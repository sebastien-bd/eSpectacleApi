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

class eSpectacleApiActivities extends eSpectacleApiElement
{
	protected $activities		= array();
	protected $archives			= array();
	
	public function hasActivities($activity = false)
	{
		if(!$activity){
			return count($this->activities);
		}elseif(isset($this->activities[$activity])){
			return count($this->activities[$activity]);
		}else {
			return false;
		}
	}

	public function hasArchives($activity = false)
	{
		if(!$activity){
			return count($this->archives);
		}elseif(isset($this->archives[$activity])){
			return count($this->archives[$activity]);
		}else {
			return false;
		}
	}

	public function hasRelations()
	{
		return count(array_merge($this->activities, $this->archives));
	}
	
	public function getRelations($activity = false)
	{
		if(!$activity){
			$keys = array_unique(array_merge(array_keys($this->activities), array_keys($this->archives)));
			return $this->getActivitiesOrder($keys);
		}else{
			$result = array();
			if(isset($this->activities[$activity])){
				$result = array_merge($result, $this->activities[$activity]);
			}
			if(isset($this->archives[$activity])){
				$result = array_merge($result, $this->archives[$activity]);
			}
			return $result;
		}
		return array_merge($this->activities, $this->archives);
	}
	
	public function getActivitiesLabels($display = 'activities')
	{
		return $this->getActivitiesOrder(array_keys($this->$display));
	}

	public function getActivities($activity = false, $start = 0, $count = false)
	{
		if(isset($this->activities[$activity])){
			if($count){
				return array_slice($this->activities[$activity], $start, $count);
			}else{
				return $this->activities[$activity];
			}
		}else {
			return array();
		}
	}

	public function getArchives($activity = false, $start = 0, $count = false)
	{
		if(isset($this->archives[$activity])){
			if($count){
				return array_slice($this->archives[$activity], $start, $count);
			}else{
				return $this->archives[$activity];
			}
		}else{
			return array();
		}
	}
	
	protected function load()
	{
		foreach($this->element->childNodes as $child)
		{
			if($child->nodeType == XML_ELEMENT_NODE)
			{
				switch($child->nodeName)
				{
					case 'relations':
						foreach($child->childNodes as $relation)
						{
							$newRelation = new eSpectacleApiExternal($relation, $this->dom);
							foreach($newRelation->getActivities() as $activity)
							{
								switch($newRelation->getStatus()){
									case 'online':
										if(!isset($this->activities[$activity])){
											$this->activities[$activity] = array();
										}
										$this->activities[$activity][] = $newRelation;
										break;
									case 'archive':
										if(!isset($this->archives[$activity])){
											$this->archives[$activity] = array();
										}
										$this->archives[$activity][] = $newRelation;
										break;
								}
								
							}
						}
						break;
				}
			}
		}
	}
}