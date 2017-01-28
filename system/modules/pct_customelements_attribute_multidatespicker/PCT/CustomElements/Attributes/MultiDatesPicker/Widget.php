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
namespace PCT\CustomElements\Attributes\MultiDatesPicker;

/**
 * Class file
 * Widget
 */
class Widget extends \Widget
{
	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'be_widget_multidatespicker';
	
	
	/**
	 * Generate the widget and return it as string
	 *
	 * @return string
	 */
	public function generate()
	{
		return parent::generate();
	}
	
	
	/**
	 * 
	 */
	public function parse($arrAttributes=array())
	{
		// set the flag that jQuery.noConflict() should be injected in the page head
		if(TL_MODE == 'BE')
		{
			$GLOBALS['MULTIDATESPICKER']['injectJqueryNoConflict'] = true;
		}
		
		if(count($arrAttributes) > 0)
		{
			$this->addAttributes($arrAttributes);
		}
		
		$objAttribute = null;
		if(isset($arrAttributes['customelements']['attr_id']) && $arrAttributes['customelements']['attr_id'] > 0)
		{
			$objAttribute = \PCT\CustomElements\Core\AttributeFactory::findById( $arrAttributes['customelements']['attr_id'] );
		}
		
		$objTemplate = new \BackendTemplate( $this->strTemplate );
		$objTemplate->name = $this->name;
		
		$objTemplate->class = '';
		$objTemplate->cssID = 'multirangepicker_'.$this->id;
		
		// values
		$varValues = $this->value;
		if(!is_array($this->value))
		{
			$varValues = array_filter(explode(',', $this->value));
		}
		
		$objTemplate->raw_values = $varValues;
		$objTemplate->value = implode(',',$varValues);
		$objTemplate->dates = $varValues;
		$objTemplate->hasDatesSelected = empty($varValues) ? false : true;
		
		return $objTemplate->parse();
	}
}