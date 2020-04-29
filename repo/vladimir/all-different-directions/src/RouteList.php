<?php

namespace Vladimir\AllDifferentDirections;

use \InvalidArgumentException;

/**
 * Wrapper which allows you to perform various actions with a list of routes
 */
class RouteList
{
    private $list;
    private $calculatedDestsX;
    private $calculatedDestsY;

    /**
     * Constructor
     * 
     * @throws InvalidArgumentException if any of the elements of the passed array is not instance of \Vladimir\AllDifferentDirections\Route
     */
    public function __construct(array $list)
    {
        foreach($list as $route) {
            if(!$route instanceof Route) {
                throw new InvalidArgumentException("Passed array should only contain instances of " . Route::class);
            }
        }

        $this->list = $list;
    }

    /**
     * Calculates the averaged destination from all existing routes
     * 
     * @return Coordinates|null averaged coordinates or null if the list of routes is empty
     */
    public function getAverageDestination() : ?Coordinates
    {
        if(is_null($this->calculatedDestsX)) {
            $this->calculateDestinations();
        }

        if(empty($this->calculatedDestsX)) {
            return null;
        }

        return new Coordinates(
            array_sum($this->calculatedDestsX) / count($this->calculatedDestsX),
            array_sum($this->calculatedDestsY) / count($this->calculatedDestsY)
        );
    }

    /**
     * Calculates maximum distance between reference coordinates and destination coordinates of one of the existing routes
     * 
     * @param Coordinates $referenceCoords reference coordinates relative to which the maximum distance will be calculated
     * 
     * @return float|null maximum distance between reference coordinates and destination coordinates of one of the
     *     existing routes or null if the list of routes is empty
     */
    public function getMaxDistanceCoordinates(Coordinates $referenceCoords) : ?float
    {
        if(is_null($this->calculatedDestsX)) {
            $this->calculateDestinations();
        }

        if(empty($this->calculatedDestsX)) {
            return null;
        }

        $refX = $referenceCoords->getX();
        $refY = $referenceCoords->getY();

        $cnt = count($this->calculatedDestsX);
        $maxDist = 0;

        for($f = 0; $f < $cnt; ++$f) {
            $dist = sqrt(pow($this->calculatedDestsX[$f] - $refX, 2) + pow($this->calculatedDestsY[$f] - $refY, 2));

            if($dist > $maxDist) {
                $maxDist = $dist;
            }
        }

        return $maxDist;
    }
    
    private function calculateDestinations()
    {
        $this->calculatedDestsX = [];
        $this->calculatedDestsY = [];

        foreach($this->list as $route) {
            $destCoords = $route->getDestinationCoordinates();
            $this->calculatedDestsX[] = $destCoords->getX();
            $this->calculatedDestsY[] = $destCoords->getY();
        }
    }
}