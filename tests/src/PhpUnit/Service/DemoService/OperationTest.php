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

namespace App\Tests\PhpUnit\Service\DemoService;

use App\Service\DemoService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

/**
 * @internal
 * @coversNothing
 */
final class OperationTest extends WebTestCase
{
    protected ?DemoService $demoService;

    protected function setUp(): void
    {
        self::bootKernel();
    }

    /**
     * @dataProvider provider
     *
     * @param string $testName
     */
    public function testInvoke(string $operand, float $x, float $y, ?float $expected, $testName): void
    {
        echo \PHP_EOL."App\\Service\\DemoService->operation(string '{$operand}', float {$x}, float {$y}): ?float {$expected} => {$testName} --- {$x} {$operand} {$y} = {$expected}".\PHP_EOL;
        $this->demoService = self::$container->get(DemoService::class);

        static::assertSame($expected, $this->demoService->operation($operand, $x, $y), $testName);
    }

    /**
     * format operand, x, y, expected, testName.
     */
    public function provider(): array
    {
        return [
            ['+', 1, 2, 3, 'Simple addition'],
            ['-', 1, 2, -1, 'Simple substraction'],
            ['*', 1, 2, 2, 'Simple multiplication'],
            ['/', 1, 2, 0.5, 'Simple division /'],
            ['%', 1, 2, 1, 'Simple division %'],
        ];
    }
}
