<?php
$currentDir = dirname(__FILE__);
$frameworkDir = realpath($currentDir.'/..').'/vendor/muuska/';
require_once $frameworkDir.'src/muuska/util/DefaultAutoloader.php';
\muuska\util\DefaultAutoloader::registerNew('myapp', $currentDir);

require_once $frameworkDir.'autoload.php';