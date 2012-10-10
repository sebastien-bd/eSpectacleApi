<?php
/*
 * This file is part of the e-Spectacle API package.
 * (c) 2011-2012 Sébastien Bernard <sebastien.bernard@e-spectacle.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * eSpectacleApiRelation offers convenience methods to handle relation between elements.
 * 
 * @author	Sébastien Bernard <sebastien.bernard@e-spectacle.fr>
 */
class eSpectacleApiRelation extends eSpectacleApiElement
{
	protected $id			= false;
	protected $activity		= '';
	protected $status		= '';
	protected $calendar		= false;
	protected $production	= false;
	protected $organization	= false;
	
	public function getId()
	{
		return $this->id;
	}
	
	public function getActivity()
	{
		return $this->activity;
	}
	
	public function getCalendar()
	{
		return $this->calendar;
	}
	
	public function getStatus()
	{
		return $this->status;
	}
	
	public function getProduction()
	{
		return $this->production;
	}
	
	public function getOrganization()
	{
		return $this->organization;
	}
	
	public function load()
	{
		$this->id = $this->element->getAttribute('id');
		$this->version = $this->element->getAttribute('version');
		$this->date = $this->element->getAttribute('date');
		$this->activity = $this->element->getAttribute('activity');
		
		foreach($this->element->childNodes as $child)
		{
			if($child->nodeType == XML_ELEMENT_NODE)
			{
				switch($child->nodeName)
				{
					case 'external-element':
						$param = $child->getAttribute('type');
						$this->set($param, new eSpectacleApiExternal($child, $this->dom));
						break;
						
					case 'calendar':
						$this->calendar = new eSpectacleApiCalendar($child);
						break;
					
					default:
						$this->set($child->nodeName, $child->nodeValue);
				}
			}
		}
	}
}