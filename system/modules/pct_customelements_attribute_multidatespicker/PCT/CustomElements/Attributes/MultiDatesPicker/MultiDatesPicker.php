<?php

/**
 * Contao Open Source CMS
 * 
 * Copyright (C) 2005-2013 Leo Feyer
 * 
 * @copyright	Tim Gatzky 2017
 * @author		Tim Gatzky <info@tim-gatzky.de>
 * @client		Lars Kooymans <kooymans@webcandy.ch>
 * @package		pct_customelements, pct_customelements_plugin_customcatalog
 * @attribute	MultiDatesPicker
 * @link		http://contao.org
 */


/**
 * Namespace
 */
namespace PCT\CustomElements\Attributes;

/**
 * Class file
 * MultiRangePicker
 */
class MultiDatesPicker extends \PCT\CustomElements\Core\Attribute
{
	/**
	 * Tell the vault to store how to save the data (binary,blob)
	 * Leave empty to varchar
	 * @var boolean
	 */
	protected $saveDataAs = 'blob';
	
	/**
	 * Return the field definition
	 * @return array
	 */
	public function getFieldDefinition()
	{
		$arrEval = $this->getEval();
		
		$arrReturn = array
		(
			'label'			=> array( $this->get('title'),$this->get('description') ),
			'exclude'		=> true,
			'inputType'		=> 'text',
			'eval'			=> $arrEval,
			'input_field_callback' => array(get_class($this),'parseWidget'),
			// convert to unix
			'save_callback'	=> array
			(
				array(get_class($this),'convertToUnix'),
			),
			// convert to desired format
			'load_callback'	=> array
			(
				array(get_class($this),'convertToDate'),
			),
			'sql'			=> "text NULL",
		);
		
		return $arrReturn;
	}
	
		
	/**
	 * Generate the widget and return as html string
	 * @param object
	 * @param string
	 * @return string
	 *
	 * called from input_field_callback
	 */
	public function parseWidget($objDC,$strLabel)
	{
		$objAttribute = \PCT\CustomElements\Plugins\CustomCatalog\Core\AttributeFactory::findByCustomCatalog($objDC->field,$objDC->table);
		if($objAttribute === null)
		{
			return '';
		}
		
		// get the class
		$strClass = $GLOBALS['BE_FFL']['multidatespicker'];
		
		// get field definition
		$arrFieldDef = $this->getFieldDefinition();
		
		// get the widget attributes
		$arrAttributes = $strClass::getAttributesFromDca($arrFieldDef,$objDC->field,$varValue,$objDC->field,$objDC->table,$objDC);
	
		// @var object 
		$objWidget = new $strClass($arrAttributes);
		
		// set a custom template
		if(strlen($objAttribute->get('be_template')) > 0)
		{
			$objWidget->__set('template', $objAttribute->get('be_template'));
		}
		
		$varValue = $objDC->value;
		
		if(\Input::post('FORM_SUBMIT') == $objDC->table)
		{
			// flag as being submitted
			$objWidget->submitted = true;
			
			$varValue = \Input::post($objWidget->name);
			
			// database expects a comma-seperated string
			if(strlen($varValue) < 1)
			{
				$varValue == null;
			}
			
			// save_value callback
			if(count($arrFieldDef['save_callback']) > 0 && is_array($arrFieldDef['save_callback']))
			{
				$objDC->objAttribute = $objAttribute;
				$objDC->objWidget = $objWidget;
				foreach($arrFieldDef['save_callback'] as $callback)
				{
					if (is_array($callback))
					{
					   $varValue = \System::importStatic($callback[0])->{$callback[1]}($varValue,$objDC);	
					}
					else if(is_callable($callback))
					{
					   $varValue = $callback($varValue,$objDC,$this);
					}
				}
			}
			
			if(is_array($varValue))
			{
				$varValue = implode(',', array_map('trim',array_filter($varValue)));
			}
			
			// store value in the database
			\Database::getInstance()->prepare("UPDATE ".$objDC->table." %s WHERE id=?")->set(array($objDC->field=>$varValue))->execute($objDC->id);
		}
		
		// set value
		$objWidget->__set('value',$varValue);
		
		// pass couple more information to the widget that might become handy
		$objWidget->attribute = $objAttribute;
		$objWidget->attr_id = $arrFieldDef['attr_id'];
		
		return $objWidget->parse();
	}
	
	
	/**
	 * Parse widget callback, render the attribute in the backend
	 * @param object
	 * @param string
	 * @param array
	 * @param object
	 * @param mixed
	 * @return string
	 */
	public function parseWidgetCallback($objWidget,$strField,$arrFieldDef,$objDC)
	{
		return $this->parseWidget($objDC,$objWidget->label);
	}
	

