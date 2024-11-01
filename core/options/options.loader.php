<?php

Class WPF_Options_Loader
{

	public function __construct($name, $option) 
	{
		$this->includes();
		$this->instance($name, $option);
	}

	/**
	* Instantiate core class
	*
	* @since 1.0.0
	*/
	public static function instance($name, $option)
	{
		new WPF_Portfolio_Options($name, $option);
	}

	/**
	* Include framework files
	*
	* Include coe file, sections and options
	*
	* @since 1.0.0
	*/
	public static function includes()
	{
		# Framework Core
		require_once 'options.core.php';

		# sections
		require_once 'sections/portfolio-list.php';
		require_once 'sections/portfolio-single.php';
	}
}