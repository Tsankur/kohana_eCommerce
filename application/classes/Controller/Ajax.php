<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Ajax extends Controller {

	private function sendBack($data)
	{
		$this->response->headers('Content-type', 'application/json');
		$this->response->body(json_encode($data));
	}
	public function action_products()
	{
		$productManager = new Model_ProductManager();
		$this->sendBack($productManager->GetProductsBy($this->request->param('sort_type'), false, I18N::lang(), $this->request->param('category_id'), $this->request->param('platform_id'), $this->request->param('page')));
	}
	public function action_getproduct()
	{
		$productManager = new Model_ProductManager();
		$this->sendBack($productManager->GetProductSort($this->request->param('id'), I18N::lang()));
	}
	public function action_addtocart()
	{
		if(!isset($_SESSION['cart']))
		{
			$_SESSION['cart'] = array();
		}
		if(!in_array($this->request->param('id'), $_SESSION['cart']))
		{
			array_push($_SESSION['cart'], $this->request->param('id'));
			$this->sendBack(1);
		}
		else
		{
			$this->sendBack(0);
		}
	}
	public function action_removefromcart()
	{
		if(isset($_SESSION['cart']))
		{
			if(in_array($this->request->param('id'), $_SESSION['cart']))
			{
				array_splice($_SESSION['cart'], array_search($this->request->param('id'), $_SESSION['cart']), 1);
				$this->sendBack(1);
			}
			else
			{
				$this->sendBack(0);
			}
		}
		else
		{
			$this->sendBack(0);
		}
	}
}