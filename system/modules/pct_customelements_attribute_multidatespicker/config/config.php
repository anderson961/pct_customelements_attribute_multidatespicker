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
 * Constants
 */
define('PCT_CUSTOMELEMENTS_MULTIDATESPICKER_PATH', 'system/modules/pct_customelements_attribute_multidatespicker');


/**
 * Globals
 */
$GLOBALS['PCT_CUSTOMELEMENTS_ATTRIBUTES_MULTIDATESPICKER']['script'] = PCT_CUSTOMELEMENTS_MULTIDATESPICKER_PATH.'/assets/js/multidatespicker/jquery-ui.multidatespicker.js'.


/**
 * Register attribute
 */
$GLOBALS['PCT_CUSTOMELEMENTS']['ATTRIBUTES']['multidatespicker'] = array
(
	'label'		=> &$GLOBALS['TL_LANG']['PCT_CUSTOMELEMENTS']['ATTRIBUTES']['multidatespicker'],
	'path' 		=> PCT_CUSTOMELEMENTS_MULTIDATESPICKER_PATH,
	'class'		=> 'PCT\CustomElements\Attributes\MultiDatesPicker',
	'icon'		=> 'fa fa-calendar',
);


/**
 * Register filter
 */
$GLOBALS['PCT_CUSTOMELEMENTS']['FILTERS']['multidatespicker'] = array
(
	'label'				=> &$GLOBALS['TL_LANG']['PCT_CUSTOMELEMENTS']['FILTERS']['multidatespicker'],
	'path' 				=> PCT_CUSTOMELEMENTS_MULTIDATESPICKER_PATH,
	'class'				=> 'PCT\CustomElements\Filters\MultiDatesPicker',
	'icon'				=> 'fa fa-calendar',
	'settings'			=> array
	(
		'autoMode'			=> false, // if set to true the filter works without user input
		'dateFormat'		=> 'd-m-Y', // date format in php
		'js_dateFormat'		=> 'dd-mm-yy',	// date format the datepicker uses. It must match the php date format
		'futureDate'		=> '+3 month' // added to start day when no end day is set
	)
);


/**
 * Register backend form field
 */
$GLOBALS['BE_FFL']['multidatespicker'] = 'PCT\CustomElements\Attributes\MultiDatesPicker\Widget';


/**
 * Hooks
 */
$GLOBALS['TL_HOOKS']['parseBackendTemplate'][] = array('PCT\CustomElements\Attributes\MultiDatesPicker\BackendHelper','injectJavascriptInBackendPage');
#$GLOBALS['CUSTOMELEMENTS_HOOKS']['processWildcardValue'][] = array('PCT\CustomElements\Attributes\Gallery','processWildcardValue');
