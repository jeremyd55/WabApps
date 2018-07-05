<?php
namespace Framework\Core;

use Framework\Core\Http\{Request, RequestInterface};
use PDO;

Class connectDB implements connectDBInterface {

    private $db;
    private $request;
    private $param;
    private $result;

    public function __construct(?string $host = null, ?string $dbname = null)
    {
        $this->setParam(new Request());
        $this->connect();
    }

    private function dns()
    {
        return $this->param['DB_CONNECTION'] . ':host=' . $this->param['DB_HOST'] . ';dbname=' . $this->param['DB_DATABASE'];
    }

    private function setParam(RequestInterface $request)
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

    private function connect()
    {
        try{
            $this->db = new PDO($this->dns(),$this->param['DB_USERNAME'], $this->param['DB_PASSWORD']);
        } catch(PDOExeption $e) {
            throw new \RumtimeException($e->getMessage());
        }
        
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

    public function insert($insert,$values)
    {
        if(empty($this->request)){
            $this->request = null;
        }
        $this->request = 'INSERT INTO ' . $insert . ' VALUES ' . $values;
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
        if(!empty($this->request)) {
            $this->request = $this->request . ' WHERE ' . $where;
        }
        return $this;
    }

    public function from($from)
    {
        if(!empty($this->request)) {
            $this->request = $this->request . ' FROM ' . $from;
        }
        return $this;
    }

    public function query()
    {
        $this->result = $this->db->prepare($this->request);
        $this->result->execute();
        return $this;
    }

    public function result(): bool
    {
        return $this->result;
    }

    public function print()
    {
        return $this->result->fetchAll();
    }

}

?>