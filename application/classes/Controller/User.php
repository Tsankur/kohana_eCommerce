<?php defined('SYSPATH') or die('No direct script access.');

class Controller_User extends Controller {

	public function action_register()
	{
		if(isset($_SESSION['firstName']))
		{
			$this->redirect('welcome/index');
		}
		else
		{	
			if(isset($_POST['referer']))
			{
				$referer = $_POST['referer'];
			}
			else
			{
				$referer = $this->request->referrer();
			}
			try
			{
				if(isset($_POST['name']) && isset($_POST['firstName']) && isset($_POST['password']) && isset($_POST['email']) && isset($_POST['phone_number']))
				{
					if(strlen($_POST['name']) > 0 && strlen($_POST['firstName']) > 0 && strlen($_POST['email']) > 0 && strlen($_POST['password']) > 0 && strlen($_POST['phone_number']) > 0)
					{
						if(Valid::email($_POST['email']))
						{
							if(Valid::phone($_POST['phone_number']))
							{
								$userManager = new Model_UserManager();
								$user = $userManager->UserExist($_POST['email']);
								if(!$user)
								{
									$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
									$userid = $userManager->RegisterUser($_POST['firstName'], $_POST['name'], $_POST['email'], $_POST['phone_number'], $password);
									if($userid)
									{
										$_SESSION['firstName'] = $_POST['firstName'];
										$_SESSION['name'] = $_POST['name'];
										$_SESSION['id'] = $userid;
										$_SESSION['email'] = $user['email'];
										$this->redirect($referer);
									}
									else
									{
										throw new Exception("erreur lors de l'ajout en base de donnée", 1);
									}
								}
								else
								{
									throw new Exception("email déja utilisé", 1);
								}
							}
							else
							{
								throw new Exception("Erreur de format du numéro de téléphone", 1);
							}
						}
						else
						{
							throw new Exception("Erreur de format de l'Email", 1);
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
			
			$header = View::Factory('header');
			$header->referer = $referer;
			$header->title = __('Register form');
			$header->styles = array();
			$header->scripts = array();

			$view = View::Factory('user/register');
			$view->_POST = $_POST;
			$view->referer = $referer;
			if(isset($error))
			{
				$view->error = $error;
			}
			$this->response->body($header.$view);
		}
	}
	public function action_login()
	{
		if(isset($_SESSION['firstName']))
		{
			$this->redirect('welcome/index');
		}
		else
		{		
			if(isset($_POST['referer']))
			{
				$referer = $_POST['referer'];
			}
			else
			{
				$referer = $this->request->referrer();
			}		
			try
			{
				if(isset($_POST['password']) && isset($_POST['email']))
				{
					if(strlen($_POST['email']) > 0 && strlen($_POST['password']) > 0)
					{
						if(Valid::email($_POST['email']))
						{
							$userManager = new Model_UserManager();
							$user = $userManager->GetUser($_POST['email']);
							if($user)
							{
								if(password_verify($_POST['password'], $user['password']))
								{
									$_SESSION['firstName'] = $user['first_name'];
									$_SESSION['name'] = $user['name'];
									$_SESSION['id'] = $user['id'];
									$_SESSION['applang'] = $user['language'];
									$_SESSION['email'] = $user['email'];
									$this->redirect($referer);
								}
								else
								{
									throw new Exception("ou mot de passe incorrect", 1);
								}
							}
							else
							{
								throw new Exception("Email incorrect", 1);
							}
						}
						else
						{
							throw new Exception("Erreur de format de l'Email", 1);
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
			$header = View::Factory('header');
			$header->referer = $referer;
			$header->title = 'Connection';
			$header->styles = array();
			$header->scripts = array();

			$view = View::Factory('user/login');
			$view->_POST = $_POST;
			$view->referer = $referer;
			if(isset($error))
			{
				$view->error = $error;
			}
			$this->response->body($header.$view);
		}
	}
	public function action_changelanguage()
	{
		$this->redirect($this->request->referrer());
	}
	public function action_logout()
	{
		$cart = $_SESSION['cart'];
		session_destroy();
		session_start();
		$_SESSION['cart'] = $cart;
		$this->redirect($this->request->referrer());
	}

} // End Welcome
