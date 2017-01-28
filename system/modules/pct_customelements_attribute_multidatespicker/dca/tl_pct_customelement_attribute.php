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
 * Table tl_pct_customelement_attribute
 */
$objDcaHelper = \PCT\CustomElements\Helper\DcaHelper::getInstance()->setTable('tl_pct_customelement_attribute');
$strType = 'multidatespicker';

/**
 * Palettes
 */
$arrPalettes = $objDcaHelper->getPalettesAsArray('default');
$arrPalettes = $objDcaHelper->removeField('eval_tl_class_w50');
$arrPalettes = $objDcaHelper->removeField('eval_tl_class_clr');
#$arrPalettes['settings_legend'] = array('options');
$arrPalettes['template_legend:hide'][] = 'be_template';
$GLOBALS['TL_DCA']['tl_pct_customelement_attribute']['palettes'][$strType] = $objDcaHelper->generatePalettes($arrPalettes);

/**
 * Fields
 */
if($objDcaHelper->getActiveRecord()->type == $strType)
{
}

$objDcaHelper->addFields(array
(
	'be_template' => array
	(
		'label'  		=> &$GLOBALS['TL_LANG']['tl_pct_customelement_attribute']['be_template'],
		'exclude'		=> true,
		'inputType'		=> 'select',
		'options'		=> \Controller::getTemplateGroup('be_widget'),
		'eval'			=> array('tl_class'=>'w50','includeBlankOption'=>true,'chosen'=>true),
		'sql'			=> "varchar(64) NOT NULL default ''"
	),
));