<?php
class MyAPI
{
	private $pdo;
	private $entity;

	public function __construct($config = false){
		require('./Entity.php');
		require('./User.php');
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
    	}
    	catch(PDOException $e)
    	{
    		echo $e->getMessage();
    		return false;
    	}
    }

    protected function getPdo(){
    	return $this->pdo;
    }
}