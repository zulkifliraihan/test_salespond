<?php

namespace App\Enums;

enum StatusCallLogEnum: string
{
    case Completed = 'completed';
    case Missed = 'missed';

    public function label(): string
    {
        return match ($this) {
            self::Completed => 'Completed',
            self::Missed => 'Missed',
        };
    }

    public static function getStatuses(): array
    {
        return [
            self::Completed->value => [
                'value' => self::Completed->value,
                'label' => self::Completed->label(),
            ],
            self::Missed->value => [
                'value' => self::Missed->value,
                'label' => self::Missed->label(),
            ],
        ];
    }

    public static function random(): self
    {
        $randomKey = array_rand(self::getStatuses());
        
        return self::from($randomKey);
    }
}
