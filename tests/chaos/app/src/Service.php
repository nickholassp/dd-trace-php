<?php

namespace App;

class Service
{
    private $snippets;

    public function __construct()
    {
        $this->snippets = new Snippets();
    }

    public function doSomethingUntraced()
    {
        $this->maybeRunSomeIntegrations();
        $this->maybeEmitAWarning();
        $then = \rand(0, 2);
        if (0 === $then) {
            return;
        } elseif (1 === $then) {
            $this->doSomethingUntraced1();
        } elseif (2 === $then) {
            $this->doSomethingTraced1();
        }
        $this->maybeRunSomeIntegrations();
    }

    public function doSomethingTraced()
    {
        $this->maybeRunSomeIntegrations();
        $then = \rand(0, 2);
        if (0 === $then) {
            return;
        } elseif (1 === $then) {
            $this->doSomethingUntraced1();
        } elseif (2 === $then) {
            $this->doSomethingTraced1();
        }
        $this->maybeRunSomeIntegrations();
        $this->maybeEmitAWarning();
    }

    public function doSomethingUntraced1()
    {
        $this->maybeRunSomeIntegrations();
        $then = \rand(0, 2);
        if (0 === $then) {
            return;
        } elseif (1 === $then) {
            $this->doSomethingUntraced2();
        } elseif (2 === $then) {
            $this->doSomethingTraced2();
        }
        $this->maybeRunSomeIntegrations();
        $this->maybeEmitAWarning();
    }

    public function doSomethingTraced1()
    {
        $this->maybeEmitAWarning();
        $this->maybeRunSomeIntegrations();
        $then = \rand(0, 2);
        if (0 === $then) {
            return;
        } elseif (1 === $then) {
            $this->doSomethingUntraced2();
        } elseif (2 === $then) {
            $this->doSomethingTraced2();
        }
        $this->maybeRunSomeIntegrations();
    }

    public function doSomethingUntraced2()
    {
        $this->maybeRunSomeIntegrations();
        $this->maybeEmitAWarning();
    }

    public function doSomethingTraced2()
    {
        $this->maybeEmitAWarning();
        $this->maybeRunSomeIntegrations();
    }

    private function maybeRunSomeIntegrations()
    {
        if (1 === \rand(0, 1)) {
            $this->runSomeIntegrations();
        }
    }

    private function runSomeIntegrations()
    {
        $availableIntegrations = $this->availableIntegrations();
        $availableIntegrationsNames = \array_keys($availableIntegrations);
        $numberOfIntegrationsToRun = \rand(0, \count($availableIntegrations));
        for ($integrationIndex = 0; $integrationIndex < $numberOfIntegrationsToRun; $integrationIndex++) {
            $pickAnIntegration = \rand(0, count($availableIntegrationsNames) - 1);
            $integrationName = $availableIntegrationsNames[$pickAnIntegration];
            $pickAVariant = \rand(1, $availableIntegrations[$integrationName]);

            $functionName = $integrationName . 'Variant' . $pickAVariant;
            $this->snippets->$functionName();
        }
    }

    private function availableIntegrations()
    {
        return [
            'mysqli' => 1,
            'phpredis' => 1,
        ];
    }

    private function maybeEmitAWarning()
    {
        // #1021 caused by DD_TRACE_ENABLED=true + warning emitted
        if ($this->percentOfCases(5)) {
            \trigger_error("Some warning triggered", \E_USER_WARNING);
        }
    }

    private function percentOfCases($percent)
    {
        return \rand(0, 99) < $percent;
    }
}
