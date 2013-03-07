<?php
/**
 * eSpectacleApiServiceInterface implements methods for a Service skill
 * 
 * @author Sébastien Bernard
 *
 */
namespace eSpectacle\eSpectacleApi;

interface eSpectacleApiServiceInterface
{

	/**
	 * Returns the name of the service.
	 * 
	 * @return string
	 */
	public static function getServiceName();

	/**
	 * Returns the service's type.
	 *
	 * @return string
	 */
	public static function getServiceType();
	
}