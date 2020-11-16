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

    /**
     * @return int
     */
    public function getStartSleepMicroSeconds(): int
    {
        return $this->startSleepMicroSeconds;
    }

    /**
     * @return self
     */
    public function reset(): self
    {
        $this->sleepMicroSeconds = $this->startSleepMicroSeconds;

        return $this;
    }

    /**
     * @return self
     */
    public function sleep(): self
    {
        usleep($this->getSleepMicroSeconds());

        return $this;
    }

    /**
     * @return int
     */
    public function getSleepMicroSeconds(): int
    {
        return $this->sleepMicroSeconds;
    }

    /**
     *
     */
    public function increase(): self
    {
        $sleepMicroSeconds = (int)($this->getSleepMicroSeconds() * $this->getSpin());
        $stopSleepMicroSeconds = $this->getStopSleepMicroSeconds();

        if ($sleepMicroSeconds > $stopSleepMicroSeconds) {
            $sleepMicroSeconds = $stopSleepMicroSeconds;
        }

        $this->sleepMicroSeconds = $sleepMicroSeconds;

        return $this;
    }

    /**
     * @return float
     */
    public function getSpin(): float
    {
        return $this->spin;
    }

    /**
     * @return int
     */
    public function getStopSleepMicroSeconds(): int
    {
        return $this->stopSleepMicroSeconds;
    }
}
