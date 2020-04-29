<?php

namespace Vladimir\AllDifferentDirections;

use \InvalidArgumentException;
use \LogicException;

/**
 * Used to store whole route data
 */
class Route
{
    private $initialCoordinates;
    private $instructions;

    /**
     * Constructor
     * 
     * @param Coordinates $coordinates coordinates of the beginning of a route
     * @param array $instructions instructions for calculating a route
     * 
     * @throws InvalidArgumentException if instructions array is empty or any element of the array is not instance of Vladimir\AllDifferentDirections\Instruction
     * @throws LogicException if first instruction is not type of start
     */
    public function __construct(Coordinates $coordinates, array $instructions)
    {
        $first = true;

        if(count($instructions) > 25) {
            throw new InvalidArgumentException("Instructions quantity must be less than 26");
        }

        foreach($instructions as $key => $instruction) {
            if(!$instruction instanceof Instruction) {
                throw new InvalidArgumentException("Value at position #" . $key . " must be instance of " . Instruction::class);
            }

            if($first) {
                if(!($instruction->getType() & Instruction::TYPE_START)) {
                    throw new LogicException("First instruction in set must be type of START");
                }

                $first = false;
            }
        }

        $this->initialCoordinates = $coordinates;
        $this->instructions = $instructions;
    }

    /**
     * Returns initial route coordinates with which object was created
     * 
     * @return Coordinates initial route coordinates
     */
    public function getInitialCoordinates() : Coordinates
    {
        return $this->initialCoordinates;
    }

    /**
     * Calculates final destination coordinates
     * 
     * @return Coordinates final destination coordinates
     * 
     * @throws LogicException if any instruction has invalid type
     */
    public function getDestinationCoordinates() : Coordinates
    {
        $initialCoords = $this->getInitialCoordinates();
        $x = $initialCoords->getX();
        $y = $initialCoords->getY();
        $first = true;

        foreach($this->instructions as $instruction) {
            $type = $instruction->getType();
            $value = $instruction->getValue();

            if($first) {
                $angle = $instruction->getValue();
                $first = false;
                continue;
            }

            if($type & Instruction::TYPE_TURN) {
                $angle += $value;
            } else if($type & Instruction::TYPE_WALK) {
                $x += $value * cos(deg2rad($angle));
                $y += $value * sin(deg2rad($angle));
            }
        }

        return new Coordinates($x, $y);
    }
}