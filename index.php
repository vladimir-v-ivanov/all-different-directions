<?php

ini_set("display_errors", 1);
error_reporting(E_ALL);

require_once __DIR__ . "/vendor/autoload.php";

use \Vladimir\AllDifferentDirections\RouteList;
use \Vladimir\AllDifferentDirections\Route;
use \Vladimir\AllDifferentDirections\Instruction;
use \Vladimir\AllDifferentDirections\Coordinates;
use \Vladimir\AllDifferentDirections\InputMapperFile;

echo "<pre>";
try {
    $inputMapper = new InputMapperFile;
    $inputMapper->setFilePath(__DIR__ . "/input.txt");
    $testCases = $inputMapper->getTestCases();

    foreach($testCases as $key => $routeList) {
        $averageCoords = $routeList->getAverageDestination();
    
        echo "Test case #", ($key + 1), PHP_EOL;
        echo "Average coordinates is: X: ", round($averageCoords->getX(), 4), ", Y: ", round($averageCoords->getY(), 4), PHP_EOL;
        echo "Worst distance is: ", round($routeList->getMaxDistanceCoordinates($averageCoords), 4), str_repeat(PHP_EOL, 2);
    }
} catch(Exception $ex) {
    print_r($ex);
}
echo "</pre>";