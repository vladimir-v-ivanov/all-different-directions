<?php

namespace Vladimir\AllDifferentDirections;

use \InvalidArgumentException;

/**
 * Used to store one instruction of a route
 */
class Instruction
{
    /**
     * The type indicates that an instruction is the first of the list
     */
    public const TYPE_START = 0x001;

    /**
     * The type indicates that an instruction has turn behavior
     */
    public const TYPE_TURN  = 0x010;

    /**
     * The type indicates that an instruction has walk behavior
     */
    public const TYPE_WALK  = 0x100;

    private $type;
    private $value;

    /**
     * Constructor
     * 
     * @param int $type type of an instruction. Must be one of the:
     *     Instruction::TYPE_START
     *     Instruction::TYPE_TURN
     *     Instruction::TYPE_WALK
     * @param float $value value of an instruction. Must be between -1000 and 1000
     * 
     * @throws InvalidArgumentException if any of passed arguments is invalid
     */
    public function __construct(int $type, float $value)
    {
        if(!in_array($type, [
            self::TYPE_START,
            self::TYPE_TURN,
            self::TYPE_WALK
        ])) {
            throw new InvalidArgumentException("Invalid instruction type passed");
        }

        if($value < -1000 || $value > 1000) {
            throw new InvalidArgumentException("Invalid value passed, value should be more than -1000 and less than 1000");
        }

        $this->type = $type;
        $this->value = $value;
    }

    /**
     * Returns the type of a current instruction
     * 
     * @return int the type of a current instruction. Type can be one of the:
     *     Instruction::TYPE_START
     *     Instruction::TYPE_TURN
     *     Instruction::TYPE_WALK
     */
    public function getType() : int
    {
        return $this->type;
    }

    /**
     * Returns the value of a current instruction
     * 
     * @return float the value of a current instruction. Value can be between -1000 and 1000
     */
    public function getValue() : float
    {
        return $this->value;
    }

    /**
     * Creates a numeric constant corresponding to text instructions
     * 
     * @param string $typeText Text instruction
     * 
     * @return float Constant instruction
     * 
     * @throws InvalidArgumentException if instruction not recognized
     */
    public static function extractTypeFromText(string $typeText) : int
    {
        if(mb_strtolower($typeText) == "start") {
            return self::TYPE_START;
        } elseif(mb_strtolower($typeText) == "turn") {
            return self::TYPE_TURN;
        } else if(mb_strtolower($typeText) == "walk") {
            return self::TYPE_WALK;
        } else {
            throw new InvalidArgumentException("Unknown instruction type \"". $typeText ."\"");
        }
    }

    /**
     * Converts fractional number from text representation to float
     * 
     * @param string $valueText Number in text representation
     * 
     * @return float Number in float representation
     * 
     * @throws InvalidArgumentException if passed text does not contain a number or contains extraneous characters
     */
    public static function extractValueFromText(string $valueText) : float
    {
        $floatValue = floatval($valueText);

        if($floatValue != $valueText) {
            throw new InvalidArgumentException("Invalid instruction value \"". $valueText ."\"");
        }

        return $floatValue;
    }
}