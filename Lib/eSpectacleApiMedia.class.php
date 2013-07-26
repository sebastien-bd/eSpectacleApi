<?php
/*
 * This file is part of the e-Spectacle API package.
 * (c) 2011-2012 Sébastien Bernard <sebastien.bernard@e-spectacle.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * eSpectacleApiLocation offers convenience methods to handle localized element.
 * 
 * @author	Sébastien Bernard <sebastien.bernard@e-spectacle.fr>
 */
namespace eSpectacle\eSpectacleApi;

class eSpectacleApiMedia extends eSpectacleApiElement
{
	const TypeVideo				= 'video';
	
	protected $provider			= '';
	protected $title			= '';
	protected $slug				= '';
	protected $template			= '';
	protected $parameters		= array();
	
	public function getProvider($default = false, $template = false)
	{
		return $this->processValue($this->provider, $default, $template);
	}
	
	public function getTitle($default = false, $template = false)
	{
		return $this->processValue($this->title, $default, $template);
	}

	public function getSlug($default = false, $template = false)
	{
		return $this->processValue($this->slug, $default, $template);
	}
	
	public function getTemplate($default = false, $template = false)
	{
		return $this->processValue($this->template, $default, $template);
	}
	
	public function getParameters($default = false, $template = false)
	{
		return $this->processValue($this->parameters, $default, $template);
	}
	
	public function display($width = false, $height = false, $fit = false){
		$options = $this->parameters;
		if(!$fit){
			if(!$width && !$height){
				$width = $options['width'];
				$height = $options['height'];
			}elseif(!$width){
				$width = round($height * $options['width'] / $options['height']);
			}elseif(!$height){
				$height = round($width * $options['height'] / $options['width']);
			}else{
				$ratio = $options['width'] / $options['height'];
				$newRatio = $width / $height;
				if($ration > $newRatio){
					$height = $options['height'];
					$width = $height * $newRatio;
				}else{
					$width = $options['width'];
					$height = $width * $newRatio;
				}
			}
		}
		$options['width'] = $width;
		$options['height'] = $height;
		return $this->applyParameters($options);
	}
	
	protected function applyParameters($options)
	{
		$vars = array();
		foreach($options as $index=>$option){
			$vars['$'.$index] = $option;
		}
		return str_replace(array_keys($vars), array_values($vars), $this->template);
	}
	
	protected function load()
	{
		foreach($this->element->childNodes as $child)
		{
			if($child->nodeType == XML_ELEMENT_NODE)
			{
				switch($child->nodeName)
				{
					case 'parameters':
						foreach($child->childNodes as $param){
							$this->parameters[$param->nodeName] = $param->nodeValue;
						}
						break;
					default:
						$this->set($child->nodeName, $child->nodeValue);
				}
			}
		}
		$this->loaded = true;
	}
}