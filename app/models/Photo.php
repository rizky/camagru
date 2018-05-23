<?php

class Photo
{
	public $id;
	public $user;
	public $url;
	public $likes;
	public $comments;
	public $description;
	public $comments_preview;
	public $time_elapse;
	public $createdAt;

	public function __construct(array $params = [])
	{
		array_key_exists('id', $params) ? $this->id = $params['id'] : 0;
		array_key_exists('user', $params) ? $this->user = $params['user'] : 0;
		array_key_exists('url', $params) ? $this->url = $params['url'] : 0;
		array_key_exists('likes', $params) ? $this->likes = $params['likes'] : 0;
		array_key_exists('comments', $params) ? $this->email = $params['comments'] : 0;
		array_key_exists('description', $params) ? $this->description = $params['description'] : 0;
		array_key_exists('comments_preview', $params) ? $this->comments_preview = $params['comments_preview'] : 0;
	}

	static public function find(array $params = [])
	{
		$photos = ORM::getInstance()->findAll('photo', $params, array('createdAt', 'DESC'), []);
		foreach ($photos as &$p)
		{
			$user = USER::find($p['user']);
			$p['user_name'] = $user->name;
			$p['user_id'] = $user->id;
			$p['user_username'] = $user->username;
			$p['description_v'] = ($p['description'] == NULL) ? 'hidden' : 'show';
			$p['time_elapse'] = Photo::time_elapsed_string($p['createdAt']);
		}
		return $photos;
	}

	static private function time_elapsed_string($datetime, $full = false)
	{
		$now = new DateTime;
		$ago = new DateTime($datetime);
		$diff = $now->diff($ago);
	
		$diff->w = floor($diff->d / 7);
		$diff->d -= $diff->w * 7;
	
		$string = array(
			'y' => 'year',
			'm' => 'month',
			'w' => 'week',
			'd' => 'day',
			'h' => 'hour',
			'i' => 'minute',
			's' => 'second',
		);
		foreach ($string as $k => &$v) {
			if ($diff->$k) {
				$v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
			} else {
				unset($string[$k]);
			}
		}
	
		if (!$full) $string = array_slice($string, 0, 1);
		return $string ? implode(', ', $string) . ' ago' : 'just now';
	}
}