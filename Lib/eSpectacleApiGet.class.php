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

use eSpectacle\eSpectacleApi\eSpectacleApi;
use eSpectacle\eSpectacleApi\eSpectacleApiData;

class eSpectacleApiGet extends eSpectacleApi
{
	public $key;
	
	public $cacheOn			= false;	// Active the cache
	public $cachePath		= '';		// Path to the Cache directory
	public $cacheDelay		= 86400;	// Delay to the next loading
	
	public $datas;
	public $errors;
	
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
	public function removeCache($id)
	{
		$cache = $this->generateCacheFilename($id);
		if(file_exists($cache))
		{
			unlink($cache);
		}
	}

	/**
	 * Clear all cache
	 */
	public function removeAllCache()
	{
		$files = scandir($this->cachePath);
		foreach($files as $file){
			if(is_file($this->cachePath.$file)){
				unlink($this->cachePath.$file);
			}
		}
	}

	/**
	 * Get cache informations
	 */
	public function getCacheInfo()
	{
		$files = scandir($this->cachePath);
		$size = 0;
		$count = 0;
		foreach($files as $file){
			if(is_file($this->cachePath.$file)){
				$size += filesize($this->cachePath.$file);
				$count++;
			}
		}
		return array(
				'count' 	=> $count,
				'size'		=> $size
		);
	}

	/**
	 * Generate the cache filename of the current object
	 * @access protected
	 * @return string|boolean	the name of the cache filename whether the cache is activated or false if is not
	 */
	public function generateCacheFilename($id)
	{
		if($this->cacheOn)
		{
			return $this->cachePath.$id.'.xml';
		}
		return false;
	}

	/**
	 * Retrieve the date of the object
	 * @access public
	 * @return DateTime	the date of the xml file
	 */
	public function getDate()
	{
		return $this->datas->getDate();
	}
	
	/**
	 * Whether or not the current object has a not expired cache file
	 * @return boolean
	 */
	public function hasCacheFile($id)
	{
		$cache = $this->generateCacheFilename($id);
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
		$dom = new \DOMDocument();
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
    public function load($id, $force = false)
    {
    	$xml = '';
    	$this->errors = array();
    	$this->datas = null;
    	
    	if(!$force && $object = eSpectacleApiLibrary::getObject($id)){
    		return $object;
    	}elseif(!$force && ($this->cacheOn && $this->hasCacheFile($id))){
    		$xml = file_get_contents($this->generateCacheFilename($id));
    	}else{
    		$parameters = array(
    				'module'	=> 'webservice',
    				'action'	=> 'get',
    				'id'		=> $id
    		);
    		
    		$xml = $this->sendRequest($parameters);;

			if($this->hasErrors($xml))
    		{
    			return false;
    		}
    		elseif($this->cacheOn)
			{
				$cacheFilename = $this->generateCacheFilename($id);
				file_put_contents($cacheFilename, $xml);
			}
    	}
    	
    	$this->datas = new eSpectacleApiData($xml);
    	return $this->datas->load();
    }

}