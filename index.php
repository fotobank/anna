<?php
/**
 * @created   by PhpStorm
 * @package   index.php
 * @version   1.0
 * @author    Alex Jurii <jurii@mail.ru>
 * @link      http://alex.od.ua
 * @copyright Авторские права (C) 2000-2015, Alex Jurii
 * @date      :     26.05.2015
 * @time      :     0:52
 * @license   MIT License: http://opensource.org/licenses/MIT
 */

use core\Autoloader;
use application\Application;


ob_start();

/** @noinspection PhpIncludeInspection */
include(__DIR__ . DIRECTORY_SEPARATOR . 'system' . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'primary_config.php');

// подключаем ядро сайта
/** @noinspection PhpIncludeInspection */
include(SITE_PATH . 'system' . DS . 'core' . DS . 'core.php');


Application::getInstance()->init();

class DI
{
 protected $storage = [];

 /**
  * @param $key
  * @param $value
     */
 public function __set($key, $value)
 {
  $this->storage[$key] = $value;
 }

 /**
  * @param $key
  *
  * @return mixed
     */
    public function __get($key)
 {
  return $this->storage[$key]($this);
 }
}

interface IAuthor {
//общие методы
}

class Author implements IAuthor {
 private $firstName;
 private $lastName;

 public function __construct($firstName, $lastName) {
  $this->firstName = $firstName;
  $this->lastName = $lastName;
 }

 public function getFirstName() {
  return $this->firstName;
 }

 public function getLastName() {
  return $this->lastName;
 }
}

class Question {
 private $author;
 private $question;

 public function __construct($question, $di) {
  $this->author = $di->author;
  $this->question = $question;
 }

 public function getAuthor() {
  return $this->author;
 }

 public function getQuestion() {
  return $this->question;
 }
}

$di  = new DI();
$di->author = function() { return new Author('John', 'Brown'); };
$question = new Question('What time is it?', $di);
echo $question->getAuthor()->getFirstName();


//use proxy\Profiler;
//Profiler::setIterataions(1);
//Profiler::testClass('proxy\Recursive',[ [], 'scanDir', ['system/',['php']]]);

// статичесский класс
//Profiler::testClass('proxy\Recursive',[ [], 'dir', ['files/portfolio/03_Банкеты']]);
// use Proxy;
//Profiler::testClass('proxy\Session',[[], 'has', ['logget']]);
//Profiler::testClass('proxy\Session',[[], 'set', ['test1/test2/test3', 'rrr']]);
//Profiler::testFunction('itter',[]);
//Profiler::generateResults();

ob_end_flush();