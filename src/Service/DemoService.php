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

namespace App\Service;

class DemoService
{
    public function operation(string $operand, float $x, float $y): ?float
    {
        switch ($operand) {
            case '+':
                return $x + $y;

                break;

            case '-':
                return $x - $y;

                break;

            case '*':
                return $x * $y;

                break;

            case '/':
                return $x / $y;

                break;

            case '%':
                return $x % $y;

                break;

            default:
                return null;
        }
    }
}
