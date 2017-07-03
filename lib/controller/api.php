<?php
class MyAPI extends My_Rest
{
	private $pdo;
	private $entity;
	private $entities = array();
	private $curl;
	private $task;

	/*
	 * Create the database link
	 * Instanciate the Entity type classes
	*/
	public function __construct($config = false){
		define('WEBROOT',str_replace('index.php','',$_SERVER['SCRIPT_NAME']));
		define('ROOT',str_replace('index.php','',$_SERVER['SCRIPT_FILENAME']));
        require(ROOT . '/lib/model/Entity.php');
        $this->requireEntities();
		$this->dbconnect($config);
	}

    /*
     * Requires the entities in the model folder
     */
    private function requireEntities(){
        $entities = array(
            'User.php',
            'Task.php');
        foreach ($entities as $value) {
            require(ROOT . 'lib/model/' . $value);
            $this->entities[] = strtolower(pathinfo($value)['filename']);
        }
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
            foreach ($this->entities as $value) {
                $entityName = ucfirst($value);
                $this->$value = new $entityName($this);
            }
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
        $request = $_SERVER['REQUEST_METHOD'];
        switch ($request) {
            case 'GET':
            $this->handleGetRequest();
            break;

            case 'POST':
            $this->handlePostRequest();
            break;
            
            case 'PUT':
            $this->handlePutRequest();
            break;

            case 'DELETE':
            $this->handleDeleteRequest();
            break;

            default:
            $this->response('', 404);
            break;
        }
    }

    private function handlePostRequest(){
        $_POST = parent::cleanInputs($_POST);
        $request = explode('/',$_GET['p']);
        if($request[0] == 'users'){
            $this->user->addUser($_POST);
        }
        elseif($request[0] == 'tasks'){
            $this->task->addTask($_POST);
        }
    }

    private function handleGetRequest(){
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
            case 'task':
            if(isset($request[1]) && is_numeric($request[1])){
                $this->task->findBy('user_id', $request[1]);
            }
            else{
                $this->task->getAll();
            }
            break;
            case 'tasks':
            if(isset($request[1]) && is_numeric($request[1])){
                $results = $this->task->findBy('user_id', $request[1]);
                $this->response(json_encode($results), 200);
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

    private function handlePutRequest(){
        $request = explode('/',$_GET['p']);
        $req = file_get_contents("php://input", "r");
        parse_str($req, $data);
        if($request[0] == 'users'){
            $this->user->editUser($data);
        }
        if($request[0] == 'tasks'){
            $this->task->editTask($data);
        }
    }

    private function handleDeleteRequest(){
        $request = explode('/',$_GET['p']);
        $req = file_get_contents("php://input", "r");
        parse_str($req, $data);
        if($request[0] == 'users'){
            $this->user->delete($data['user_id']);
        }
        if($request[0] == 'tasks'){
            $this->task->delete($data['id']);
        }
    }
}