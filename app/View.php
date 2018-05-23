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
		$this->params['get'] = $_GET;
		$this->params['post'] = $_POST;
        new Template($this->view, $this->params);
	}
}