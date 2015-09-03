<?php
/**
 * Framework Component
 */

/**
 * @namespace
 */
namespace proxy;

use messages\Messages as Instance;

/**
 * Proxy to Messages
 *
 * Example of usage
 *     use proxy\Messages;
 *
 *     Messages::addSuccess('All Ok!');
 *
 * @package  Proxy
 *
 * @method   static Instance getInstance()
 *
 * @method   static Messages addNotice($message, $text = [])
 * @see      messages\Messages::addNotice()
 *
 * @method   static Messages addSuccess($message, $text = [])
 * @see      messages\Messages::addSuccess()
 *
 * @method   static Messages addError($message, $text = [])
 * @see      messages\Messages::addError()
 *
 * @method   static integer count()
 * @see      messages\Messages::count()
 *
 * @method   static \stdClass pop($type = null)
 * @see      messages\Messages::pop()
 *
 * @method   static \ArrayObject popAll()
 * @see      Messages\Messages::popAll()
 *
 * @method   static void reset()
 * @see      Messages\Messages::reset()
 *
 * @author   Alex Jurii
 */
class Messages extends AbstractProxy
{
    /**
     * Init instance
     *
     * @return Instance
     */
    protected static function initInstance()
    {
        $instance = new Instance();
        $instance->setOptions(Di::get('messages'));
        return $instance;
    }
}
