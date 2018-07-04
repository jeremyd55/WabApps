<?php

Class connectDB {

    private $host = null;
    private $dbname = null;
    private $db;
    private $select;
    private $from;
    private $where;

    private $userDB = '';
    private $passDB = '';
    private $charset = 'utf8';
    private $collate = 'utf8_unicode_ci';
    private $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

    private $options = [
        PDO::ATTR_ERRMODE => PDO :: ERRMODE_EXCEPTION,
        PDO::ATTR_PERSISTENT => false,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES $charset COLLATE $collate"
    ];

    $dbh = new PDO($dsn, $userDB, $passDB, $options);

    public function __construct(?string $host = null, ?string $dbname = null)
    {
        $this->setHost($host);
        $this->setDbname($dbname);
        $this->connect();
    }

    public function __destruct()
    {
        if($this->db) {
            $this->db = null;
        }
    }

    private function setHost(?string $host = null)
    {
        if(!empty($host)) {
            $this->host = $host;
        }
        return $this;
    }

    private function setDbname(?string $dbname = null)
    {
        if(!empty($dbname)) {
            $this->dbname = $dbname;
        }
        return $this;
    }

    public function connect($dsn, $userDB, $passDB, $options)
    {
        $this->db = new PDO($dsn, $userDB, $passDB, $options);
    }

    public function select($select)
    {
        if(! empty($this->request)) {
            $this->request = null;
        }
        $this->request = 'SELECT' . $select;
        return $this;
    }

    public function insert($insert)
    {
        if(empty($this->$request)) {
            $this->request = null;
        }
        $this->request = 'INSERT INTO' . $insert;
        return $this;
    }

    public function set($set)
    {
        if(empty($this->request)) {
            $this->request . 'SET' . $set;
        }
        return $this;
    }

    public function where($where)
    {
        if(empty($this->request)) {
            $this->request . 'WHERE' . $where;
        }
        return $this;
    }

    public function from($from)
    {
        if(empty($this->request)) {
            $this->request . 'FROM' . $from;
        }
        return $this;
    }


}

$connect = new connectDB('host', 'dbname')->connect();
$connect->select([])->where('pseudo')->select();

?>