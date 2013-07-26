<?php
/*
 * This file is part of the e-Spectacle API package.
 * (c) 2011-2012 Sébastien Bernard <sebastien.bernard@e-spectacle.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * eSpectacleApiPressNewspaper offers convenience methods to handle calendar from a relation element.
 * 
 * @author	Sébastien Bernard <sebastien.bernard@e-spectacle.fr>
 */
namespace eSpectacle\eSpectacleApi;

class eSpectacleApiOffer extends eSpectacleApiElement implements eSpectacleApiServiceInterface
{
	protected $id			= '';
	protected $provider		= false;
	protected $label		= '';
	protected $price		= '';
	protected $description	= '';

	public static function getServiceName()
	{
		return 'Offer';
	}

	public static function getServiceType()
	{
		return 'Many';
	}

	public function getId()
	{
		$this->id;
	}
	
	public function getProvider()
	{
		return $this->provider;
	}

	public function getLabel($default = false, $template = false)
	{
		return $this->processValue($this->label, $default, $template);
	}

	public function getPrice($default = false, $template = false)
	{
		return $this->processValue($this->price, $default, $template);
	}

	public function getDescription($default = false, $template = false)
	{
		return $this->processValue($this->description, $default, $template);
	}
	
	protected function load()
	{
		$this->id = $this->element->getAttribute('id');
		
		foreach($this->element->childNodes as $child)
		{
			if($child->nodeType == XML_ELEMENT_NODE)
			{
				switch($child->nodeName)
				{
					case 'external-element':
						if($child->hasAttribute('alias')){
							$param = $child->getAttribute('alias');
						}else{
							$param = $child->getAttribute('type');
						}
						$this->set($param, new eSpectacleApiExternal($child, $this->dom));
						break;
					default:
						$this->set($child->nodeName, $child->nodeValue);
				}
			}
		}
	}
}