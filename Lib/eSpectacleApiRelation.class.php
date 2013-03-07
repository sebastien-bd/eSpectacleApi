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
namespace eSpectacle\eSpectacleApi;


class eSpectacleApiRelation extends eSpectacleApiElement
{
	protected $id				= false;
	protected $activity			= '';
	protected $status			= '';
	protected $production		= false;
	protected $organization		= false;
	protected $application		= false;
	protected $services			= array();
	
	public function getId()
	{
		return $this->id;
	}

	public function getServices($deploy = false)
	{
		if($deploy){
			$services = array();
			foreach($this->services as $name=>$value){
				if(is_array($value)){
					$services = array_merge($services, $value);
				}else{
					$services[] = $value;
				}
			}
			return $services;
		}else{
			return $this->services;
		}
		
	}

	public function getService($service)
	{
		return $this->services[$service];
	}

	public function hasServices()
	{
		return count($this->services) ? true : false;
	}
	
	public function getActivity()
	{
		return $this->activity;
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

	public function getApplication()
	{
		return $this->application;
	}
	
	public function load()
	{
		$this->id = $this->element->getAttribute('id');
		$this->version = $this->element->getAttribute('version');
		$this->date = new \DateTime($this->element->getAttribute('date'));
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

					case 'services':
						foreach($child->childNodes as $service){
							$serviceName = implode('', array_map('ucfirst', explode('-', $service->nodeName)));
							$className = 'eSpectacle\\eSpectacleApi\\eSpectacleApi'.$serviceName;
							if(class_exists($className)){
								if($className::getServiceType() == 'One'){
									$this->services[$serviceName] = new $className($service);
								}elseif($className::getServiceType() == 'Many'){
									if(!isset($this->services[$serviceName])){
										$this->services[$serviceName] = array();
									}
									$this->services[$serviceName][] = new $className($service);
								}
							}
						}
						break;
							
					case 'application':
						$this->application = new eSpectacleApiApplication($child);
						break;
								
					default:
						$this->set($child->nodeName, $child->nodeValue);
				}
			}
		}
		
	}
}