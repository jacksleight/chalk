<?php
class Ayre extends \Coast\App
{
	public function isDebug()
	{
		return (bool) $this->env('DEBUG');
	}

	public function isDevelopment()
	{
		return $this->env('SERVER') == 'development';
	}	

	public function isStaging()
	{
		return $this->env('SERVER') == 'staging';
	}	

	public function isProduction()
	{
		return $this->env('SERVER') == 'production';
	}	
}