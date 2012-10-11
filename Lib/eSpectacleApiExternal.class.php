<?php
/*
 * This file is part of the e-Spectacle API package.
 * (c) 2011-2012 Sébastien Bernard <sebastien.bernard@e-spectacle.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * eSpectacleApiExternal offers convenience methods to handle element not yet loaded.
 * 
 * @author	Sébastien Bernard <sebastien.bernard@e-spectacle.fr>
 */
namespace eSpectacle\eSpectacleApi;

class eSpectacleApiExternal extends eSpectacleApiElement
{
	protected $type			= false;
	protected $id			= false;
	protected $name			= '';
	protected $activities	= array();
	
	protected $object	= false;
	
	public function __call($name, $arguments)
	{
		if(!$this->loaded)
		{
			$this->loadElement();
		}
		if(!$this->object)
		{
			$this->object = $this->extract($this->type, $this->id);
		}
		//return call_user_func_array(array(get_class($this->object), $name), $arguments);
		return $this->object->$name();
	}
	
	public function getActivities()
	{
		return $this->activities;
	}
	
	public function getId()
	{
		return $this->id;
	}
	
	public function getType()
	{
		return $this->type;
	}
	
	public function getName()
	{
		return $this->name;
	}
	
	public function load()
	{
		$this->id = $this->element->getAttribute('id');
		$this->type = $this->element->getAttribute('type');
		$this->activities = explode(', ', $this->element->getAttribute('activity'));
		
		foreach($this->element->childNodes as $child)
		{
			if($child->nodeType == XML_ELEMENT_NODE)
			{
				$this->set($child->nodeName, $child->nodeValue);
			}
		}
	}
	
}