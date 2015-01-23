<?php

require_once('../class.r0mdauDb.php');

class r0mdauDbTest extends PHPUnit_Framework_TestCase {
    public function setUp(){
        $this->db = new r0mdauDb('.');
    }

    public function testJePeuxRecupererUneCollection() {
        $collection = $this->db->collection('romain');
        $this->assertTrue($collection instanceof r0mdauCollection);
    }

    private $db;
}