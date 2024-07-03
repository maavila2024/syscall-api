<?php

namespace App\Enums;

enum TaskType: string
{
    case Incidente  = "1";
    case Melhoria   = "2";

    /**
     * Descriptio os enum
     *
     * @return string
     */
    public function description(): string
    {
        return match($this) {
            static::Incidente => "Incidente",
            static::Melhoria => "Melhoria",
        };
    }
}
