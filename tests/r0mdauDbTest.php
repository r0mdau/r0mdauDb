<?php

namespace r0mdau;

class r0mdauDbTest extends \PHPUnit_Framework_TestCase
{
    public function setUp()
    {
        $this->db = new r0mdauDb('data');
    }

    public function testGetDbIsr0mdauDb()
    {
        $this->assertTrue($this->db instanceof r0mdauDb);
    }

    public function testGetCollectionIsr0mdauCollection()
    {
        $collection = $this->db->collection('test.db');

        $this->assertTrue($collection instanceof r0mdauCollection);
    }

    private $db;
}