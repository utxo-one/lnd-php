<?php

namespace UtxoOne\LndPhp\Traits;

trait EnumHelper
{
    public static function names(): array
    {
        return array_column(self::cases(), 'name');
    }

    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    public static function array(): array
    {
        return array_combine(self::names(), self::values());
    }

    public static function fromValue(string $value): self
    {
        foreach (self::cases() as $case) {
            if($value === $case->value ){
                return $case;
            }
        }
        throw new \ValueError("$value is not a valid backing value for enum " . self::class );
    }

    public static function fromName(string $name): self
    {
        foreach (self::cases() as $case) {
            if($name === $case->name ){
                return $case;
            }
        }
        throw new \ValueError("$name is not a valid name for enum " . self::class );
    }
}