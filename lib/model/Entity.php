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
			return $fetch;
		}
		else{
			$this->api->response('', 204);
			return false;
		}
	}

	protected function insert($data){
		$query = 'INSERT INTO ' . strtolower(static::class) . 's (';
		$iterator = 1;
		foreach ($data as $key => $value) {
			$query .= $key;
			if($iterator != count($data)){
				$query .= ', ';
			}
			$iterator++;
		}
		$query .= ') VALUES (';
		$iterator = 1;
		foreach ($data as $key => $value) {
			$query .= '"' . $value . '"';
			if($iterator != count($data)){
				$query .= ', ';
			}
			$iterator++;
		}
		$query .= ')';
		$query = $this->pdo->prepare($query);
		$query->execute();
	}

	protected function update($data){
		$id = $data['id'];
		unset($data['id']);
		$query = 'UPDATE ' . strtolower(static::class) . 's SET ';
		$iterator = 1;
		foreach ($data as $key => $value) {
			$query .= $key . '="' . $value . '"';
			if($iterator != count($data)){
				$query .= ', ';
			}
			$iterator++;
		}
		$query .= ' WHERE id=' . $id;
		$query = $this->pdo->prepare($query);
		$query->execute();
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
			return $fetch;
		}
		else{
			return false;
		}
	}

	public function findById($id){
		$query = $this->pdo->prepare('SELECT * FROM ' . strtolower(static::class) . 's WHERE id = ' . $id);
		$query->execute();
		$fetch = $query->fetchAll(PDO::FETCH_ASSOC);
		if($fetch){
			return $fetch;
		}
		else{
			$this->api->response('', 204);
			return false;
		}
	}

	public function delete($id){
		$query = $this->pdo->prepare('DELETE FROM ' . strtolower(static::class) . 's WHERE id = ' . $id);
		$query->execute();
		$this->response('', 200);
	}

	protected function response($data, $code){
		$this->api->response($data, $code);
	}
}