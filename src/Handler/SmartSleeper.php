<?php

declare(strict_types=1);

namespace Extraton\TonClient\Handler;

class SmartSleeper
{
    private int $startSleepMicroSeconds;

    private int $stopSleepMicroSeconds;

    private int $sleepMicroSeconds;

    private float $spin;

    public function __construct(
        int $startSleepMicroSeconds = 5_000,
        int $stopSleepMicroSeconds = 2_500_000,
        float $spin = 2
    ) {
        $this->startSleepMicroSeconds = $startSleepMicroSeconds;
        $this->stopSleepMicroSeconds = $stopSleepMicroSeconds;
        $this->sleepMicroSeconds = $startSleepMicroSeconds;
        $this->spin = $spin;
    }

    public function getStartSleepMicroSeconds(): int
    {
        return $this->startSleepMicroSeconds;
    }

    public function getStopSleepMicroSeconds(): int
    {
        return $this->stopSleepMicroSeconds;
    }

    public function getSleepMicroSeconds(): int
    {
        return $this->sleepMicroSeconds;
    }

    public function getSpin(): float
    {
        return $this->spin;
    }

    public function increase(): void
    {
        $sleepMicroSeconds = (int)($this->getSleepMicroSeconds() * $this->getSpin());
        $stopSleepMicroSeconds = $this->getStopSleepMicroSeconds();

        if ($sleepMicroSeconds > $stopSleepMicroSeconds) {
            $sleepMicroSeconds = $stopSleepMicroSeconds;
        }

        $this->sleepMicroSeconds = $sleepMicroSeconds;
    }

    public function reset(): void
    {
        $this->sleepMicroSeconds = $this->startSleepMicroSeconds;
    }

    public function sleep(): void
    {
        usleep($this->getSleepMicroSeconds());
        $this->increase();
    }
}
