<?php

namespace App\Tests\PhpUnit\Service\DemoService;

use App\Service\DemoService;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OperationTest extends WebTestCase
{
    protected ?DemoService $demoService;

    public function setUp(): void
    {
        self::bootKernel();
    }

    /**
     * @test
     * @dataProvider provider
     *
     * @param string $operand
     * @param float $x
     * @param float $y
     * @param float|null $expected
     * @param string $testName
     */
    public function __invoke(string $operand, float $x, float $y, ?float $expected, $testName)
    {
        echo PHP_EOL . "App\Service\DemoService->operation(string '$operand', float $x, float $y): ?float $expected => $testName --- $x $operand $y = $expected" . PHP_EOL;
        $this->demoService = self::$container->get(DemoService::class);

        $this->assertEquals($expected, $this->demoService->operation($operand, $x, $y), $testName);
    }

    /**
     * format operand, x, y, expected, testName
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
