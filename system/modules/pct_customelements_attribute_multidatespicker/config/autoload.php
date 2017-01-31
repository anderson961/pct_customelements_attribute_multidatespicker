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
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'PCT\CustomElements',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	'PCT\CustomElements\Attributes\MultiDatesPicker'					=> 'system/modules/pct_customelements_attribute_multidatespicker/PCT/CustomElements/Attributes/MultiDatesPicker/MultiDatesPicker.php',	
	'PCT\CustomElements\Attributes\MultiDatesPicker\BackendHelper'		=> 'system/modules/pct_customelements_attribute_multidatespicker/PCT/CustomElements/Attributes/MultiDatesPicker/BackendHelper.php',
	'PCT\CustomElements\Attributes\MultiDatesPicker\Widget'				=> 'system/modules/pct_customelements_attribute_multidatespicker/PCT/CustomElements/Attributes/MultiDatesPicker/Widget.php',
	'PCT\CustomElements\Filters\MultiDatesPicker'						=> 'system/modules/pct_customelements_attribute_multidatespicker/PCT/CustomElements/Filters/MultiDatesPicker/MultiDatesPicker.php',	

));


/**
 * Register templates
 */
TemplateLoader::addFiles(array
(
	'be_widget_multidatespicker'		=> 'system/modules/pct_customelements_attribute_multidatespicker/templates',
	'customcatalog_filter_datesrange'	=> 'system/modules/pct_customelements_attribute_multidatespicker/templates',
));