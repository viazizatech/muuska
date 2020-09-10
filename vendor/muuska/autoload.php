<?php
$baseDir = dirname(__FILE__);
require_once $baseDir.'/src/muuska/util/DefaultAutoloader.php';
\muuska\util\DefaultAutoloader::registerNew('muuska', $baseDir);
