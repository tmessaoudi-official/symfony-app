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

namespace App\Tests\PhpUnit\Src\Service\DemoService;

use App\Service\DemoService;
use DivisionByZeroError;
use const PHP_EOL;
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
     */
    public function testInvoke(string $operand, float $x, float $y, ?float $expected, string $testName, ?array $options = []): void
    {
        echo PHP_EOL.DemoService::class."->operation(string '{$operand}', float {$x}, float {$y}): ?float {$expected} => {$testName} --- {$x} {$operand} {$y} = {$expected}".PHP_EOL;
        $this->demoService = self::$container->get(DemoService::class);

        if (@$options['exception']) {
            static::expectException($options['exception']);
        }
        static::assertSame($expected, $this->demoService->operation($operand, $x, $y), $testName);
    }

    /**
     * format string 'operand', float x, float y, float expected, string 'testName'.
     */
    public function provider(): array
    {
        return [
            ['+', 1, 2, 3, 'Simple addition'],
            ['-', 1, 2, -1, 'Simple substraction'],
            ['*', 1, 2, 2, 'Simple multiplication'],
            ['/', 1, 2, 0.5, 'Simple division /'],
            ['/', 1, 0, null, 'Simple division / by zero', ['exception' => DivisionByZeroError::class]],
            ['%', 1, 2, 1, 'Simple division %'],
            ['%', 1, 0, null, 'Simple division % by zero', ['exception' => DivisionByZeroError::class]],
        ];
    }
}
