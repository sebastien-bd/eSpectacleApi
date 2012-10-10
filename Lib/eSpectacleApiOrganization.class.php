<?php
/*
 * This file is part of the e-Spectacle API package.
 * (c) 2011-2012 Sébastien Bernard <sebastien.bernard@e-spectacle.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * eSpectacleApiOrganization offers convenience methods to handle Organization structure.
 * 
 * @author	Sébastien Bernard <sebastien.bernard@e-spectacle.fr>
 */
class eSpectacleApiOrganization extends eSpectacleApiElement
{
	protected $id					= false;
	protected $version				= false;
	protected $date					= '';
	protected $completeName 		= '';
	protected $name 				= '';
	protected $publicPhonenumber 	= '';
	protected $privatePhonenumber	= '';
	protected $email 				= '';
	protected $website 				= '';
	protected $location 			= false;
	protected $activities			= array();
	protected $relations 			= array();
	
	public function getActivities($activity = false)
	{
		if(!$activity)
		{
			return array_keys($this->activities);
		}
		elseif(isset($this->activities[$activity]))
		{
			return $this->activities[$activity];
		}
		else 
		{
			return array();
		}
	}
	
	public function getCompleteName()
	{
		return $this->completeName;
	}
	
	public function getName()
	{
		return $this->name;
	}
	
	public function getPublicPhonenumber()
	{
		return $this->publicPhonenumber;
	}
	
	public function getPrivatePhonenumber()
	{
		return $this->privatePhonenumber;
	}
	
	public function getEmail()
	{
		return $this->email;
	}
	
	public function getWebsite()
	{
		return $this->website;
	}
	
	public function getLocation()
	{
		return $this->location;
	}
	
	public function getRelations()
	{
		return $this->relations;
	}
	
	protected function load()
	{
		$this->id = $this->element->getAttribute('id');
		$this->version = $this->element->getAttribute('version');
		$this->date = $this->element->getAttribute('date');
		foreach($this->element->childNodes as $child)
		{
			if($child->nodeType == XML_ELEMENT_NODE)
			{
				switch($child->nodeName)
				{
					case 'location':
						$this->location = new eSpectacleApiLocation($child);
						break;
					
					case 'activities':
						foreach($child->childNodes as $activity)
						{
							if(!isset($this->activities[$activity->nodeValue]))
							{
								$this->activities[$activity->nodeValue] = array();
							}
						}
						break;
					
					case 'relations':
						foreach($child->childNodes as $relation)
						{
							$newRelation = new eSpectacleApiExternal($relation, $this->dom);
							foreach($newRelation->getActivities() as $activity)
							{
								$this->activities[$activity][] = $newRelation;
							}
							$this->relations[] = $newRelation;
						}
						break;
						
					default:
						$this->set($child->nodeName, $child->nodeValue);
				}
			}
		}
	}
}