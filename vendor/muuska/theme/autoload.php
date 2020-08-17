<?php
$baseDir = dirname(__FILE__);
require_once realpath($baseDir.'/..').'/muuska/src/muuska/util/DefaultAutoloader.php';
\muuska\util\DefaultAutoloader::registerNew('metronic', $baseDir);
?>
