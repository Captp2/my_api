<?php
class MyAPI extends My_Rest
{
	private $pdo;
	private $entity;
	private $user;
	private $curl;
	private $task;

	/*
	 * Create the database link
	 * Instanciate the Entity type classes
	 * Call the watcher on connection
	*/
	public function __construct($config = false){
		parent::construct();
		define('WEBROOT',str_replace('index.php','',$_SERVER['SCRIPT_NAME']));
		define('ROOT',str_replace('index.php','',$_SERVER['SCRIPT_FILENAME']));
		require('./Entity.php');
		require('./User.php');
		require('./Task.php');
		$this->dbconnect($config);
	}


	private function dbconnect($config){
		if(is_string($config)){
			$config = file_get_contents($config);	
		}
		else{
			$config = file_get_contents('./config.json');
		}
		$config = json_decode($config);
		try {
			$this->pdo = new PDO('mysql:host=' . $config->host . '; dbname=' . $config->dbname, $config->username, $config->password);
			$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    		$this->user = new User($this); //sends self instance to Entity class to share the db connexion
    		$this->task = new Task($this);
    		$this->handleRequest();
    	}
    	catch(PDOException $e)
    	{
    		echo $e->getMessage();
    		return false;
    	}
    }

    public function getPdo(){
    	return $this->pdo;
    }

    public function handleRequest(){
    	$request = explode('/',$_GET['p']);
    	switch ($request[0]) {
    		case 'users':
    		if(isset($request[1]) && is_numeric($request[1])){
    			$this->user->findById($request[1]);
    		}
    		else{
    			$this->user->getAll();
    		}
    		break;
    		case 'tasks':
    		if(isset($request[1]) && is_numeric($request[1])){
    			$this->task->findById($request[1]);
    		}
    		else{
    			$this->task->getAll();
    		}
    		break;
    		default:
    		$this->response('', 404);
    		break;
    	}
    }
}