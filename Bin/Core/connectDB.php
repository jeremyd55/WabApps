<?php
namespace Framework\Core;

use Framework\Core\Http\{Request, RequestInterface};

Class connectDB {

    private $host = null;
    private $dbname = null;
    private $db;
    private $request;
    private $param;
    private $dns;

    private $options = [
        PDO::ATTR_ERRMODE => PDO :: ERRMODE_EXCEPTION,
        PDO::ATTR_PERSISTENT => false,
        PDO::ATTR_EMULATE_PREPARES => false,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES $charset COLLATE $collate"
    ];

    public function __construct(?string $host = null, ?string $dbname = null)
    {
        $this->setParam(new Request());
        $this->setHost($this->param['DB_HOST']);
        $this->setDbname($this->param['DB_DATABASE']);
        $this->connect(dns(), $this->param['DB_USERNAME'], $this->param['DB_PASSWORD'], $this->$options);
    }

    public function dns()
    {
        $this->dns = $this->param['DB_CONNECTION'] . ':host=' + $this->param['DB_HOST'] . ';dbname=' . $this->param['DB_DATABASE'];
        return $this;
    }

    private function setParam(RequestInteface $request)
    {
        $this->param = $request->getEnvironment();
        return $this;
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

    private function connect($dns, $userDB, $passDB, $options)
    {
        $this->db = new PDO($dns, $userDB, $passDB, $options);
        return $this;
    }

    public function select($select)
    {
        if(! empty($this->request)) {
            $this->request = null;
        }
        $this->request = 'SELECT ' . $select;
        return $this;
    }

    public function insert($insert)
    {
        if(empty($this->$request)) {
            $this->request = null;
        }
        $this->request = 'INSERT INTO ' . $insert;
        return $this;
    }

    public function update($update)
    {
        if(empty($this->$request)) {
            $this->request = null;
        }
        $this->request = 'UPDATE ' . $update;
        return $this;
    }

    public function delete($delete)
    {
        if(empty($this->$request)) {
            $this->request = null;
        }
        $this->request = 'DELETE FROM ' . $delete;
        return $this;
    }

    public function set($set)
    {
        if(empty($this->request)) {
            $this->request . ' SET ' . $set;
        }
        return $this;
    }

    public function where($where)
    {
        if(empty($this->request)) {
            $this->request . ' WHERE ' . $where;
        }
        return $this;
    }

    public function from($from)
    {
        if(empty($this->request)) {
            $this->request . ' FROM ' . $from;
        }
        return $this;
    }

}

?>