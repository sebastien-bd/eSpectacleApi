<?php
/*
 * This file is part of the e-Spectacle API package.
 * (c) 2011-2012 Sébastien Bernard <sebastien.bernard@e-spectacle.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * eSpectacleApiApplication offers convenience methods to handle relation with application.
 * 
 * @author	Sébastien Bernard <sebastien.bernard@e-spectacle.fr>
 */
namespace eSpectacle\eSpectacleApi;

class eSpectacleApiApplication extends eSpectacleApiElement
{
	protected $id			= false;
	protected $name			= false;
	protected $url			= false;
	protected $description	= '';
	
	public function getId()
	{
		return $this->id;
	}

	public function getName()
	{
		return $this->name;
	}

	public function getUrl()
	{
		return $this->url;
	}

	public function getDescription()
	{
		return $this->description;
	}
	
	public function load()
	{
		$this->id = $this->element->getAttribute('id');
		
		foreach($this->element->childNodes as $child)
		{
			if($child->nodeType == XML_ELEMENT_NODE)
			{
				$this->set($child->nodeName, $child->nodeValue);
			}
		}
	}
}