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
 * @filter		MultiDatesPicker
 * @link		http://contao.org
 */


/**
 * Namespace
 */
namespace PCT\CustomElements\Filters;

/**
 * Class file
 * MultiRangePicker
 */
class MultiDatesPicker extends \PCT\CustomElements\Filter
{
	/**
	 * The attribute
	 * @param object
	 */
	protected $objAttribute = null;


	/**
	 * Init
	 */
	public function __construct($arrData=array())
	{
		$this->setData($arrData);

		// fetch the attribute the filter works on
		$this->objAttribute = \PCT\CustomElements\Core\AttributeFactory::findById($this->get('attr_id'));

		// set the filter name
		$this->setName( $this->get('urlparam') ? $this->get('urlparam') : $this->objAttribute->alias );

		// point the filter to the attribute
		$this->setFilterTarget( $this->objAttribute->alias );
	}
	
	
	/**
	 * Prepare the sql query array for this filter and return it as array
	 * @return array
	 * 
	 * called from getQueryOption() in \PCT\CustomElements\Filter
	 */	
	public function getQueryOptionCallback()
	{
		$arrSettings = $GLOBALS['PCT_CUSTOMELEMENTS']['FILTERS'][$objFilter->type]['settings'];
		
		$blnAutoMode = $arrSettings['autoMode'];
		$strPublished = ($GLOBALS['PCT_CUSTOMCATALOG']['FRONTEND']['FILTER']['publishedOnly'] ? $this->getCustomCatalog()->getPublishedField() : '');
		$strTarget = $this->getFilterTarget();
		
		$arrOptions = array();
		
		// if user filters manually and days are different
		$arrRange = array();
		if(count($this->getValue($this->getName().'_start')) > 0 || count($this->getValue($this->getName().'_stop')) > 0 && ($this->getValue($this->getName().'_start') != $this->getValue($this->getName().'_stop')) )
		{
			$arrRange = array_filter(array_merge($this->getValue($this->getName().'_start'), $this->getValue($this->getName().'_stop')));
			$blnAutoMode = false;
			$strTarget = $this->getName();
		}
		
		if($blnAutoMode === true)
		{
			$varValue = implode('',$this->getValue());
			
			if(strlen($this->get('defaultValue')) > 0)
			{
				$varValue = $this->get('defaultValue');
			}
			
			// if no filter value is set use current timestamp (today) as value
			if(empty($varValue))
			{
				$objDate = new \Date();
				$varValue = $objDate->__get('dayBegin');
			}
			
			$intToday = $varValue;
			
			// build sql query array
			$arrOptions = array
			(
				'column'	=> $strTarget,
				'where'		=> ($this->get('mode') == 'sub' ? ' NOT ' : '').'FIND_IN_SET('.$intToday.','.$strTarget.')',
			);
		}
		else
		{
			// @var object
			$objCache = new \PCT\CustomElements\Plugins\CustomCatalog\Core\Cache();
			
			$strFormat = $arrSettings['dateFormat'] ?: 'd-m-Y';
			
			// create Date objects
			// @var object \Date
			$objDateStart = new \Date($arrRange[0],$strFormat);
			$objDateStop = new \Date($arrRange[1],$strFormat);
			
			$intStart = $objDateStart->__get('dayBegin');
			$intStop = $objDateStop->__get('dayBegin');
		
			// use default value as date start if not set
			if(strlen($arrRange[0]) < 1 && strlen($this->get('defaultValue')) > 0)
			{
				$intStart = $this->get('defaultValue');		
			}
		
			if(strlen($arrRange[1]) < 1)
			{
				$strFuture = $arrSettings['futureDate'] ?: '+3 month';
				$intStop = date('U', strtotime($strFuture,$objDateStart->__get('tstamp')));
			}
			
			// @var object DateTime
			$objDateTimeStart = \DateTime::createFromFormat('U', $intStart);
			$objDateTimeStop = \DateTime::createFromFormat('U', $intStop);
			
			// @var object DatePeriod
			$objPeriod = new \DatePeriod($objDateTimeStart, new \DateInterval('P1D'), $objDateTimeStop);
			
			// build single LIKE statements because mysql cannot search in comma-seperated string values
			$arrQuery = array();
			foreach($objPeriod as $date)
			{
				$objDate = new \Date($date->format('U'));
				$arrQuery[] = $strTarget.' LIKE '."'%".$objDate->__get('dayBegin')."%'";
			}
			
			// look up from cache
			$objRows = $objCache::getDatabaseResult('MultiDatesPicker::findAll'.(strlen($strPublished) > 0 ? 'Published' : ''),$strTarget);
			if($objRows === null)
			{
				$combiner = $this->get('mode') == 'sub' ? 'AND' : 'OR'; 
				
				$objRows = \Database::getInstance()->prepare("SELECT id FROM ".$this->getTable()." WHERE ".$strTarget." IS NOT NULL AND (".implode(' '.$combiner.' ',$arrQuery).")" .(strlen($strPublished) > 0 ? " AND ".$strPublished."=1" : ""))->execute();
				// add to cache
				$objCache::addDatabaseResult('MultiDatesPicker::findAll'.(strlen($strPublished) > 0 ? 'Published' : ''),$strTarget,$objRows);
			}
			
			if($objRows->numRows < 1)
			{
				return array();
			}
			
			$arrOptions = array
			(
				'column' 	=> 'ID',
				'operation'	=> 'IN',
				'value'		=> $objRows->fetchEach('id')
			);
			
		}
		
		return $arrOptions;
	}
	
	
	/**
	 * Render the filter and return string
	 * @param string	Name of the filter
	 * @param array		Active filter values
	 * @param object	Template object
	 * @param object	The current filter object
	 * @return string
	 */
	public function renderCallback($strName,$varValue,$objTemplate,$objFilter)
	{
		$objTemplate->name = $strName;
		$objTemplate->label = $this->get('label') ?:  $this->get('title');
		$objTemplate->value = $varValue;
		
		// start
		$objTemplate->value_start = implode('',$this->getValue($strName.'_start'));
		// stop
		$objTemplate->value_stop = implode('',$this->getValue($strName.'_stop'));
		
		// date formats
		$objTemplate->js_dateFormat = $GLOBALS['PCT_CUSTOMELEMENTS']['FILTERS'][$objFilter->type]['js_dateFormat'] ?: 'dd-mm-yy';
		$objTemplate->php_dateFormat = $GLOBALS['PCT_CUSTOMELEMENTS']['FILTERS'][$objFilter->type]['dateFormat'] ?: 'd-m-Y';
		
		return $objTemplate->parse();
	}
}