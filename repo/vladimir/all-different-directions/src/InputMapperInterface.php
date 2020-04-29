<?php

namespace Vladimir\AllDifferentDirections;

/**
 * Input mapper interface
 */
interface InputMapperInterface
{
    /**
     * Generates test cases from data source
     * 
     * @return array Test cases
     */
    public function getTestCases() : array;
}