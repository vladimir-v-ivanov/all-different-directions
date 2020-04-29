<?php

namespace Vladimir\AllDifferentDirections;

/**
 * Used to store a coordinates of one point
 */
class Coordinates
{
    private $x;
    private $y;

    /**
     * Constructor
     * 
     * @param float $x abscissa coordinate
     * @param float $y ordinate coordinate
     */
    public function __construct(float $x, float $y)
    {
        $this->x = $x;
        $this->y = $y;
    }

    /**
     * Returns abscissa coordinate
     * 
     * @return float abscissa coordinate
     */
    public function getX() : float
    {
        return $this->x;
    }

    /**
     * Returns ordinate coordinate
     * 
     * @return float ordinate coordinate
     */
    public function getY() : float
    {
        return $this->y;
    }
}