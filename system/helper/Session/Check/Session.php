<?php
namespace helper\Session\Check;

use helper\ArrayHelper\ArrayHelper;


/**
 * Class Session
 */
class Session extends ArrayHelper
{
    /**
     * конструктор
     */
    public function __construct()
    {
        if(null !==$_SESSION)
        {
            $this->properties = &$_SESSION;
        }

    }
}