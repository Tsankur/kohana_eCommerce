<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Ajax extends Controller
{
	private $sortdesc = array('add_date'=>true, 'sells'=>true, 'views'=>true, 'name'=>false);
	private function sendBack($data)
	{
		$this->response->headers('Content-type', 'application/json');
		$this->response->body(json_encode($data));
	}
	public function action_products()
	{
		$productManager = new Model_ProductManager();
		$this->sendBack($productManager->GetProductsBy($this->request->param('sort_type'), $this->sortdesc[$this->request->param('sort_type')], I18N::lang(), $this->request->param('category_id'), $this->request->param('platform_id'), $this->request->param('page')));
	}
	public function action_myproducts()
	{
		if(isset($_SESSION['name']))
		{
			$productManager = new Model_ProductManager();
			$this->sendBack($productManager->GetProductsByFor($this->request->param('sort_type'), $_SESSION['id'], $this->sortdesc[$this->request->param('sort_type')], I18N::lang(), $this->request->param('category_id'), $this->request->param('platform_id'), $this->request->param('page')));
		}
		else
		{
			$this->sendBack(0);
		}
	}
	public function action_getproduct()
	{
		$productManager = new Model_ProductManager();
		$this->sendBack($productManager->GetProductShort($this->request->param('id')));
	}
	public function action_addtocart()
	{
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
	public function action_paycart()
	{
		if(isset($_SESSION['name']))
		{
			if(count($_SESSION['cart']) > 0)
			{
				if(isset($_POST['name']) && isset($_POST['firstname']) && isset($_POST['card_type']) && isset($_POST['card_number']) && isset($_POST['cvv']) && isset($_POST['month']) && isset($_POST['year']))
				{
					$total = 0;
					$taxe = 0;
					$sells = array();
					$paypal = new Helper_Paypal('application/config/config.ini');
					$productManager = new Model_ProductManager();
					$ownedProducts = $productManager->GetProductsFor($_SESSION['id']);
					$ownedProductsreduced = array();
					foreach ($ownedProducts as $value)
					{
						array_push($ownedProductsreduced, $value['product_id']);
					}
					foreach ($_SESSION['cart'] as $product_id)
					{
						$product = $productManager->GetProductShort($product_id);
						if($product)
						{
							if(!in_array($product_id, $ownedProductsreduced))
							{
								$paypal->addItem(urlencode($product['name']), urlencode($product['name']), 1, $product['price'], ($product['price']/6));
								$total += $product['price'];
							}
							array_push($sells, $product_id);
						}
					}
					if(count($sells) > 0)
					{
						$paypal->setCard($_POST['card_type'], $_POST['card_number'], $_POST['month'], $_POST['year'], $_POST['cvv'], $_POST['firstname'], $_POST['name']);
						$paypal->setTotal(0, $total/6, $total - $total/6, $total);
						$result = array(
							"status" => "success",
							"message" => 'Félicitation'
							);
						//$result = $paypal->send();
						if($result['status'] == 'success')
						{
							foreach ($sells as $product_id)
							{
								if(!in_array($product_id, $ownedProductsreduced))
								{
									$productManager->BuyProduct($product_id);
									$productManager->AddOwnedProduct($_SESSION['id'], $product_id);
								}
								if(in_array($product_id, $_SESSION['cart']))
								{
									array_splice($_SESSION['cart'], array_search($product_id, $_SESSION['cart']), 1);
								}
							}
						}
						$result['sells'] = $sells;
						$this->sendBack($result);
					}
					else
					{
						$this->sendBack(array(
							"status" => "error",
							"error" => 'Aucun produit trouver'
							));
					}
				}
				else
				{
					$this->sendBack(array(
						"status" => "error",
						"error" => 'Champ non rempli'
						));
				}
			}
			else
			{
				$this->sendBack(array(
					"status" => "error",
					"error" => 'Panier vide'
					));
			}
		}
		else
		{
			$this->sendBack(array(
				"status" => "error",
				"error" => 'Veuillez vous connecter'
				));
		}
	}
}