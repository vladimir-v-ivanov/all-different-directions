<?php

namespace Vladimir\AllDifferentDirections;

use \RuntimeException;
use \DomainException;
use \InvalidArgumentException;

/**
 * Class for retrieving test cases data from file storage
 */
class InputMapperFile implements InputMapperInterface
{
    private $filePath;

    /**
     * Sets file from which test cases will be parsed
     * 
     * @param string $filePath An absolute path to a file
     * 
     * @throws InvalidArgumentException if file at given path is not readable
     */
    public function setFilePath(string $filePath)
    {
        if(!is_readable($filePath)) {
            throw new InvalidArgumentException("");
        }

        $this->filePath = $filePath;
    }

    /**
     * Generates test cases from data source
     * 
     * @return array Array with test cases
     * 
     * @throws RuntimeException if source file is not readable or error occurs during reading
     * @throws DomainException if file contains a data in invalid format
     */
    public function getTestCases() : array
    {
        $file = fopen($this->filePath, "r");

        if($file === false) {
            throw new RuntimeException("Unable to open unput file \"". $this->filePath ."\" for read");
        }

        $testCases = [];
        $f = 0;

        while(($cnt = fgets($file)) !== false) {
            $cnt = trim($cnt);

            /* Checking the first line of each test case, because each test case must starts with an integer only */
            if(!ctype_digit((string) $cnt)) {
                throw new DomainException("Test case #". ($f + 1) ." must starts with an integer");
            }

            $cnt = (int) $cnt;

            if($cnt < 0 || $cnt > 20) {
                throw new DomainException("Test case #". ($f + 1) ." integer must be more than 0 and less than 21, current value is: " . $cnt);
            }

            if($cnt == 0) {
                continue;
            }

            $routeList = [];

            for($a = 0; $a < $cnt; ++$a) {
                $instructions = fgets($file);

                /* The string should contain text in a format suitable for the route instructions. */
                if($instructions === false || !preg_match("/^[0-9\.]+[\ ]+[0-9\.]+[\ ]+[a-z]{4,5}[\ ]+/i", $instructions)) {
                    throw new DomainException("Invalid instructions line #". ($a + 1) ." in test case #" . ($f + 1));
                }

                $instructions = array_filter(explode(" ", $instructions), "strlen");
                
                if(count($instructions) % 2 != 0) {
                    throw new DomainException("Arguments quantity in the instruction line #". ($a + 1) ." must be an even number");
                }

                $instructions = array_chunk($instructions, 2);
                $initialPositon = array_shift($instructions);
                $instructions = array_map(function($value){
                    return new Instruction(Instruction::extractTypeFromText($value[0]), Instruction::extractValueFromText($value[1]));
                }, $instructions);

                $routeList[] = new Route(
                    new Coordinates($initialPositon[0], $initialPositon[1]),
                    $instructions
                );
            }

            $testCases[] = new RouteList($routeList);
            ++$f;
        }

        if(!feof($file)) {
            throw new RuntimeException("Input file \"". $this->filePath ."\" is not fully read");
        }

        fclose($file);

        return $testCases;
    }
}