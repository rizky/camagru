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
		$params['session'] = $_SESSION;
        new Template($this->view, $this->params);
	}
}