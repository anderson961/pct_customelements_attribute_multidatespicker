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
 * BackendHelper
 */
class BackendHelper
{
	/**
	 * Inject javascript in the backend page
	 * @param string
	 * @param string
	 * @return string
	 */
	public function injectJavascriptInBackendPage($strBuffer,$strTemplate)
	{
		if($strTemplate == 'be_main' && $GLOBALS['MULTIDATESPICKER']['injectJqueryNoConflict'] === true)
		{
			$strBuffer = str_replace('</head>','<script>$.noConflict();jQuery.noConflict();</script></head>', $strBuffer);
		}
		return $strBuffer;
	}
}