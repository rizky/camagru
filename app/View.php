<?php

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
		$params = $this->params;
		require_once 'app/views/' . $this->view . '.php';
	}
}