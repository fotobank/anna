<?php
namespace admin\applications\Timer\Profiler;


/**
 * This is a simple example class to demonstrate the functionality of
 * the TimerTrait class.
 *
 * @author Jacob Fogg
 */
class example
{
    use TimerTrait;

    /**
     *
     */
    public function __construct()
    {
        echo 'Starting the timer and setting a limit of 5 seconds.<br>';
        $this->timerStart();
        $this->setTimeLimit(5);

        echo 'Sleeping for 3 seconds<br>';
        sleep(3);

        echo $this->timerElapsed() . ' seconds have elapsed.<br>';
        echo 'There ' . ($this->timerHasRemaining() ? 'is' : 'is not') . ' time left on the timer.<br>';
        echo $this->timerRemaining() . ' seconds are remaining on the timer.<br>';

        echo 'Sleeping for 3 more seconds<br>';
        sleep(3);

        echo $this->timerElapsed() . ' seconds have elapsed.<br>';
        echo 'There ' . ($this->timerHasRemaining() ? 'is' : 'is not') . ' time left on the timer.<br>';
        echo $this->timerRemaining() . ' seconds are remaining on the timer.<br>';

        echo 'Resetting the timer.<br>';
        $this->timerReset();

        echo $this->timerElapsed() . ' seconds have elapsed.<br>';
        echo 'There ' . ($this->timerHasRemaining() ? 'is' : 'is not') . ' time left on the timer.<br>';
        echo $this->timerRemaining() . ' seconds are remaining on the timer.<br>';
    }
}