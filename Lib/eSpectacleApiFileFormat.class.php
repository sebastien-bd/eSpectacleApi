<?php
/*
 * This file is part of the e-Spectacle API package.
 * (c) 2011-2012 Sébastien Bernard <sebastien.bernard@e-spectacle.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * eSpectacleApiFileFormat offers convenience methods to handle files formats.
 * 
 * @author	Sébastien Bernard <sebastien.bernard@e-spectacle.fr>
 */
namespace eSpectacle\eSpectacleApi;

class eSpectacleApiFileFormat extends eSpectacleApiElement
{
	protected $id			= '';
	protected $datas		= array();
	protected $file			= null;

	public function getId()
	{
		return $this->id;
	}

	public function getValue($value, $default = false, $template = false)
	{
		$value = isset($this->datas[$value]) ? $this->datas[$value] : false;
		return $this->processValue($value, $default, $template);
	}
	
	public function getWidth($max = false)
	{
		if(!$max){
			return $this->getValue('width');
		}else{
			if(max($this->getWidth(), $this->getHeight()) == $this->getWidth()){
				return $max;
			}else{
				return $this->getWidth() * $max / $this->getHeight();
			}
		}
	}
	
	public function getHeight($max = false)
	{
	if(!$max){
			return $this->getValue('height');
		}else{
			if(max($this->getWidth(), $this->getHeight()) == $this->getHeight()){
				return $max;
			}else{
				return $this->getHeight() * $max / $this->getWidth();
			}
		}
	}
	
	protected function load()
	{
		$this->id = $this->element->getAttribute('format');
		if($this->element->hasAttributes()){
			foreach($this->element->attributes as $attribute)
			{
				$this->datas[$attribute->nodeName] = $attribute->nodeValue;
			}
		}
	}
}