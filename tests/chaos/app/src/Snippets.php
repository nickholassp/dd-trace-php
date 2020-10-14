<?php

namespace App;

class Snippets
{
    public function mysqliVariant1()
    {
        $mysqli = \mysqli_connect('mysql', 'test', 'test', 'test');
        $mysqli->query('SELECT 1');
        $mysqli->close();
    }

    public function phpredisVariant1()
    {
        $redis = new \Redis();
        $redis->connect('redis', 6379);
        $redis->flushAll();
        $redis->set('k1', 'v1');
        $redis->get('k1');
    }
}
