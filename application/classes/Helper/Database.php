<?php
class Helper_Database
{
	private $db;
	function __construct($configFile)
	{
		$config = new Helper_Config($configFile);
		$dbname = $config->get('dbname','database');
		$user = $config->get('user','database');
		$password = $config->get('password','database');
		if($dbname && $user && $password)
		{
			$this->db = new PDO('mysql:host=localhost;dbname='.$dbname, $user, $password);
			$this->db->exec("SET NAMES UTF8");
		}
		else
		{
			error_log('Something is wrong with the dbname the user or the password');
		}
	}
	function query($query, $value = array())
	{
		$query = $this->db->prepare($query);
		$query->execute($value);
		return $query->fetchAll(PDO::FETCH_ASSOC);
	}
	function queryOne($query, $value = array())
	{
		$query = $this->db->prepare($query.' LIMIT 1');
		$query->execute($value);
		return $query->fetch(PDO::FETCH_ASSOC);
	}
	function execute($query, $value = array())
	{
		$query = $this->db->prepare($query);
		$query->execute($value);
		return $this->db->lastInsertId();
	}
}