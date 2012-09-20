<?php
namespace tapeet;


require_once 'tapeet/ClassLoader.php';
use \tapeet\annotation\AnnotationProcessor;
use \tapeet\ioc\ContextUtils;


$classLoader = new ClassLoader();
spl_autoload_register(array($classLoader, 'load'), true);

$classLoader->addListener(new AnnotationProcessor());

$context = ContextUtils::getContext();
$context->add('_tapeet_core_classLoader', $classLoader);
