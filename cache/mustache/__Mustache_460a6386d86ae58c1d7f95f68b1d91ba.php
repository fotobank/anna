<?php

class __Mustache_460a6386d86ae58c1d7f95f68b1d91ba extends Mustache_Template
{
    protected $strictCallables = true;
    public function renderInternal(Mustache_Context $context, $indent = '')
    {
        $buffer = '';
        $newContext = array();

        $buffer .= $indent . '<div class="onlineWidget">
';
        $buffer .= $indent . '<div class="panel">
';
        $buffer .= $indent . '<img class="preloader" src="/inc/who_is_online/img/preloader.gif" alt="Loading.." width="22" height="22" /></div>
';
        $buffer .= $indent . '<div class="count"></div><div class="label"><span>online</span></div><div class="arrow"></div></div>';

        return $buffer;
    }
}
