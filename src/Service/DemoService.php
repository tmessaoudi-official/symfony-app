<?php

namespace App\Service;

class DemoService
{
    public function operation(string $operand, float $x, float $y): ?float
    {
        switch ($operand) {
            case '+': {
                return $x + $y;
                break;
            }
            case '-': {
                return $x - $y;
                break;
            }
            case '*': {
                return $x * $y;
                break;
            }
            case '/': {
                return $x / $y;
                break;
            }
            case '%': {
                return $x % $y;
                break;
            }
            default: {
                return null;
            }
        }
    }
}
