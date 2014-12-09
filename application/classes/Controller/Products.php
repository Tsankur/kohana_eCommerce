<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Products extends Controller {

	private $cart;
	public function before()
	{
		$productManager = new Model_ProductManager();
		parent::before();
		$this->cart = array();
		if(isset($_SESSION['cart']))
		{
			foreach ($_SESSION['cart'] as $id)
			{
				$this->cart[$id] = $productManager->GetProduct($id, I18N::lang());
			}
		}
	}
	public function action_index()
	{
		$productManager = new Model_ProductManager();
		$categoryManager = new Model_CategoryManager();
		$platformManager = new Model_PlatformManager();

		$header = View::Factory('header');
		$header->title = 'Accueil';
		$header->styles = array('mainPage', 'font-awesome.min');
		$header->scripts = array('products');

		$view = View::Factory('index');
		$view->cart = $this->cart;
		$view->popularProducts = $productManager->GetPopularProducts(I18N::lang());
		$view->categories = $categoryManager->GetCategories();
		$view->platforms = $platformManager->GetPlatforms();
		$this->response->body($header.$view);
	}
	public function action_product()
	{
		$productManager = new Model_ProductManager();
		$productManager->ViewProduct($this->request->param('id'));
		$header = View::Factory('header');
		$header->title = 'Product';
		$header->styles = array('mainPage', 'font-awesome.min');
		$header->scripts = array('products');

		$view = View::Factory('product');
		$view->cart = $this->cart;
		$view->platformsEquivalent = array('Mac'=>'apple', 'Windows'=>'windows', 'Android'=> 'android', 'Linux'=> 'linux');
		$view->product = $productManager->GetProduct($this->request->param('id'), I18N::lang());
		$this->response->body($header.$view);
	}

} // End Welcome
