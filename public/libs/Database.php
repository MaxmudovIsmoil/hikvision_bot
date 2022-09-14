<?php

namespace libs;

class Database
{
    protected static $connection;
    
	private $server;

    private $user;

    private $password;

    private $database;

    
    public function __construct($server, $user, $password, $database)
    {
		$this->server = $server;

		$this->user = $user;

		$this->password = $password;

		$this->database = $database;

    }

	/**
     * Connect to the database
     * 
     * @return bool false on failure / mysqli MySQLi object instance on success
     */
    public function connect() 
    {    
    
        // Try and connect to the database
        if(!isset(self::$connection)) 
        {
            // Load configuration as an array. Use the actual location of your configuration file
            self::$connection = mysqli_connect($this->server, $this->user, $this->password, $this->database);
        }

        // If connection was not successful, handle the error
        if(self::$connection === false) 
        {
            // Handle error - notify administrator, log to a file, show an error screen, etc.
            return false;
        }

        self::$connection->set_charset("utf8");            

        return self::$connection;
    }

    /**
     * Query the database
     *
     * @param $query The query string
     * @return mixed The result of the mysqli::query() function
     */
    public function query($query) 
    {

        // Connect to the database
        $connection = $this -> connect();

        // Query the database
        $result = $connection -> query($query);

        return $result;
    }

    public function insert($query) 
    {

        // Connect to the database
        $connection = $this -> connect();

        // Query the database
        $result = $connection -> query($query);

        return $connection->insert_id;
    }

    /**
     * Fetch row from the database (SELECT query)
     *
     * @param $query The query string
     * @return bool False on failure / row Database row on success
     */
    public function selectOne($query) 
    {
    
        $result = $this -> query($query);
    
        if($result === false) return false;

        $row = $result -> fetch_assoc(); 
        
        return $row;
    }


    /**
     * Fetch rows from the database (SELECT query)
     *
     * @param $query The query string
     * @return bool False on failure / array Database rows on success
     */
    public function select($query) 
    {
    
        $rows = array();
    
        $result = $this -> query($query);
    
        if($result === false) return false;

        while ($row = $result -> fetch_assoc()) 
        {
        
            $rows[] = $row;
        
        }
        
        return $rows;
    }

    /**
     * Fetch rows from the database (SELECT query)
     *
     * @param $query The query string
     * @return bool False on failure / array Database rows on success
     */
    public function getTasks($time)
    {
      $rows = array();
      $month = date('m');

      $sql = "SELECT u.id, u.full_name, u.chat_id, u.rule,
              t.id as task_id, t.`name`, ut.remind_time, ut.month, ut.year, ut.day_off1, ut.day_off2 
              FROM `user_tasks` ut
              LEFT JOIN users u ON u.id = ut.user_id
              LEFT JOIN `tasks` t ON t.id = ut.task_id
              WHERE '" . $month . "' = ut.month 
                AND '" . date('Y') . "' = ut.year 
                AND '" . $time . "' = STR_TO_DATE(ut.remind_time, '%H:%i')";

        $result = $this -> query($sql);
    
        if($result === false) return false;

        While ($row = $result -> fetch_assoc()) 
        {
          $rows[] = $row;
        }
        
        return $rows;
    }


    /**
     * Fetch the last error from the database
     * 
     * @return string Database error message
     */
    public function error() 
    {
        $connection = $this -> connect();
    
        return $connection -> error;
    }



    public function __destruct()
    {
    	// $this->db->close();
    }

}


