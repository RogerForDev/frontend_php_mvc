<?php

class Connection
{
    private $dbhost = DB_HOST;
    private $dbname = DB_NAME;
    private $dbuser = DB_USER;
    private $dbpass = DB_PASS;
    private $dbport = DB_PORT;
    
    /**
     * @var Singleton The reference to *Singleton* instance of this class
     */
    private static $instance;
    
    /* @var $conn PDO */
    private $conn = NULL;
    
    /**
     * Returns Connection Instance
     * 
     * @staticvar Connection $instance single instance.
     * @return \static Connection
     */
    public static function getInstance()
    {
        
        if (null === self::$instance) {
            self::$instance = new Connection();
        }
        
        return self::$instance;
    }
    
    protected function __construct() 
    {
    }
    
    /**
     * Avoid someone cloning the class.
     * 
     * @return void;
     */
    private function __clone()
    {   
    }
    
    /**
     * prevents method to be (de)serialized.
     * 
     * @return void;
     */
    private function __wakeup()
    {
    }
    

    public function getConnection()
    {
        if (is_null($this->conn)) {
            try {
                $dsn = 'mysql:host=' . $this->dbhost . ';dbname=' . $this->dbname .';port='.$this->dbport;
                $this->conn = new PDO($dsn, $this->dbuser, $this->dbpass, array(
                    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC));
            } catch (PDOException $e) {
                //print_r($e->getMessage()."<hr>");
                die("Error to connect in database");
            }
        }
        
        return $this->conn;
    }

}
