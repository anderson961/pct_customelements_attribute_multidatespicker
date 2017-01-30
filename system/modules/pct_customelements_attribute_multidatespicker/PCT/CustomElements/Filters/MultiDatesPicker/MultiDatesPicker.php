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
		$varValue = implode('',$this->getValue());
		
		if(strlen($this->get('defaultValue') > 0))
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
		$strTarget = $this->getFilterTarget();
		
		$options = array
		(
			'column'	=> $strTarget,
			'where'		=> ($this->get('mode') == 'sub' ? ' NOT ' : '').'FIND_IN_SET('.$intToday.','.$strTarget.')',
		);
		
		return $options;
	}
}