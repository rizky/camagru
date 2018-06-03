<?php

class Model
{
	public $id;

	public static function getClass()
    {
        return static::class;
	}

	public static function getTableName()
    {
        return strtolower(static::class);
	}
	
	public static function findOne(array $where = [])
	{
		return ORM::getInstance()->findOne(static::getTableName(), $where);
	}

	public static function findAll(array $where = [], array $order = [], array $limit = [])
	{
		return ORM::getInstance()->findAll(static::getTableName(), $where, $order, $limit);
	}

	public static function store($value)
	{
		return ORM::getInstance()->store(static::getTableName(), $value);
	}

	public function delete()
	{
		$class = static::getClass();
		$object = $class::findOne(array('id' => $this->id));
		if ($object instanceof $class)
			return ORM::getInstance()->delete(static::getTableName(), $this->id);
		return false;
	}
}