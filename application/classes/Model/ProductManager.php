<?php
class Model_ProductManager
{
	private $db;
	private $allowedSort;
	private $productsPerPage = 20;
	function __construct()
	{
		$this->allowedSort = array('add_date', 'sells', 'views', 'name');
		$this->db = new Helper_Database('application/config/config.ini');
	}
	function GetProduct($id, $lang)
	{
		$result = $this->db->queryOne('SELECT *, descriptions.description FROM products LEFT JOIN descriptions on products.id = descriptions.product_id WHERE id = ? AND descriptions.lang = ?', array($id, $lang));
		
		$result['platforms'] = $this->db->query('SELECT platforms.name FROM platforms_relationship INNER JOIN platforms ON platforms_relationship.platform_id = platforms.id WHERE platforms_relationship.product_id = ?', array($id));
		return $result;
	}
	function GetProductSort($id)
	{
		$result = $this->db->queryOne('SELECT image, name, price FROM products WHERE id = ?', array($id));
		
		$result['platforms'] = $this->db->query('SELECT platforms.name FROM platforms_relationship INNER JOIN platforms ON platforms_relationship.platform_id = platforms.id WHERE platforms_relationship.product_id = ?', array($id));
		return $result;
	}
	function GetPopularProducts($lang='en-us')
	{
		$products = $this->db->query('SELECT * FROM products LEFT JOIN descriptions on products.id = descriptions.product_id WHERE descriptions.lang = :lang AND add_date > (CURRENT_TIMESTAMP - 3600*24*30) ORDER BY add_date LIMIT 5', array(':lang'=>$lang));
		
		return $products;
	}
	function GetProductsBy($sortType, $desc = false, $lang = 'en-us', $category_id = 0, $platform_id = 0, $page = 0)
	{
		$result = array();
		if(in_array($sortType, $this->allowedSort))
		{
			$request = 'SELECT SQL_CALC_FOUND_ROWS products.* FROM products';
			$parameters = array();
			if($category_id > 0)
			{
				$request .= ' LEFT JOIN categories_relationship ON categories_relationship.product_id = products.id';
				$parameters['category_id'] = $category_id;
			}
			if($platform_id > 0)
			{
				$request .= ' LEFT JOIN platforms_relationship ON platforms_relationship.product_id = products.id';
				$parameters['plateform_id'] = $platform_id;
			}
			if($platform_id > 0 || $category_id > 0)
			{
				$request .= ' WHERE';
			}
			if($category_id > 0)
			{
				$request .= ' categories_relationship.category_id = :category_id ';
			}
			if($category_id > 0 && $platform_id > 0)
			{
				$request .= ' AND';
			}
			if($platform_id > 0)
			{
				$request .= ' platforms_relationship.platform_id = :plateform_id';
			}
			$request .= ' ORDER BY '.$sortType;
			if($desc)
			{
				$request .= ' DESC';
			}
			$request .= ' LIMIT '.((int)$page * $this->productsPerPage).', '.$this->productsPerPage;
			$result['products'] = $this->db->query($request, $parameters);
			$result['pageCount'] = $this->db->queryOne('SELECT FOUND_ROWS() AS productCount')['productCount']/$this->productsPerPage;
			foreach ($result['products'] as &$value)
			{
				$value['platforms'] = $this->db->query('SELECT platforms.name FROM platforms_relationship INNER JOIN platforms ON platforms_relationship.platform_id = platforms.id WHERE platforms_relationship.product_id = ?', array($value['id']));
			}
			return $result;
		}
		return null;	
	}
	function AddProduct($name, $price, $image)
	{
		return $this->db->execute('INSERT INTO products (name, price, image) VALUES (?, ?, ?)', array($name, $price, $image));
	}
	function AddDescription($product_id, $lang, $description)
	{
		return $this->db->execute('INSERT INTO descriptions (product_id, lang, description) VALUES (?, ?, ?)', array($product_id, $lang, $description));
	}
	function ViewProduct($id)
	{
		return $this->db->execute('UPDATE products SET views = views + 1 WHERE id = ?', array($id));
	}
}