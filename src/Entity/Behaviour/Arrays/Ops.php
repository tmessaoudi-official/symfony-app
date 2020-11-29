<?php

/*
 * Personal project using Php 8/Symfony 5.2.x@dev.
 *
 * @author       : Takieddine Messaoudi <takieddine.messaoudi.official@gmail.com>
 * @organization : Smart Companion
 * @contact      : takieddine.messaoudi.official@gmail.com
 *
 */

declare(strict_types=1);

namespace App\Entity\Behaviour\Arrays;

class Ops
{
    public static function addItem(?array $carry, ?string $item): ?array
    {
        if (!$carry) {
            $carry = [];
        }

        $item = !empty(array_flip($carry)[$item]) ? null : $item;

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
