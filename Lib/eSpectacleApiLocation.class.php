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
	
	public function getComplete()
	{
		return $this->complete;
	}
	
	public function getAddress()
	{
		return $this->address;
	}
	
	public function getPostcode()
	{
		return $this->postcode;
	}
	
	public function getCity()
	{
		return $this->city;
	}
	
	public function getDepartmentCode()
	{
		return $this->departmentCode;
	}
	
	public function getDepartmentName()
	{
		return $this->departmentName;
	}
	
	public function getRegion()
	{
		return $this->region;
	}
	
	public function getLat()
	{
		return $this->lat;
	}
	
	public function getLng()
	{
		return $this->lng;
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