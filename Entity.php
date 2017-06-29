<?php
abstract class Entity{
	private $pdo;

	public function __construct($api){
		$this->pdo = $api->pdo;
	}

	public function getAll(){
		$query = 'SELECT * FROM' . strtolower(static::class) . 's';
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