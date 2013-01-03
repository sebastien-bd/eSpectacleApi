<?php
/*
 * This file is part of the e-Spectacle API package.
 * (c) 2011-2012 Sébastien Bernard <sebastien.bernard@e-spectacle.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * eSpectacleApiProduction offers convenience methods to handle Production elements.
 * 
 * @author	Sébastien Bernard <sebastien.bernard@e-spectacle.fr>
 */
namespace eSpectacle\eSpectacleApi;

class eSpectacleApiProduction extends eSpectacleApiElement
{
	protected $id			= false;
	protected $version		= false;
	protected $date			= '';
	protected $update		= '';
	protected $title		= '';
	protected $alphaTitle	= '';
	protected $description	= '';
	protected $creation		= '';
	protected $slug			= '';
	protected $picture		= '';
	protected $thumbnail	= '';
	protected $duration		= '';
	protected $fingerprint	= '';
	protected $largeQRCode	= '';
	protected $smallQRCode	= '';
	protected $activities	= array();
	protected $credits		= false;
	protected $relations	= false;
	
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

	public function getUpdate()
	{
		return $this->update;
	}
	
	public function getTitle()
	{
		return $this->title;
	}
	
	public function getAlphaTitle()
	{
		return $this->alphaTitle;
	}
	
	public function getDescription()
	{
		return $this->description;
	}
	
	public function getCreation()
	{
		return $this->creation;
	}
	
	public function getSlug()
	{
		return $this->slug;
	}
	
	public function getPicture()
	{
		return $this->picture;
	}
	
	public function getThumbnail()
	{
		return $this->thumbnail;
	}
	
	public function getDuration()
	{
		return $this->duration;
	}
	
	public function getActivities($activity = false, $order = false)
	{
		if(!$activity)
		{
			return $this->getActivitiesOrder(array_keys($this->activities));
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
	
	public function getCredits()
	{
		return $this->credits;
	}

	public function getFingerprint()
	{
		return $this->fingerprint;
	}

	public function getQRCode($size)
	{
		$property = $size."QRCode";
		return $this->$property;
	}

	public function getRelations()
	{
		return $this->relations;
	}
	
	public function load()
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
					
					case 'relations':
						foreach($child->childNodes as $relation)
						{
							$newRelation = new eSpectacleApiExternal($relation, $this->dom);
							foreach($newRelation->getActivities() as $activity)
							{
								if(!isset($this->activities[$activity]))
								{
									$this->activities[$activity] = array();
								}
								$this->activities[$activity][] = $newRelation;
							}
							$this->relations[] = $newRelation;
						}
						//ksort($this->activities);
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