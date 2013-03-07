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
namespace eSpectacle\eSpectacleApi;

class eSpectacleApiOrganization extends eSpectacleApiElement
{
	protected $id					= false;
	protected $version				= false;
	protected $slug					= '';
	protected $pressReport			= '';
	protected $tinyPressReport		= '';
	protected $date					= '';
	protected $update				= '';
	protected $completeName 		= '';
	protected $name 				= '';
	protected $publicPhonenumber 	= '';
	protected $privatePhonenumber	= '';
	protected $email 				= '';
	protected $website 				= '';
	protected $largeQRCode			= '';
	protected $smallQRCode			= '';
	protected $location 			= false;
	protected $activities			= array();
	protected $credits				= false;
	protected $relations 			= array();

	public function getId()
	{
		return $this->id;
	}

	public function getVersion()
	{
		return $this->version;
	}
	
	public function getDate()
	{
		return $this->date;
	}

	public function getSlug($default = false, $template = false)
	{
		return $this->processValue($this->slug, $default, $template);
	}
	
	public function getUpdate()
	{
		return $this->update;
	}
	
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
	
	public function getCompleteName($default = false, $template = false)
	{
		return $this->processValue($this->completeName, $default, $template);
	}
	
	public function getName($default = false, $template = false)
	{
		return $this->processValue($this->name, $default, $template);
	}
	
	public function getPublicPhonenumber($default = false, $template = false)
	{
		return $this->processValue($this->publicPhonenumber, $default, $template);
	}
	
	public function getPrivatePhonenumber($default = false, $template = false)
	{
		return $this->processValue($this->privatePhonenumber, $default, $template);
	}
	
	public function getEmail($default = false, $template = false)
	{
		return $this->processValue($this->email, $default, $template);
	}
	
	public function getWebsite($default = false, $template = false)
	{
		return $this->processValue($this->website, $default, $template);
	}
	
	public function getLocation()
	{
		return $this->location;
	}

	public function getPressReport($default = false, $template = false)
	{
		return $this->processValue($this->pressReport, $default, $template);
	}
	
	public function getTinyPressReport($default = false, $template = false)
	{
		return $this->processValue($this->tinyPressReport, $default, $template);
	}
	
	public function getQRCode($size)
	{
		$property = $size."QRCode";
		return $this->$property;
	}

	public function getCredits($default = false, $template = false)
	{
		return $this->credits;
	}
	
	public function getRelations()
	{
		return $this->relations;
	}
	
	protected function load()
	{
		$this->id = $this->element->getAttribute('id');
		$this->version = $this->element->getAttribute('version');
		$this->date = new \DateTime($this->element->getAttribute('date'));
		$this->update = new \DateTime($this->element->getAttribute('update'));
		foreach($this->element->childNodes as $child)
		{
			if($child->nodeType == XML_ELEMENT_NODE)
			{
				switch($child->nodeName)
				{
					case 'credits':
						$this->credits = new eSpectacleApiCredits($child);
						break;
					
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

					case 'qrcode-small':
						$this->smallQRCode = $child->nodeValue;
						break;
					
					case 'qrcode-large':
						$this->largeQRCode = $child->nodeValue;
						break;
								
					default:
						$this->set($child->nodeName, $child->nodeValue);
				}
			}
		}
	}
}