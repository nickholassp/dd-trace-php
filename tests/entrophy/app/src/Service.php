<?php

namespace App;

class Service
{
    public function doSomethingUntraced()
    {
        $then = \rand(0, 2);
        if (0 === $then) {
            return;
        } elseif (1 === $then) {
            $this->doSomethingUntraced1();
        } elseif (2 === $then) {
            $this->doSomethingTraced1();
        }
    }

    public function doSomethingTraced()
    {
        $then = \rand(0, 2);
        if (0 === $then) {
            return;
        } elseif (1 === $then) {
            $this->doSomethingUntraced1();
        } elseif (2 === $then) {
            $this->doSomethingTraced1();
        }
    }

    public function doSomethingUntraced1()
    {
        $then = \rand(0, 2);
        if (0 === $then) {
            return;
        } elseif (1 === $then) {
            $this->doSomethingUntraced2();
        } elseif (2 === $then) {
            $this->doSomethingTraced2();
        }
    }

    public function doSomethingTraced1()
    {
        $then = \rand(0, 2);
        if (0 === $then) {
            return;
        } elseif (1 === $then) {
            $this->doSomethingUntraced2();
        } elseif (2 === $then) {
            $this->doSomethingTraced2();
        }
    }

    public function doSomethingUntraced2()
    {
    }

    public function doSomethingTraced2()
    {
    }

    private function nextBoolean()
    {
        return \rand(0, 1) == 1;
    }
}
