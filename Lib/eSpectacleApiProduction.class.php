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
	protected $id				= false;
	protected $version			= false;
	protected $date				= '';
	protected $update			= '';
	protected $title			= '';
	protected $alphaTitle		= '';
	protected $description		= '';
	protected $creation			= '';
	protected $slug				= '';
	protected $picture			= '';
	protected $duration			= '';
	protected $pressReport		= '';
	protected $tinyPressReport	= '';
	protected $largeQRCode		= '';
	protected $smallQRCode		= '';
	protected $activities		= array();
	protected $credits			= false;
	protected $relations		= false;
	
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
	
	public function getTitle($default = false, $template = false)
	{
		return $this->processValue($this->title, $default, $template);
	}
	
	public function getAlphaTitle($default = false, $template = false)
	{
		return $this->processValue($this->alphaTitle, $default, $template);
	}
	
	public function getDescription($default = false, $template = false)
	{
		return $this->processValue($this->description, $default, $template);
	}
	
	public function getCreation($format = false, $default = false, $template = false)
	{
		if(!$format){
			return $this->publishDate;
		}
		return $this->processValue(strftime($format, $this->creation->format('U')), $default, $template);
	}
	
	public function getSlug($default = false, $template = false)
	{
		return $this->processValue($this->slug, $default, $template);
	}
	
	public function getPicture($size = 'thumb', $default = false, $template = false)
	{
		$picture = substr($this->picture, 0, -4).'_'.$size.substr($this->picture, -4);
		return $this->processValue($picture, $default, $template);
	}
	
	public function getDuration($default = false, $template = false)
	{
		return $this->processValue($this->duration, $default, $template);
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
	
	public function getCredits($default = false, $template = false)
	{
		return $this->processValue($this->credits, $default, $template);
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
						break;

					case 'qrcode-small':
						$this->smallQRCode = $child->nodeValue;
						break;
					
					case 'qrcode-large':
						$this->largeQRCode = $child->nodeValue;
						break;
								
					case 'creation':
						$this->creation = new \DateTime($child->nodeValue);
						break;
					default:
						$this->set($child->nodeName, $child->nodeValue);
				}
			}
		}
	}
}