<?php

namespace App;

use Exception;

class Service
{
    private $snippets;

    public function __construct()
    {
        $this->snippets = new Snippets();
    }

    public function doSomethingUntraced()
    {
        $this->maybeSomethingHappens();
        $this->maybeRunSomeIntegrations();
        $this->maybeSomethingHappens();
        $then = \rand(0, 2);
        if (0 === $then) {
            return;
        } elseif (1 === $then) {
            $this->doSomethingUntraced1();
        } elseif (2 === $then) {
            $this->doSomethingTraced1();
        }
        $this->maybeSomethingHappens();
        $this->maybeRunSomeIntegrations();
        $this->maybeSomethingHappens();
    }

    public function doSomethingTraced()
    {
        $this->maybeSomethingHappens();
        $this->maybeRunSomeIntegrations();
        $this->maybeSomethingHappens();
        $then = \rand(0, 2);
        if (0 === $then) {
            return;
        } elseif (1 === $then) {
            $this->doSomethingUntraced1();
        } elseif (2 === $then) {
            $this->doSomethingTraced1();
        }
        $this->maybeSomethingHappens();
        $this->maybeRunSomeIntegrations();
        $this->maybeSomethingHappens();
    }

    public function doSomethingUntraced1()
    {
        $this->maybeSomethingHappens();
        $this->maybeRunSomeIntegrations();
        $this->maybeSomethingHappens();
        $then = \rand(0, 2);
        if (0 === $then) {
            return;
        } elseif (1 === $then) {
            $this->doSomethingUntraced2();
        } elseif (2 === $then) {
            $this->doSomethingTraced2();
        }
        $this->maybeSomethingHappens();
        $this->maybeRunSomeIntegrations();
        $this->maybeSomethingHappens();
    }

    public function doSomethingTraced1()
    {
        $this->maybeSomethingHappens();
        $this->maybeRunSomeIntegrations();
        $then = \rand(0, 2);
        if (0 === $then) {
            return;
        } elseif (1 === $then) {
            $this->doSomethingUntraced2();
        } elseif (2 === $then) {
            $this->doSomethingTraced2();
        }
        $this->maybeSomethingHappens();
        $this->maybeRunSomeIntegrations();
        $this->maybeSomethingHappens();
    }

    public function doSomethingUntraced2()
    {
        $this->maybeRunSomeIntegrations();
        $this->maybeSomethingHappens();
    }

    public function doSomethingTraced2()
    {
        $this->maybeSomethingHappens();
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

    private function maybeSomethingHappens()
    {
        $this->maybeEmitAWarning();
        $this->maybeEmitACaughtException();
        $this->maybeEmitAnUncaughtException();
        $this->maybeGenerateAFatal();
    }

    private function maybeEmitAWarning()
    {
        // #1021 caused by DD_TRACE_ENABLED=false + warning emitted
        if ($this->percentOfCases(5)) {
            \trigger_error("Some warning triggered", \E_USER_WARNING);
        }
    }

    private function maybeEmitACaughtException()
    {
        if ($this->percentOfCases(20)) {
            try {
                $this->alwaysThrowException('caught exception from chaos');
            } catch (Exception $e) {
            }
        }
    }

    private function maybeEmitAnUncaughtException()
    {
        // TODO: find a percentage that would let us detect spikes due to something wrong
        if ($this->percentOfCases(0)) {
            $this->alwaysThrowException('uncaught exception from chaos');
        }
    }

    private function maybeGenerateAFatal()
    {
        // TODO: find a percentage that would let us detect spikes due to something wrong
        if ($this->percentOfCases(0)) {
            $this->alwaysGenerateAFatal();
        }
    }

    private function percentOfCases($percent)
    {
        return \rand(0, 99) < $percent;
    }

    private function alwaysThrowException($message)
    {
        throw new Exception($message);
    }

    private function alwaysGenerateAFatal()
    {
        trigger_error('fatal', \E_USER_ERROR);
    }
}
