<?php
class User extends Entity{
	private $tasks;
	public $api;
	private $task;

	public function __construct($api){
		$this->task = new Task($api);
		parent::__construct($api);
	}

	public function findById($id){
		$response['user'] = parent::findById($id);
		$tasks = $this->task->findBy('user_id', $id);
		$response['tasks'] = $tasks;
		parent::response(json_encode($response), 200);
	}

	public function getAll(){
		$users = parent::getAll();
		foreach ($users as &$user) {
			$user['tasks'] = $this->task->findBy('user_id', $user['id']);
		}
		parent::response(json_encode($users), 200);
	}

	public function addUser($data){
		$this->checkUser($data);
		parent::insert($data);
		$this->response('', 200);
	}

	public function editUser($data){
		$this->checkUser($data);
		parent::update($data);
		$this->findById($data['id']);
	}

	private function checkUser($data){
		if(empty($data['name']) || empty($data['email'])){
			parent::response(json_encode('Missing parameters'), 406);
		}

		if(strlen($data['name']) > 50 || strlen($data['name']) < 3 || strlen($data['email']) > 50 || strlen($data['email']) < 3){
			parent::response(json_encode('Bad parameters'), 406);
		}
		if(!is_string($data['name']) || !is_string($data['email'])){
			parent::response(json_encode('Bad parameters'), 406);
		}
	}
}