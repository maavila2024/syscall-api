<?php

namespace App\Enums;

enum Status: string
{
    case Ativa     = "1";
    case Inativa   = "0";

    /**
     * Descriptio os enum
     *
     * @return string
     */
    public function description(): string
    {
        return match($this) {
            static::Ativa => "true",
            static::Inativa => "false",
        };
    }
}
