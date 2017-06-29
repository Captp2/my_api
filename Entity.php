<?php
abstract class Entity{
	private $pdo;
	private $api;

	public function __construct($api){
		$this->api = $api;
		$this->pdo = $api->getPdo();
	}

	public function getAll(){
		$query = $this->pdo->prepare('SELECT * FROM ' . strtolower(static::class) . 's');
		$query->execute();
		$fetch = $query->fetchAll(PDO::FETCH_ASSOC);
		if($fetch){
			$this->api->response(json_encode($fetch), 200);
			return true;
		}
		else{
			$this->api->response('', 204);
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
			$this->api->response(json_encode($fetch), 200);
			return true;
		}
		else{
			$this->api->response('', 204);
			return false;
		}
	}

	public function findById($id){
		$query = $this->pdo->prepare('SELECT * FROM ' . strtolower(static::class) . 's WHERE id = ' . $id);
		$query->execute();
		$fetch = $query->fetchAll(PDO::FETCH_ASSOC);
		if($fetch){
			$this->api->response(json_encode($fetch), 200);
			return true;
		}
		else{
			$this->api->response('', 204);
			return false;
		}
	}
}