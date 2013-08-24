<?php
/*
 * This file is part of the e-Spectacle API package.
 * (c) 2011-2012 Sébastien Bernard <sebastien.bernard@e-spectacle.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * eSpectacleApiPressNewspaper offers convenience methods to handle calendar from a relation element.
 * 
 * @author	Sébastien Bernard <sebastien.bernard@e-spectacle.fr>
 */
namespace eSpectacle\eSpectacleApi;

class eSpectacleApiPressNewspaper extends eSpectacleApiElement implements eSpectacleApiServiceInterface
{
	protected $id			= '';
	protected $document		= '';
	protected $title		= '';
	protected $abstract		= '';
	protected $publishDate	= '';
	protected $author		= '';
	protected $url			= '';

	public static function getServiceName()
	{
		return 'PressNewspaper';
	}

	public static function getServiceType()
	{
		return 'Many';
	}
	
	public function getId()
	{
		$this->id;
	}
	
	public function getDocument($default = false, $template = false)
	{
		return $this->processValue($this->document, $default, $template);
	}

	public function getTitle($default = false, $template = false)
	{
		return $this->processValue($this->title, $default, $template);
	}

	public function getAbstract($default = false, $template = false)
	{
		return $this->processValue($this->abstract, $default, $template);
	}

	public function getPublishDate($format = false, $default = false, $template = false)
	{
		if(!$format){
			$publishDate = $this->publishDate;
		}else{
			$publishDate = $this->publishDate ? strftime($format, $this->publishDate->format('U')) : false;
		}
		return $this->processValue($publishDate, $default, $template);
	}

	public function getAuthor($default = false, $template = false)
	{
		return $this->processValue($this->author, $default, $template);
	}
	
	public function getUrl($default = false, $template = false)
	{
		return $this->processValue($this->url, $default, $template);
	}
	
	protected function load()
	{
		$this->id = $this->element->getAttribute('id');
		
		foreach($this->element->childNodes as $child)
		{
			if($child->nodeType == XML_ELEMENT_NODE)
			{
				switch($child->nodeName)
				{
					case 'publish-date':
						$this->publishDate = new \DateTime($child->nodeValue);
						break;
					default:
						$this->set($child->nodeName, $child->nodeValue);
				}
			}
		}
	}
}