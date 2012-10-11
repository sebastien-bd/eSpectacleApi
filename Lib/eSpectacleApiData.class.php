<?php
/*
 * This file is part of the e-Spectacle API package.
 * (c) 2011-2012 Sébastien Bernard <sebastien.bernard@e-spectacle.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * eSpectacleApiData offers convenience methods to handle unknown datas from XML file.
 * 
 * @author	Sébastien Bernard <sebastien.bernard@e-spectacle.fr>
 */
namespace eSpectacle\eSpectacleApi\eSpectacleApiData;

use eSpectacle\eSpectacleApi\eSpectacleApiElement;

class eSpectacleApiData extends eSpectacleApiElement
{
	public $root = false;

	public function __construct($xml)
	{
		$this->dom = new DomDocument();
    	$this->dom->loadXml($xml, LIBXML_NOBLANKS);
    	
    	$this->element = $this->dom->documentElement;
	}
	
	public function getObject()
	{
		return $this->root;
	}
	
	/**
	 * Initializes the object with a XML content
	 * @param string $xml
	 */
	public function load()
	{
		$root = $this->dom->getElementsByTagName('external-root');
    	$root = $root->item(0);
    	$this->root = $this->extract($root->getAttribute('type'), $root->getAttribute('id'));

    	$this->loaded = true;
    	return $this->root;
    }

    
}