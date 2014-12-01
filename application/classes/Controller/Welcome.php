<?php defined('SYSPATH') or die('No direct script access.');

class Controller_Welcome extends Controller {

	public function action_index()
	{
		session_start();
		$header = View::Factory('header');
		$header->title = 'Acceuil';

		$view = View::Factory('index');
		$this->response->body($header.$view);
	}

} // End Welcome
