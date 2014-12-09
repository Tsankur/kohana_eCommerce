<?php
class Model_CategoryManager
{
	private $db;
	function __construct()
	{
		$this->db = new Helper_Database('application/config/config.ini');
	}
	function GetCategories()
	{
		$categories = $this->db->query('SELECT * FROM categories ORDER BY id');
		return $categories;
	}
	function SetCategory($product_id, $category_id)
	{
		return $this->db->execute('INSERT INTO categories_relationship (product_id, category_id) VALUES (?, ?)', array((int)$product_id, (int)$category_id));
	}
}