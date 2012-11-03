<?php
/*
 * This file is part of the e-Spectacle API package.
 * (c) 2011-2012 Sébastien Bernard <sebastien.bernard@e-spectacle.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * eSpectacleApiCredits offers convenience methods to handle credits from an element.
 * 
 * @author	Sébastien Bernard <sebastien.bernard@e-spectacle.fr>
 */
namespace eSpectacle\eSpectacleApi;

class eSpectacleApiCredits extends eSpectacleApiElement
{
	protected $display	= '';
	
	public function getDisplay()
	{
		return $this->display;
	}
	
	protected function load()
	{
		foreach($this->element->childNodes as $child)
		{
			if($child->nodeType == XML_ELEMENT_NODE)
			{
				switch($child->nodeName)
				{
					case 'display':
						$div = $child->getElementsByTagName('div');
						$div = $div->item(0);
						$this->display = $div->C14N();
						break;

					default:
						$this->set($child->nodeName, $child->nodeValue);
				}
			}
		}
	}
}