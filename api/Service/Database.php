<?php

namespace ggs\api\Service;

use ggs\api\Config\DbConfig;

class Database
{
	private $db;


 	public static function getInstance()
    {
        static $instance = null;
        if (null === $instance) {
            $instance = new static();
        }

        return $instance;
    }

	private function __construct()
	{
		$dbConfig = new DbConfig();
		$dbConfig = $dbConfig->dbConfig;

		$this->db = new \PDO(
			'mysql:host=localhost;dbname=' . $dbConfig['dbname'] . ';charset=utf8', 
			$dbConfig['user'], 
			$dbConfig['password']
		);
		$this->db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

	}

	public function get($table, $id, $condition = null)
	{
		if (!is_null($id) && !is_null($condition)) {
			$where = ' WHERE id = ' . $id . ' AND ' . $condition;
		} elseif (is_null($id) && !is_null($condition)) {
			$where = ' WHERE ' . $condition;
		} elseif (!is_null($id) && is_null($condition))  {
			$where = ' WHERE id = ' . $id;
		} else {
			return false;
		}

		try {
		    $sql = 'SELECT * FROM ' . $table . $where;
		    $statement = $this->db->prepare($sql);
            $statement->execute();
		    $result = $statement->fetch();

		 	return $result;	
		 	
		} catch (PDOException $pe) {
		    die("Could not connect to the database :" . $pe->getMessage());
		}
	}

	public function getAll($table, $where = null)
	{
		try {
		    $sql = 'SELECT * FROM ' . $table . ' ORDER BY date ASC';
		 
		    $statement = $this->db->prepare($sql);
            $statement->execute();
		    $result = $statement->fetchAll();

		 	return $result;	

		} catch (PDOException $pe) {
		    die("Could not connect to the database :" . $pe->getMessage());
		}
	}

	public function save($data, $table)
	{
		foreach ($data as $key => $value){
			$keys[]   = $key;
			$values[] = "'" . $value . "'";
		}

		$cloumns  = implode(',' , $keys);
		$dbValues = implode(',' , $values);

		try {
			$result = $this->db->exec(
				"INSERT INTO " . $table . "(" . $cloumns . ") VALUES(" . $dbValues . ")");
			$insertId = $this->db->lastInsertId();
		} catch(PDOException $ex) {

		}

		return $insertId;
	}

	public function update($id, $data, $table = null)
	{

	}

	public function delete($id, $table)
	{

	}
}