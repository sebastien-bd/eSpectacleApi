<?php
/*
 * This file is part of the e-Spectacle API package.
 * (c) 2011-2012 Sébastien Bernard <sebastien.bernard@e-spectacle.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * eSpectacleApiFile offers convenience methods to handle files.
 * 
 * @author	Sébastien Bernard <sebastien.bernard@e-spectacle.fr>
 */
namespace eSpectacle\eSpectacleApi;

class eSpectacleApiFile extends eSpectacleApiElement
{
	protected $id			= '';
	protected $date			= false;
	protected $formats		= array();
	protected $urls			= array();
	protected $title		= '';
	protected $mimeType		= '';
	protected $description	= '';
	protected $terme		= '';
	protected $copyright	= '';

	public function getId()
	{
		return $this->id;
	}
	
	public function getDate($default = false, $template = false)
	{
		return $this->processValue($this->date, $default, $template);
	}
	
	public function getFormat($formatId)
	{
		if(!isset($this->formats[$formatId])){
			return new eSpectacleApiGarbage();
		}
		return $this->formats[$formatId];
	}
	
	public function getFormats()
	{
		return $this->formats;
	}

	public function getUrl($formatId = 'original')
	{
		if(!isset($this->urls[$formatId])){
			return new eSpectacleApiGarbage();
		}
		return $this->urls[$formatId];
	}

	public function getTitle($default = false, $template = false)
	{
		return $this->processValue($this->title, $default, $template);
	}
	
	public function getMimeType($default = false, $template = false)
	{
		return $this->processValue($this->mime_type, $default, $template);
	}

	public function getDescription($default = false, $template = false)
	{
		return $this->processValue($this->description, $default, $template);
	}

	public function getTerme($default = false, $template = false)
	{
		return $this->processValue($this->terme, $default, $template);
	}

	public function getCopyright($default = false, $template = false)
	{
		return $this->processValue($this->copyright, $default, $template);
	}
	
	protected function load()
	{
		$this->id = $this->element->getAttribute('id');
		$this->date = new \DateTime($this->element->getAttribute('date'));
		
		foreach($this->element->childNodes as $child)
		{
			if($child->nodeType == XML_ELEMENT_NODE)
			{
				switch($child->nodeName)
				{
					case 'url':
						$formatId = $child->getAttribute('format');
						$this->formats[$formatId] = new eSpectacleApiFileFormat($child);
						$this->urls[$formatId] = $child->nodeValue;
						break;
					default:
						$this->set($child->nodeName, $child->nodeValue);
				}
			}
		}
	}
}