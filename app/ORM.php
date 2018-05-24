<?php

class ORM
{
	private $PDOInstance = null;
	private static $instance = null;
	private $sqlDB;

	private function __construct()
	{
		try {
			require_once('config/database.php');
			$this->sqlDB = $DB_BASE;
			$this->PDOInstance = new \PDO($DB_DSN, $DB_USER, $DB_PASSWORD);
			$this->PDOInstance->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
		} catch (PDOException $e) {
			echo 'Connection failed: ' . $e->getMessage();
		}
	}

	public static function getInstance()
	{
		if (is_null(self::$instance)) {
			self::$instance = new ORM();
		}
		return self::$instance;
	}

	public function findOne($table, $where)
	{
		$req = 'SELECT * FROM '.$this->sqlDB.'.'.$table.' WHERE deleted = false';
		foreach ($where as $k => $v)
			$req .= " AND " . $k . " = :" . $k;
		$statement = $this->PDOInstance->prepare($req);
		foreach ($where as $k => $v)
			$statement->bindValue(':' . $k, $v);
		$statement->setFetchMode(\PDO::FETCH_CLASS, ucfirst($table));
		$statement->execute();
		return $statement->fetch(\PDO::FETCH_CLASS);
	}

	public function findAll($table, $where, $order = null, $limit = null)
	{
		$req = 'SELECT * FROM '.$this->sqlDB.'.'.$table.' WHERE deleted = false';
		foreach ($where as $k => $v)
			$req .= " AND " . $k . " = :" . $k;
		if (!empty($order))
			$req .= " ORDER BY ".$order[0]." ".$order[1];
		if (!empty($limit))
			$req .= " LIMIT ".$limit[0].",".$limit[1];
		$statement = $this->PDOInstance->prepare($req);
		foreach ($where as $k => $v)
			$statement->bindValue(':' . $k, $v);
		$statement->execute();
		return $statement->fetchAll(\PDO::FETCH_ASSOC);
	}

	private function getFields($table)
	{
		$statement = $this->PDOInstance->prepare("SELECT column_name FROM information_schema.columns WHERE table_schema = :base AND table_name = :table");
		$statement->bindValue(':table', $table);
		$statement->bindValue(':base', $this->sqlDB);
		$statement->execute();
		return ($statement->fetchAll(\PDO::FETCH_COLUMN));
	}

	private function insert($table, $fields, $value)
	{
		$req_field = '';
		$req_value = '';
		unset($value['id']);
		foreach ($value as $k => $v)
		{
			if (in_array($k, $fields))
			{
				$req_field .= '`'.$k.'`, ';
				$req_value .= ':'.$k.', ';
			}
		}
		$req = 'INSERT INTO '.$this->sqlDB.'.'.$table.' ('.rtrim($req_field, ', ').') VALUES ('.rtrim($req_value, ', ').')';
		$statement = $this->PDOInstance->prepare($req);
		foreach ($value as $k => $v)
		{
			if (in_array($k, $fields))
			{
				$statement->bindValue(':' . $k, $v);
			}
		}
		try{
			$statement->execute();
		} catch(\Exception $e) {
			echo "<pre>";
			echo $req;
			print_r($value);
			echo $e->getMessage();
			exit();
		}
		return ($this->PDOInstance->lastInsertId());
	}

	private function update($table, $fields, $value)
	{
		$req_field = '';
		foreach ($value as $k => $v)
		{
			if (in_array($k, $fields))
			{
				$req_field .= '`'.$k.'`=:'.$k.', ';
			}
		}
		$req = 'UPDATE '.$this->sqlDB.'.'.$table.' SET '.rtrim($req_field, ', ').' WHERE id = :id';
		$statement = $this->PDOInstance->prepare($req);
		foreach ($value as $k => $v)
		{
			if (in_array($k, $fields))
			{
				$statement->bindValue(':' . $k, $v);
			}
		}
		$statement->bindValue(':id', $value['id']);
		$statement->execute();
		return (true);
	}

	public function count($table, $where){
		$req = 'SELECT count(*) FROM '.$this->sqlDB.'.'.$table.' WHERE deleted = false';
		foreach ($where as $k => $v)
			$req .= " AND " . $k . " = :" . $k;
		$statement = $this->PDOInstance->prepare($req);
		foreach ($where as $k => $v)
			$statement->bindValue(':' . $k, $v);
		$statement->execute();
		$tmp = $statement->fetch();
		return $tmp[0];
	}

	public function store($table, $value)
	{
		$fields = $this->getFields($table);
		if ($value['id'] == NULL)
			return ($this->insert($table, $fields, $value));
		else
			return ($this->update($table, $fields, $value));
	}

	public function delete_s($table, $id)
	{
		$req = 'UPDATE '.$this->sqlDB.'.'.$table.' SET deleted = true WHERE id = :id';
		$statement = $this->PDOInstance->prepare($req);
		$statement->bindValue(':id', $id);
		$statement->execute();
	}

	public function delete($table, $id)
	{
		$req = 'DELETE FROM '.$this->sqlDB.'.'.$table.' WHERE id = :id';
		$statement = $this->PDOInstance->prepare($req);
		$statement->bindValue(':id', $id);
		$statement->execute();
	}
}