	/**
	 * Generate the attribute in the frontend
	 * @param string
	 * @param mixed
	 * @param array
	 * @param string
	 * @param object
	 * @param object
	 * @return string
	 * called renderCallback method
	 */
	public function renderCallback($strField,$varValue,$objTemplate,$objAttribute)
	{
		$objTemplate->name = $strField;
		$objTemplate->selector = 'multidatespicker_'.$objAttribute->get('id');
		
		$arrCssID = deserialize($objAttribute->get('cssID')) ?: array();
		if(strlen($arrCssID[0]) > 0)
		{
			$objTemplate->selector = $arrCssID[0]; 
		}
		
		// values
		if(!is_array($varValue))
		{
			$varValue = array_filter(explode(',', $varValue));
		}
		
		$objTemplate->raw_values = $varValue;
		$objTemplate->value = implode(',',$varValue);
		
		$strFormat = 'm/d/Y';;
		$arrDates = array();
		$arrFormattedValues = array();
		foreach($varValue as $value)
		{
			$arrDates[] = \System::parseDate($strFormat,$value);
			$arrFormattedValues[] = \System::parseDate(\Config::get('dateFormat'),$value);
		}
		
		$arrDates = array_filter($arrDates);
		$arrFormattedValues = array_filter($arrFormattedValues);
		
		$objTemplate->dates = $arrDates;
		$objTemplate->formatted_value = implode(',',$arrFormattedValues);
		$objTemplate->hasDatesSelected = empty($arrDates) ? false : true;
				
		return $objTemplate->parse();
	}
	
	
	/**
	 * Generate wildcard value
	 * @param mixed
	 * @param object	DatabaseResult
	 * @param integer	Id of the Element ( >= CE 1.2.9)
	 * @param string	Name of the table ( >= CE 1.2.9)
	 * @return string
	 */
	 public function processWildcardValue($varValue,$objAttribute,$intElement=0,$strTable='')
	 {
		if($objAttribute->get('type') != 'daterangepicker')
	 	{
	 		return $varValue;
	 	}
	 	
	 	$strBuffer = '';
	 	
		return $strBuffer;
	 }
	 
	 
	/**
	 * Convert the unix timestamps to the desired date format
	 * @param mixed
	 * @param object
	 * @return array
	 *
	 * called from load_callback
	 */
	public function convertToDate($varValue,$objDC=null)
	{
		if(empty($varValue))
		{
			return $varValue;
		}
		
		$varValue = deserialize($varValue);
		
		if(!is_array($varValue))
		{
			$varValue = explode(',',$varValue);
		}
		
		$strFormat = 'm/d/Y';
		if(strlen($objDC->customFormat) > 0)
		{
			$strFormat = $objDC->customFormat;
		}
		
		$arrReturn = array();
		foreach($varValue as $value)
		{
			// convert unix timestamps back to date format
			if(is_int((int)$value) && is_numeric($value))
			{
				$value = \System::parseDate($strFormat,$value);
			}
			$arrReturn[] = $value;
		}
		
		return $arrReturn;
	}
	
	
	/**
	 * Convert the dates to unix timestamps
	 * @param mixed
	 * @param object
	 * @return array
	 *
	 * called from save_callback
	 */
	public function convertToUnix($varValue,$objDC=null)
	{
		if(empty($varValue))
		{
			return $varValue;
		}
		
		$varValue = deserialize($varValue);
		
		if(!is_array($varValue))
		{
			$varValue = explode(',',$varValue);
		}
		
		$strFormat = 'm/d/Y';
		if(strlen($objDC->customFormat) > 0)
		{
			$strFormat = $objDC->customFormat;
		}
		
		$arrReturn = array();
		foreach($varValue as $value)
		{
			// convert date formats to unix timestamps
			if(is_string($value) && !is_numeric($value) && !is_int($value))
			{
				$objDate = new \Date($value,$strFormat);
				$value = $objDate->__get('tstamp');
			}
			$arrReturn[] = $value;
		}
		
		return $arrReturn;
	}	
}