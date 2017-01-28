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
 * Table tl_pct_customelement_filter
 */
$objDcaHelper = \PCT\CustomElements\Helper\DcaHelper::getInstance()->setTable('tl_pct_customelement_filter');
$strType = 'multidatespicker';

/**
 * Palettes
 */
$arrPalettes = $objDcaHelper->getPalettesAsArray('default');
$arrPalettes['settings_legend'][] = 'attr_id';
$arrPalettes['settings_legend'][] = 'label';
$arrPalettes['settings_legend'][] = 'defaultValue';
$arrPalettes['settings_legend'][] = 'mode'; 
#$arrPalettes['settings_legend'][] = 'includeReset';
array_insert($arrPalettes['expert_legend'],1,array('conditional'));
$GLOBALS['TL_DCA']['tl_pct_customelement_filter']['palettes'][$strType] = $objDcaHelper->generatePalettes($arrPalettes);

if($objDcaHelper->getActiveRecord()->type == $strType)
{
	#if(\Input::get('act') == 'edit' && \Input::get('table') == $objDcaHelper->getTable())
	#{
	#	// Show template info
	#	\Message::addInfo(sprintf($GLOBALS['TL_LANG']['PCT_CUSTOMCATALOG']['MSC']['templateInfo_filter'], 'customcatalog_filter_tags'));
	#}
	#
	$GLOBALS['TL_DCA']['tl_pct_customelement_filter']['fields']['attr_id']['options_values'] = array('multidatespicker','simpleColumn');
	#$GLOBALS['TL_DCA']['tl_pct_customelement_filter']['fields']['template']['default'] = 'customcatalog_filter_tags';

	$GLOBALS['TL_DCA'][$objDcaHelper->getTable()]['fields']['mode']['label'] = $GLOBALS['TL_LANG']['tl_pct_customelement_filter']['multidatespicker_mode'];
	$GLOBALS['TL_DCA'][$objDcaHelper->getTable()]['fields']['mode']['inputType'] = 'select';
	$GLOBALS['TL_DCA'][$objDcaHelper->getTable()]['fields']['mode']['options'] = array('add','sub');
	$GLOBALS['TL_DCA'][$objDcaHelper->getTable()]['fields']['mode']['reference'] = $GLOBALS['TL_LANG']['tl_pct_customelement_filter']['multidatespicker_mode'];
	$GLOBALS['TL_DCA'][$objDcaHelper->getTable()]['fields']['mode']['eval'] = array('tl_class'=>'clr','chosen'=>true);	

}