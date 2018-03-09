<?php

define('DS', DIRECTORY_SEPARATOR);

// Créer un chemin absolu
function currentPath($fileName)
{
    $path = array
    (
        __DIR__,
        $fileName
    );
    return implode(DS, $path);
}

$path = currentPath('config.ini');
var_dump($path);

if(file_exists($path))
{
    $strings = array();
    $resource = fopen($path, 'r');
    if($resource)
    {
        while (($string = fgets($resource, 4096)) !== false)
        {
            $strings[] = trim($string);
        }
        fclose($resource);
    }
    $config = array();
    foreach ($strings as $string)
    {
        $paramsExploded = explode('=', $string);
        $config[$paramsExploded[0]] = $paramsExploded[1];
    }
}

extract($config);
