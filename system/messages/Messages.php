<?php
/**
 * Alex Framework Component
 *

 */

/**
 * @namespace
 */
namespace Messages;

use common\Options;
use proxy\Session;
use proxy\Translator;

/**
 * Realization of Flash Messages
 *
 * @package  Messages
 *
 * @author   Alex Jurii
 */
class Messages
{
    use Options;

    const TYPE_ERROR = 'error';
    const TYPE_SUCCESS = 'success';
    const TYPE_NOTICE = 'notice';

    /**
     * Stack of messages types
     * @var array
     */
    protected $types = [
        self::TYPE_ERROR,
        self::TYPE_SUCCESS,
        self::TYPE_NOTICE
    ];

    /**
     * Initialize Messages container
     * @return Messages
     */
    protected function init()
    {
        if (!$this->getMessagesStore()) {
            $this->reset();
        }
        return $this;
    }

    /**
     * Add notice
     * @api
     * @since 1.0.0 added $text
     * @param string $message
     * @param string[] $text
     * @return void
     */
    public function addNotice($message)
    {
        $this->add(self::TYPE_NOTICE, $message, array_slice(func_get_args(), 1));
    }

    /**
     * Add success
     * @api
     * @since 1.0.0 added $text
     * @param string $message
     * @param string[] $text
     * @return void
     */
    public function addSuccess($message)
    {
        $this->add(self::TYPE_SUCCESS, $message, array_slice(func_get_args(), 1));
    }

    /**
     * Add error
     * @api
     * @since 1.0.0 added $text
     * @param string $message
     * @param string[] $text
     * @return void
     */
    public function addError($message)
    {
        $this->add(self::TYPE_ERROR, $message, array_slice(func_get_args(), 1));
    }

    /**
     * Add message to container
     * @param string $type One of error, notice or success
     * @param string $message
     * @param string[] $text
     * @return void
     */
    protected function add($type, $message)
    {
        $this->init();
        $this->getMessagesStore()[$type][] = Translator::translate($message, array_slice(func_get_args(), 2));
    }

    /**
     * Pop a message
     * @param string $type
     * @return \stdClass|null
     */
    public function pop($type = null)
    {
        if (!$this->getMessagesStore()) {
            return null;
        }

        if ($type !== null) {
            $text = array_shift($this->getMessagesStore()[$type]);
            if ($text) {
                $message = new \stdClass();
                $message->text = $text;
                $message->type = $type;
                return $message;
            }
        } else {
            foreach ($this->types as $type) {
                $message = $this->pop($type);
                if ($message) {
                    return $message;
                }
            }
        }
        return null;
    }

    /**
     * Pop all messages
     * @return array
     */
    public function popAll()
    {
        if (!$this->getMessagesStore()) {
            return $this->createEmptyMessagesStore();
        }

        $messages = $this->getMessagesStore()->getArrayCopy();
        $this->reset();
        return $messages;
    }

    /**
     * Get size of messages container
     * @return integer
     */
    public function count()
    {
        $size = 0;
        if (!$store = $this->getMessagesStore()) {
            return $size;
        }
        foreach ($store as $messages) {
            $size += count($messages);
        }
        return $size;
    }

    /**
     * Reset messages
     * @return void
     */
    public function reset()
    {
        Session::set('messages/store', $this->createEmptyMessagesStore());
    }

    /**
     * Returns current messages store
     * @return \ArrayObject|null Returns null if store not exists yet
     */
    protected function getMessagesStore()
    {
        return Session::get('messages/store');
    }

    /**
     * Creates a new empty store for messages
     * @return \ArrayObject
     */
    protected function createEmptyMessagesStore()
    {
        return new \ArrayObject(
            [
                self::TYPE_ERROR => [],
                self::TYPE_SUCCESS => [],
                self::TYPE_NOTICE => []
            ]
        );
    }
}
