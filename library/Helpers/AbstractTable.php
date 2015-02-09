<?php

namespace Helpers;

use Zend\Db\TableGateway\TableGateway;


abstract class AbstractTable
{
    protected $_tableGateway;
    protected $_model;

    public function __construct(TableGateway $tableGateway)
    {
        $this->_tableGateway = $tableGateway;
    }

    public function fetchAll()
    {
        $resultSet = $this->_tableGateway->select();
        return $resultSet;
    }

    public function get($id)
    {
        $id  = (int) $id;
        $rowset = $this->_tableGateway->select(array('id' => $id));
        $row = $rowset->current();
        if (!$row) {
            throw new \Exception("Could not find row $id");
        }
        return $row;
    }

    public function fetch($where = array())
    {
        // If a number is given as the $where argument then assume we're trying
        // to fetch based on a single primary key and try to grab that record.
        if (is_numeric($where)) {
            $where = array('id' => $where);
        }

        if (is_object($where)) {
            $results = $this->_tableGateway->selectWith($where);
        } else {
            $results = $this->_tableGateway->select($where);
        }

        if ($results->count() === 0) {
            return false;
        }

        return $results;
    }

    abstract public function save($object);

    public function delete($id)
    {
        $this->_tableGateway->delete(array('id' => (int) $id));
    }
}