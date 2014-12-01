<?php
class Helper_Config
{
	private $tab;
	function __construct($file)
	{
		if(file_exists($file))
		{
			$this->tab = parse_ini_file($file, true);
			//var_dump($this->tab);
		}
		else
		{
			error_log('File "'.$file.'" could not be loaded');
		}
	}
	function get($key, $section = null)
	{
		if($section == null)
		{
			if(array_key_exists($key, $this->tab))
			{
				return $this->tab[$key];
			}
			else
			{
				error_log('Key "'.$key.'" not found');
				return null;
			}
		}
		else
		{
			if(array_key_exists($section, $this->tab))
			{
				if(array_key_exists($key, $this->tab[$section]))
				{
					return $this->tab[$section][$key];
				}
				else
				{
					error_log('Key "'.$key.'" not found in section '.$section);
					return null;
				}
			}
			else
			{
				error_log('Section "'.$section.'" not found');
				return null;
			}
		}
	}
}