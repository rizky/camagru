<?php
require_once 'Template.php';

class View
{
	protected $view;
	public $params;

	public function __construct($view, $params)
	{
		$this->view = $view;
		$this->params = $params;
	}

	public function render()
	{
		$this->params['session'] = $_SESSION;
		if (isset($_SESSION['user']))
		{
			$this->params['session']['user'] = (array)unserialize($_SESSION['user']);
			if (strlen($this->params['session']['user']['username']) > 8)
				$this->params['session']['user']['username_cut'] = substr($this->params['session']['user']['username'], 0, 5) . '...';
			else
				$this->params['session']['user']['username_cut'] = $this->params['session']['user']['username'];
		}
		$this->params['get'] = $_GET;
		$this->params['post'] = $_POST;
		$template = new Template($this->view, $this->params);
		$template->render();
	}

	public function dump()
	{
		$this->params['session'] = $_SESSION;
		if (isset($_SESSION['user']))
			$this->params['session']['user'] = (array)unserialize($_SESSION['user']);
		$this->params['get'] = $_GET;
		$this->params['post'] = $_POST;
		$template = new Template($this->view, $this->params);
		return $template->dump();
	}
}