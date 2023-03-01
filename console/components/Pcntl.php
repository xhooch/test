<?php

namespace console\components;

use React\EventLoop\LoopInterface;
use React\EventLoop\TimerInterface;

class Pcntl
{
    private const DEFAULT_INTERVAL = 0.1;

    private TimerInterface|null $timer;

    private LoopInterface $loop;

    public function __construct(LoopInterface $loop, float $interval = self::DEFAULT_INTERVAL)
    {
        $this->loop = $loop;
        $this->timer = null;
        $this->start($interval);
    }

    public function start(float $interval = self::DEFAULT_INTERVAL): TimerInterface
    {
        if ($this->timer) {
            $this->stop();
        }

        return $this->timer = $this->loop->addPeriodicTimer($interval, $this);
    }

    public function stop(): void
    {
        if ($this->timer) {
            $this->loop->cancelTimer($this->timer);
        }

        $this->timer = null;
    }

    public function on(int $signo, callable $listener): void
    {
        pcntl_signal($signo, $listener);
    }

    public function __invoke()
    {
        pcntl_signal_dispatch();
    }
}
