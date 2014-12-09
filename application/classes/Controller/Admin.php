<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Admin extends Controller {

	public function action_index()
	{
		if(!isset($_SESSION['isAdmin']))
		{
			$this->redirect('admin/login');
		}
		else
		{
			$productManager = new Model_ProductManager();
			$categoryManager = new Model_CategoryManager();
			$platformManager = new Model_PlatformManager();
			$categories = $categoryManager->GetCategories();
			$platforms = $platformManager->GetPlatforms();
			try
			{
				if(isset($_POST['name']) && isset($_POST['price']) && isset($_POST['frdesc']) && isset($_POST['endesc']))
				{
					if(isset($_FILES['image']))
					{
						$file = $_FILES['image'];
						if($file['error'] == 0)
						{
							$allowedType = array('image/png', 'image/jpeg', 'image/jpg', 'image/gif');
							$formats = array('.png', '.jpeg', '.jpg', '.gif');
							$formatId = array_search($file['type'], $allowedType);
							if($formatId !== false && $formatId < count($formats))
							{
								if(strlen($_POST['name']) > 0 && ((float)$_POST['price']) > 0)
								{
									$addCategories = array();
									$addPlatforms = array();
									foreach ($categories as $category)
									{
										if(isset($_POST['cat'.$category['id']]))
										{
											array_push($addCategories, $category['id']);
										}
									}
									foreach ($platforms as $platform)
									{
										if(isset($_POST['plat'.$platform['id']]))
										{
											array_push($addPlatforms, $platform['id']);
										}
									}
									$fileName = uniqid().$formats[$formatId];
									
									$id = $productManager->addProduct($_POST['name'], $_POST['price'], $fileName);
									
									if($id)
									{
										move_uploaded_file($file['tmp_name'], 'assets/images/products/'.$fileName);
										$productManager->addDescription($id, 'fr-fr', $_POST['frdesc']);
										$productManager->addDescription($id, 'en-us', $_POST['endesc']);
										foreach ($addCategories as $category)
										{
											$categoryManager->SetCategory($id, $category);
										}
										foreach ($addPlatforms as $platform)
										{
											$platformManager->SetPlatform($id, $platform);
										}
									}
									else
									{
										throw new Exception("Erreur d'ajout en base de donné.", 1);
									}
								}
								else
								{
									throw new Exception("Champ(s) non rempli.", 1);
								}
							}
							else
							{
								throw new Exception("Format d'image non supporté.", 1);
							}
						}
						else
						{
							throw new Exception("Erreur d'upload de l'image.", 1);
						}
					}
					else
					{
						throw new Exception("Veuillez choisir une image.", 1);
					}
				}
			}
			catch(Exception $e)
			{
				$error = $e->getMessage();
			}
			$view = View::Factory('admin/admin');
			$view->categories = $categories;
			$view->platforms = $platforms;
			if(isset($error))
			{
				$view->error = $error;
			}
			$this->response->body($view);
		}
	}
	public function action_login()
	{
		if(isset($_SESSION['isAdmin']))
		{
			$this->redirect('admin/index');
		}
		else
		{
			try
			{
				if(isset($_POST['password']) && isset($_POST['login']))
				{
					if(strlen($_POST['login']) > 0 && strlen($_POST['password']) > 0)
					{
						if($_POST['login'] == 'admin' && password_verify($_POST['password'], password_hash('admin', PASSWORD_DEFAULT)))
						{
							$_SESSION['isAdmin'] = true;
							$this->redirect('admin/index');
						}
						else
						{
							throw new Exception("login ou mot de passe incorrect", 1);
						}
					}
					else
					{
						throw new Exception("Champ(s) non rempli", 1);
					}
				}
			}
			catch(HTTP_Exception_Redirect $e)
			{
				throw $e;
			}
			catch(Exception $e)
			{
				$error = $e->getMessage();
			}

			$view = View::Factory('admin/login');
			$view->_POST = $_POST;
			if(isset($error))
			{
				$view->error = $error;
			}
			$this->response->body($view);
		}
	}
	public function action_logout()
	{
		session_destroy();
		$this->redirect('admin/login');
	}
}