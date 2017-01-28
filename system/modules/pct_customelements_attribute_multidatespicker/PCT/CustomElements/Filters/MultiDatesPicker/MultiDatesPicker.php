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

		// point the filter to the attribute or use the urlparameter
		$name = $this->get('urlparam') ? $this->get('urlparam') : $this->objAttribute->alias;
		$target = $this->objAttribute->alias;

		// set the filter name
		$this->setName($name);

		// point the filter to the attribute
		$this->setFilterTarget($target);
	}
	
	
		/**
	 * Prepare the sql query array for this filter and return it as array
	 * @return array
	 * 
	 * called from getQueryOption() in \PCT\CustomElements\Filter
	 */	
	public function getQueryOptionCallback()
	{
		$value = implode('',$this->getValue());
		
		if(strlen($this->get('defaultValue') > 0))
		{
			$value = $this->get('defaultValue');
		}
		
		throw new \Exception('--- STOP ---');
		
		$value = $this->getUnix($value);
		
		if(empty($value))
		{
			return array();
		}

		$t = $this->getFilterTarget();
		
		// compare to current time if no target has been set
		if(strlen($t) < 1)
		{
			$t = time();	
		}
		
		$where = '';
		switch($this->get('mode'))
		{
		
		}
		
		$options = array
		(
		#	'column'	=> $t,
		#	'where'		=> '('.$where . ($this->bolAllowEmpty ? ' OR '.$t."='' " : '').')',
		);
		
		return $options;
	}
}