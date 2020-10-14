<?php

namespace App;

class Controller
{
    public function action()
    {
        $service = new Service();
        echo $service->doSomething();
    }
}
