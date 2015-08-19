<?php
/**
 * Framework Component
 */

/**
 * @namespace
 */
namespace proxy;

use translator\Translator as Instance;

/**
 * Proxy to Translator
 *
 * Example of usage
 *     use proxy\Translator;
 *
 *     echo Translator::translate('message id');
 *
 * @package  Proxy
 *
 * @method   static Instance getInstance()
 *
 * @method   static string translate($message, $text = [])
 * @see      Bluz\Translator\Translator::translate()
 *
 * @method   static string translatePlural($singular, $plural, $number, $text = [])
 * @see      Bluz\Translator\Translator::translatePlural()
 *
 */
class Translator extends AbstractProxy
{
    /**
     * Init instance
     *
     * @return Instance
     */
    protected static function initInstance()
    {
        $instance = new Instance();
        $instance->setOptions(Config::getData('translator'));
        return $instance;
    }
}
