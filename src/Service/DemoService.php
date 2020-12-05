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

use DivisionByZeroError;

class DemoService
{
    public function operation(string $operand, float $x, float $y): ?float
    {
        switch ($operand) {
            case '+':
                return $x + $y;

            case '-':
                return $x - $y;

            case '*':
                return $x * $y;

            case '/':
            case '%':
                if (0 === $y) {
                    throw new DivisionByZeroError();
                }

                return '/' === $operand ? $x / $y : $x % $y;

            default:
                return null;
        }
    }
}
