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
	protected $library			= array();
	protected $activities		= array();

	public function getRelationsStatus()
	{
		return array_keys($this->activities);
	}

	public function getRelationsTypes($status = false)
	{
		$result = $this->filterRelationLayer($this->activities, $status);
		return array_keys($result);
	}

	public function getRelationsActivities($type = false)
	{
		$result = $this->filterRelationLayer($this->activities, $type);
		return array_keys($result);
	}
	
	public function getRelations($type = false, $activity = false, $status = false)
	{
		$result = $this->activities;
		if($type)
			$result = $this->filterRelationLayer($result, $type);
		if($activity)
			$result = $this->filterRelationLayer($result, $activity);
		if($status)
			$result = $this->filterRelationLayer($result, $status);
		return $result;
	}

	public function hasRelations($type = false, $activity = false, $status = false)
	{
		$result = $this->activities;
		$result = $this->filterRelationLayer($result, $type);
		$result = $this->filterRelationLayer($result, $activity);
		$result = $this->filterRelationLayer($result, $status);
		return count($result);
	}
	
	public function findRelated($id)
	{
		if($id = $this->generateId(array($this->getId(), $id))){
			if(isset($this->library[$id])){
				return $this->library[$id];
			}
		}
		return false;
	}
	
	protected function generateId($ids)
	{
		$links = array('po');
		$a = substr($ids[0], 0, 1);
		$b = substr($ids[1], 0, 1);
		if(in_array($a.$b, $links)){
			return $ids[0].'@'.$ids[1];
		}
		if(in_array($b.$a, $links)){
			return $ids[1].'@'.$ids[0];
		}
		return false;
	}
	
	protected function filterRelationLayer($level, $value = false)
	{
		$result = array();
		if(!$value){
			foreach($level as $key=>$cell){
				$result = array_merge_recursive($result, $cell);
			}
		}else{
			if(!is_array($value)){
				$value = array($value);
			}
			foreach($value as $index){
				if(isset($level[$index])){
					$result = array_merge($result, $level[$index]);
				}
			}
		}
		return $result;
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

							foreach($newRelation->getActivities(true) as $type=>$listActivities)
							{
								if(!isset($this->activities[$type])){
									$this->activities[$type] = array();
								}
								
								foreach($listActivities as $activity)
								{
									if(!isset($this->activities[$type][$activity])){
										$this->activities[$type][$activity] = array();
									}
									
									$status = $newRelation->getStatus();
									if(!isset($this->activities[$type][$activity][$status])){
										$this->activities[$type][$activity][$status] = array();
									}
									$this->activities[$type][$activity][$status][] = $newRelation;
								}
							}
							if(!isset($this->library[$newRelation->getId()])){
								$this->library[$newRelation->getId()] = $newRelation;
							}
						}
						break;
				}
			}
		}
	}
}