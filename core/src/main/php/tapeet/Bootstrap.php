<?php
namespace tapeet;

require_once 'tapeet/ClassLoader.php';
$classLoader = new \tapeet\ClassLoader();
spl_autoload_register(array($classLoader, 'load'), true);

$classLoader->addListener(new \tapeet\annotation\AnnotationProcessor());

$context = \tapeet\ioc\ContextUtils::getContext();
$context->add('_tapeet_core_classLoader', $classLoader);
