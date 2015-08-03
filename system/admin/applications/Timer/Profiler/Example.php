<?php

use proxy\Profiler;

//Instantiate a profiler object
echo "Start the profiler.<br>\n";
$profiler = new Profiler(1000);

//Profiler a function
$profiler->testFunction('rand', [0, 9999]);

//profile a namespaced class
Profiler::testClass('core\Autoloader', [[], 'rScanDir', [$directory]]);

//profile a method
$profiler->testMethod(new mysqli(), 'query', ['select count(*) from users;']);

//print out the results
Profiler::printResults();
//или
Profiler::generateResults();


//====================================================
// use proxy\Profiler;
$directory = 'system/admin';

Profiler::setIterataions(1);
Profiler::testClass('core\Autoloader', [[], 'rScanDir', [$directory]]);

$dir = new helper\Recursive();
Profiler::testMethod($dir, 'dir', [$directory]);

Profiler::testClass('helper\Recursive', [[], 'dir', [$directory]]);

// статичесский класс
Profiler::testClass('proxy\Recursive', [[], 'dir', [$directory]]);


Profiler::generateResults();

