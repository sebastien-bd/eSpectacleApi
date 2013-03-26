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
	protected $update		= '';
	
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
		return call_user_func_array(array($this->object, $name), $arguments);
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
	
	public function getUpdate()
	{
		return $this->update;
	}

	public function load()
	{
		$this->id = $this->element->getAttribute('id');
		$this->type = $this->element->getAttribute('type');
		$this->update = new \DateTime($this->element->getAttribute('update'));

		foreach($this->element->childNodes as $child)
		{
			if($child->nodeType == XML_ELEMENT_NODE)
			{
				$this->set($child->nodeName, $child->nodeValue);
			}
		}
	}
	
	public function isLoaded()
	{
		if(!$this->object){
			return false;
		}else{
			return $this->object->isLoaded();
		}
	}

	public function isAvailable()
	{
		if(is_null($this->available)){
			if($this->isLoaded()){
				$available = true;
			}else{
				$available = $this->exists($this->type, $this->id);
			}
			$this->available = $available;
		}
		return $this->available;
	}
	
}