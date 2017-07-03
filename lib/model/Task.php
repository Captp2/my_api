<?php
class Task extends Entity{
	private $id;
	private $name;
	private $email;
	private $table_name;
	private $user;

	public function findByUserId($id){
		$response['tasks'] = $this->findBy('user_id', $id);
		parent::response(json_encode($response), 200);
	}

	public function addTask($data){
		$this->checkTask($data);
		parent::insert($data);
		$this->response('', 200);
	}

	private function checkTask($data){
		if(empty($data['title']) || empty($data['description']) || empty($data['status'])){
			parent::response(json_encode('Missing parameters'), 406);
		}

		if(strlen($data['title']) > 70 || strlen($data['title']) < 3 || strlen($data['description']) > 200 || strlen($data['description']) < 3){
			parent::response(json_encode('Bad parameters'), 406);
		}
		if(!is_string($data['title']) || !is_string($data['description'])){
			parent::response(json_encode('Bad parameters'), 406);
		}
	}

	public function editTask($data){
		$this->checkTask($data);
		parent::update($data);
		$this->findById($data['id']);
	}
}