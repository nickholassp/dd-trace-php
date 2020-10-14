<?php

namespace App;

class Controller
{
    public function action()
    {
        $service = new Service();
        if (\rand(0, 1) === 1) {
            $service->doSomethingTraced();
        } else {
            $service->doSomethingUntraced();
        }
    }
}
