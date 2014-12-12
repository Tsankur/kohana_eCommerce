<?php
class Model_ProductManager
{
	private $db;
	private $allowedSort;
	private $productsPerPage = 6;
	function __construct()
	{
		$this->allowedSort = array('add_date', 'sells', 'views', 'name');
		$this->db = new Helper_Database('application/config/config.ini');
	}
	function GetProduct($id, $lang)
	{
		$result = $this->db->queryOne('SELECT *, descriptions.description FROM products LEFT JOIN descriptions on products.id = descriptions.product_id WHERE id = ? AND descriptions.lang = ?', array($id, $lang));
		
		$result['platforms'] = $this->db->query('SELECT platforms.name FROM platforms_relationship INNER JOIN platforms ON platforms_relationship.platform_id = platforms.id WHERE platforms_relationship.product_id = ?', array($id));
		$result['categories'] = $this->db->query('SELECT categories.name FROM categories_relationship INNER JOIN categories ON categories_relationship.category_id = categories.id WHERE categories_relationship.product_id = ?', array($id));
		return $result;
	}
	function GetProductShort($id)
	{
		$result = $this->db->queryOne('SELECT image, name, price FROM products WHERE id = ?', array($id));
		
		$result['platforms'] = $this->db->query('SELECT platforms.name FROM platforms_relationship INNER JOIN platforms ON platforms_relationship.platform_id = platforms.id WHERE platforms_relationship.product_id = ?', array($id));
		return $result;
	}
	function GetProductsBy($sortType, $desc = false, $lang = 'en-us', $category_id = 0, $platform_id = 0, $page = 0, $search ='')
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
			if(strlen($search) > 0)
			{
				if($platform_id == 0 && $category_id == 0)
				{
					$request .= ' WHERE';
				}
				else
				{
					$request .= ' AND';
				}
				$request .= ' products.name LIKE :search';
				$parameters['search'] = '%'.$search.'%';
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
	function GetProductsByFor($sortType, $user_id, $desc = false, $lang = 'en-us', $category_id = 0, $platform_id = 0, $page = 0, $search ='')
	{
		$result = array();
		if(in_array($sortType, $this->allowedSort))
		{
			$request = 'SELECT SQL_CALC_FOUND_ROWS products.* FROM products';
			$parameters = array();
			$request .= ' LEFT JOIN owned_products ON products.id = owned_products.product_id';
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
			$request .= ' WHERE owned_products.user_id = :user_id';
			$parameters['user_id'] = $user_id;
			if($platform_id > 0 || $category_id > 0)
			{
				$request .= ' AND';
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
			if(strlen($search) > 0)
			{
				$request .= ' AND products.name LIKE :search';
				$parameters['search'] = '%'.$search.'%';
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
	function GetProductsFor($user_id)
	{
		return $this->db->query('SELECT product_id FROM owned_products WHERE user_id = ?', array($user_id));	
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
	function BuyProduct($id)
	{
		return $this->db->execute('UPDATE products SET sells = sells + 1 WHERE id = ?', array($id));
	}
	function AddOwnedProduct($user_id, $product_id)
	{
		return $this->db->execute('INSERT INTO owned_products (product_id, user_id) VALUES (?, ?)', array($product_id, $user_id));
	}
}