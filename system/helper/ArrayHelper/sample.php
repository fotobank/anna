<?php

include_once('ArrayHelper.php');

echo "<pre>";

$a = new ArrayHelper();
$a->setProperties(array("data"=> array(1,2,3)));
print_r($a);

$a->set("data/1","test");
print_r($a);

echo $a->has("data/2");
print_r($a->fetchAll()); # not cleared return of deep nested array

print_r($a->getAll());

print_r($a);