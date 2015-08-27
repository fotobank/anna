<?php
namespace admin\applications\Timer\Profiler;

/**
 * A simple timer trait class for adding basic timing to other classes
 *
 * To add timing to any class simply add "use TimerTrait" to the class.
 * To use the timer functionality, call $this->timer_start(), $this->timer_reset()
 * and $this->timer_elapsed(). Additionally, you can populate the $timeLimit variable
 * to take advantage of the timer_remaining() to determine how much time is left and
 * timer_has_remaining() to get a boolean if there is any time left.
 *
 * Class TimerTrait
 * @package admin\applications\Timer\Profiler
 */
trait TimerTrait
{
    /**
     * @var float The epoc time since the timers last start or reset
     */
    protected $time;

    /**
     * @var float предельное время в секундах
     */
    protected $timeLimit;

    /**
     * @param float $timeLimit
     */
    public function setTimeLimit($timeLimit)
    {
        $this->timeLimit = $timeLimit;
    }

    /**
     * Starts the timer
     */
    protected function timerStart()
    {
        $this->timerReset();
    }

    /**
     * Resets the timer
     */
    protected function timerReset()
    {
        $this->time = microtime(true);
    }

    /**
     * Returns the amount of time since the timer start or reset
     *
     * @return float The amount of time since the timers start or reset
     */
    protected function timerElapsed()
    {
        return microtime(true) - $this->time;
    }

    /**
     * If $timeLimit is set, this returns the number of remaining seconds
     *
     * @return float The number of remaining seconds
     */
    protected function timerRemaining()
    {
        return $this->timeLimit - $this->timerElapsed();
    }

    /**
     * Returns a boolean if there is any time remaining
     *
     * @return boolean True if there is any time remaining
     */
    protected function timerHasRemaining()
    {
        return ($this->timerRemaining() > 0) ? true : false;
    }
}