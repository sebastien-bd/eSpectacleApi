<?php
/*
 * This file is part of the e-Spectacle API package.
 * (c) 2011-2012 Sébastien Bernard <sebastien.bernard@e-spectacle.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * eSpectacleApiElement is a basic class for all elements.
 * 
 * @author	Sébastien Bernard <sebastien.bernard@e-spectacle.fr>
 */
//namespace eSpectacle\eSpectacleApi\eSpectacleApiElement;

abstract class eSpectacleApiElement
{
	protected $loaded 	= false;
	protected $dom		= false;
	protected $element	= false;
	
	public function __construct($element = false, $dom = false)
	{
		$this->dom = $dom;
		if($element)
		{
			$this->element = $element;
		}
		if($this->element)
		{
			$this->loadElement();
		}
	}
	
	public static function camel($str, $upper = false, $separator = '-')
	{
		$result = '';
		$words = explode($separator, $str);
		$i = 0;
		foreach($words as $word)
		{
			if($upper || (!$upper && $i))
			{
				$result .= ucfirst($word);
			}
			else 
			{
				$result .= strtolower($word);
			}
			$i++;
		}
		return $result;
	}
	
	public static function unCamel($content, $separator = '-')
	{
		$content = preg_replace('#(?<=[a-zA-Z])([A-Z])(?=[a-zA-Z])#e', "'$separator' . strtolower('$1')", $content);
 		$content = strtolower($content);
		return $content;
	}
	
	public function extract($name, $id)
	{
		$xPath = new DOMXPath($this->dom);
		$result = $xPath->query('//'.$name.'[@id='.$id.']');
		if($result->length)
		{
			$element =  $result->item(0);
			
	    	$class = 'eSpectacleApi'.ucfirst($name);
	    	if(!class_exists($class))
	    	{
	    		throw new Exception("Unparseable element ($name)");
	    	}
	    	return new $class($element, $this->dom);
		}
		else 
		{
			return false;
		}
	}
	
	public function isLoaded()
	{
		return $this->loaded;
	}
	
	public function loadElement(DOMElement $element = null)
	{
		if(!is_null($element))
		{
			$this->element = $element;
		}
		elseif(!$this->element)
		{
			throw new Exception("You must provide a DOMElement for loading.");
		}
		$this->load($this->element);
		
		$this->loaded = true;
	}
	
	public function set($name, $value)
	{
		$name = $this->camel($name);
		if(!isset($this->$name))
		{
			throw new Exception("$name is not a parameter for \"".get_class($this)."\"");
		}
		$this->$name = $value;
	}
	
	abstract protected function load();
}