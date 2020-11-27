<?php

namespace App\Entity\Behaviour\Arrays;

class Ops
{
    public static function addItem(?array $carry, ?string $item): ?array
    {
        if (!$carry) {
            $carry = [];
        }

        $item = array_flip($carry)[$item] ? null : $item;

        if ($item) {
            $carry[] = $item;
        }

        return $carry;
    }

    public static function removeItem(?array $carry, ?string $item): ?array
    {
        if ($item) {
            $carry = array_reduce($carry, function (?array $carrier, ?string $element) use ($item) {
                if (!$carrier) {
                    $carrier = [];
                }

                if ($item !== $element) {
                    $carrier[] = $element;
                }

                return $carrier;
            }, []);
        }

        return $carry;
    }
}
