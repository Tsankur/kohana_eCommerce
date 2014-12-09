<?php
class Model_UserManager
{
	private $db;
	function __construct()
	{
		$this->db = new Helper_Database('application/config/config.ini');
	}
	function UserExist($email)
	{
		$user = $this->db->queryOne('SELECT id FROM users WHERE email = ?', array($email));
		
		return $user;
	}
	function GetUserPassword($email)
	{
		$user = $this->db->queryOne('SELECT id, password FROM users WHERE email = ?', array($email));
		
		return $user;
	}
	function GetUser($email)
	{
		$user = $this->db->queryOne('SELECT * FROM users WHERE email = ?', array($email));
		
		return $user;
	}
	function RegisterUser($firstname, $name, $email, $phone_number, $password, $lang)
	{
		return $this->db->execute('INSERT INTO users (first_name, name, email, phone_number, password, language) VALUES (?, ?, ?, ?, ?, ?)', array($firstname, $name, $email, $phone_number, $password, $lang));
	}
	/*function GetUsers()
	{
		return $this->db->query('SELECT user_name, pseudo, email, isAdmin, user_id FROM users');
	}*/
	function ChangeLanguage($id, $lang)
	{
		return $this->db->execute('UPDATE users SET language = ? WHERE id = ?', array($lang ,$id));
	}
}