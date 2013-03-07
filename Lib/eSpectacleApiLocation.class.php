<?php
/*
 * This file is part of the e-Spectacle API package.
 * (c) 2011-2012 Sébastien Bernard <sebastien.bernard@e-spectacle.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * eSpectacleApiLocation offers convenience methods to handle localized element.
 * 
 * @author	Sébastien Bernard <sebastien.bernard@e-spectacle.fr>
 */
namespace eSpectacle\eSpectacleApi;

class eSpectacleApiLocation extends eSpectacleApiElement
{
	protected $complete			= '';
	protected $address			= '';
	protected $postcode			= '';
	protected $city				= '';
	protected $departmentCode	= '';
	protected $departmentName	= '';
	protected $region			= '';
	protected $lat				= '';
	protected $lng				= '';
	
	public function getComplete($default = false, $template = false)
	{
		return $this->processValue($this->complete, $default, $template);
	}
	
	public function getAddress($default = false, $template = false)
	{
		return $this->processValue($this->address, $default, $template);
	}
	
	public function getPostcode($default = false, $template = false)
	{
		return $this->processValue($this->postcode, $default, $template);
	}
	
	public function getCity($default = false, $template = false)
	{
		return $this->processValue($this->city, $default, $template);
	}
	
	public function getDepartmentCode($default = false, $template = false)
	{
		return $this->processValue($this->departmentCode, $default, $template);
	}
	
	public function getDepartmentName($default = false, $template = false)
	{
		return $this->processValue($this->departmentName, $default, $template);
	}
	
	public function getRegion($default = false, $template = false)
	{
		return $this->processValue($this->region, $default, $template);
	}
	
	public function getLat($default = false, $template = false)
	{
		return $this->processValue($this->lat, $default, $template);
	}
	
	public function getLng($default = false, $template = false)
	{
		return $this->processValue($this->lng, $default, $template);
	}
	
	protected function load()
	{
		foreach($this->element->childNodes as $child)
		{
			if($child->nodeType == XML_ELEMENT_NODE)
			{
				switch($child->nodeName)
				{
					case 'department':
						$this->departmentCode = $child->getAttribute('code');
						$this->departmentName = $child->nodeValue;
						break;
						
					default:
						$this->set($child->nodeName, $child->nodeValue);
				}
			}
		}
		$this->loaded = true;
	}
}