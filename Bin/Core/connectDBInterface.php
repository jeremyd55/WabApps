<?php

namespace Framework\Core;


interface connectDBInterface
{
    public function __construct();

    public function select($select);

    public function update($update);

    public function delete($delete);

    public function insert($insert, $values);

    public function set($set);

    public function where($where);

    public function from($from);

    public function query();

    public function result();

    public function print();
}