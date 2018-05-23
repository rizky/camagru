<?php

class Camera extends Controller
{
	protected $user;
	protected $view;

	public function __construct()
	{
		$this->user = $this->model('User');
	}
}