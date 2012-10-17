<?php
/*
 * This file is part of the e-Spectacles API package.
 * (c) 2011-2012 Sébastien Bernard <sebastien.bernard@e-spectacle.fr>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//include_once 'eSpectacleApiData.class.php';

/**
 * eSpectaclesApiGet offers convenience methods to call the eSpectacles API (function GET) :
 * - generates HTML request
 * - parses the response
 * - manages a cache process (requires a directory with write permission)
 * - offers easy access to the XML fields
 * 
 * @package	eSpectaclesApi
 * @author	Sébastien Bernard <sebastien.bernard@e-spectacle.fr>
 */
namespace eSpectacle\eSpectacleApi;

use eSpectacle\eSpectacleApi\eSpectacleApiData;

class eSpectacleApiGet
{
	public $object;
	public $id;
	public $key;
	
	public $cacheOn		= false;	// Active the cache
	public $cachePath	= '';		// Path to the Cache directory
	public $cacheDelay	= 86400;	// Delay to the next loading
	
	public $datas;
	public $errors;
	
	/**
	 * Constructor
	 * @param string $object	e-Spectacles API key
	 * @param string $object	object's name
	 * @param integer $id		object's unique id
	 * @return void
	 */
	public function __construct($key, $object, $id)
	{
		$this->object = $object;
		$this->id = $id;
		$this->key = $key;
		$this->errors = array();
	}
	
	/**
	 * Activate cache processing
	 * @param string $path		Path to a directory with write permission
	 * @param integer $delay	Delay of cache validity
	 */
	public function activateCache($path, $delay)
	{
		if(substr($path, -1, 1) != '/')
		{
			$path .= '/';
		}
		$this->cacheOn = true;
		$this->cachePath = $path;
		$this->cacheDelay = empty($delay) ? 0 : $delay;
	}
	
	/**
	 * Clear the object's cache file
	 */
	public function clearCache()
	{
		$cache = $this->generateCacheFilename();
		if(file_exists($cache))
		{
			unlink($cache);
		}
	}
	
	/**
	 * Generate the cache filename of the current object
	 * @access protected
	 * @return string|boolean	the name of the cache filename whether the cache is activated or false if is not
	 */
	public function generateCacheFilename()
	{
		if($this->cacheOn)
		{
			return $this->cachePath.$this->object.'_'.$this->id.'.xml';
		}
		return false;
	}
	
	/**
	 * Whether or not the current object has a not expired cache file
	 * @return boolean
	 */
	public function hasCacheFile()
	{
		$cache = $this->generateCacheFilename();
		if($cache)
		{
			if(file_exists($cache))
			{
	    		if((time() - filemtime($cache)) < $this->cacheDelay)
	    		{
					return true;
	    		}
	    		else 
	    		{
	    			unlink($cache);
	    		}
			}
		}
		return false;
	}
	
	public function getErrors()
	{
		return $this->errors;
	}
	
	/**
	 * Whether or not the server sent errors
	 * @param unknown_type $xml
	 * @return boolean
	 * 
	 */
	public function hasErrors($xml)
	{
		$dom = new \DomDocument();
    	$dom->loadXml($xml, LIBXML_NOBLANKS);
    	
		$xPath = new \DOMXPath($dom);
		$errors = $xPath->query('//errors');
		if($errors->length)
		{
			foreach($errors as $error)
			{
				$this->errors[$error->getAttribute('id')] = $error->nodeValue;
			}
			return true;
		}
		else 
		{
			return false;
		}
	}
    
    /**
     * Loads the object from e-Spectacles API. 
     * When cache process is activated, checks if cache file exists and return its content if is not yet expired.
     * @param boolean $force	Bypass the cache process when $force is true
     * @return string			Returns the XML file's content as a string
     */
    public function load($force = false)
    {
    	$xml = '';
    	if($force || ($this->cacheOn && $this->hasCacheFile()))
    	{
    		$xml = file_get_contents($this->generateCacheFilename());
    	}
    	else
    	{
    		$url = 'http://sf.e-spectacles.fr/api_test.php/webservice/get?key='.$this->key.'&object='.$this->object.'&id='.$this->id;
    		print_r($url);
    		$curl = curl_init($url);
		    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

		    //TODO remove the next line with basic authentication
		    curl_setopt($curl,CURLOPT_USERPWD, "guest:123456");
		    
		    $xml = curl_exec($curl);
		    curl_close($curl);
		    
		    if(!$xml){
		    	return false;
		    }
    		
			if($this->hasErrors($xml))
    		{
    			return false;
    		}
    		elseif($this->cacheOn)
			{
				$cacheFilename = $this->generateCacheFilename();
				file_put_contents($cacheFilename, $xml);
			}
    	}
    	
    	$this->datas = new eSpectacleApiData($xml);
    	return $this->datas->load();
    }

}
