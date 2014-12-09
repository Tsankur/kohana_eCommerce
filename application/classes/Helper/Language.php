<?php defined('SYSPATH') or die('No direct script access.');

class Helper_Language
{
	private static $supported_languages = array('fr'=>'fr-fr', 'en'=>'en-us');

	public static function detect()
	{
		session_start();
		if(isset($_GET['lang']))
		{
			$lang = $_GET['lang'];
			if(isset($_SESSION['name']))
			{
				$userManager = new Model_UserManager();
				$userManager->ChangeLanguage($_SESSION['id'], $lang);
				$_SESSION['applang'] = $lang;
			}
		}
		else if(isset($_SESSION['applang']))
		{
			$lang = $_SESSION['applang'];
		}
		else if(Cookie::get('applang'))
		{
			$lang = Cookie::get('applang');
		}
		else if(isset($_SERVER['HTTP_ACCEPT_LANGUAGE']))
		{
			$language = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'],0,2);
			
			if(array_key_exists($language, self::$supported_languages))
			{
				$lang = self::$supported_languages[$language];
			}
			else
			{
				$lang = i18n::lang();
			}
		}
		else
		{
			$lang = i18n::lang();
		}
		if(!in_array($lang, self::$supported_languages))
		{
			$lang = i18n::lang();
		}
		Cookie::set('applang',$lang);
		return $lang;
	}
}