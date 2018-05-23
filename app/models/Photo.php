<?php

class Photo
{
	public $id;
	public $user;
	public $url;
	public $likes;
	public $comments;
	public $comments_preview;

	public function __construct(array $params = [])
	{
		array_key_exists('id', $params) ? $this->id = $params['id'] : 0;
		array_key_exists('user', $params) ? $this->user = $params['user'] : 0;
		array_key_exists('url', $params) ? $this->url = $params['url'] : 0;
		array_key_exists('likes', $params) ? $this->likes = $params['likes'] : 0;
		array_key_exists('comments', $params) ? $this->email = $params['comments'] : 0;
		array_key_exists('comments_preview', $params) ? $this->comments_preview = $params['comments_preview'] : 0;
	}
}
