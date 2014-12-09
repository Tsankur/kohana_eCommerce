<?php
class Model_PlatformManager
{
	private $db;
	function __construct()
	{
		$this->db = new Helper_Database('application/config/config.ini');
	}
	function GetPlatforms()
	{
		$platforms = $this->db->query('SELECT * FROM platforms ORDER BY id');
		return $platforms;
	}
	function SetPlatform($product_id, $platform_id)
	{
		return $this->db->execute('INSERT INTO platforms_relationship (product_id, platform_id) VALUES (?, ?)', array((int)$product_id, (int)$platform_id));
	}
}