<?php

namespace App\Enums;

enum Segment: string
{
    case Graos     = "1";
    case Proteina   = "2";

    /**
     * Descriptio os enum
     *
     * @return string
     */
    public function description(): string
    {
        return match($this) {
            static::Graos => "Grãos",
            static::Proteina => "Proteína",
        };
    }
}
