<?php
abstract class Entity{
	private $pdo;

	public function __construct($api){
		$this->pdo = $api->getPdo();
	}

	public function getAll(){
		$query = $this->pdo->prepare('SELECT * FROM ' . strtolower(static::class) . 's');
		$query->execute();
		$fetch = $query->fetchAll(PDO::FETCH_ASSOC);
		if($fetch){
			echo json_encode($fetch);
			return true;
		}
		else{
			return false;
		}
	}

	public function findBy($key, $value){
		if(is_numeric($value)){
			$query = $this->pdo->prepare('SELECT * FROM ' . strtolower(static::class) . 's WHERE ' . $key . ' = ' . $value);
		}
		else{
			$query = $this->pdo->prepare('SELECT * FROM ' . strtolower(static::class) . 's WHERE ' . $key . ' = "' . $value . '"');
		}
		$query->execute();
		$fetch = $query->fetchAll(PDO::FETCH_ASSOC);
		if($fetch){
			echo json_encode($fetch);
			return true;
		}
		else{
			return false;
		}
	}
}