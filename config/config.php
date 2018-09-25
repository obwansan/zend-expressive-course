<?php

use Zend\Stdlib\ArrayUtils;
use Zend\Stdlib\Glob;

$config = [];


/**
 * Reads in all files in config/autoload ending in .global.php and merges
 * them to form the base configuration.
 * Then reads in all files in config/autoload ending in .local.php and merges
 * them together on top of the existing configuration.
 * Any setting you store in a .local.php file will override any existing
 * setting in a .global.php file in config/autoload. This allows us to have
 * development settings separate from production ones.
 *
 * The glob() function returns an array of filenames or directories matching a
 * specified pattern, or FALSE on failure.
 */
foreach (Glob::glob(
    'config/autoload/{{,*.}global,{,*.}local}.php',
    Glob::GLOB_BRACE) as $file
){
    $config = ArrayUtils::merge($config, include $file);
}
return new ArrayObject($config, ArrayObject::ARRAY_AS_PROPS);