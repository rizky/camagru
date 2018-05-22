<?php

class View
{
	protected $view;
	public $params;

	public function __construct($view)
	{
		$this->view = $view;
	}

	public function render()
	{
		$params = $this->params;
		require_once 'app/views/' . $this->view . '.php';
	}
}