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

namespace App\Tests\Behat\Suite\Demo\Context;

use App\Tests\Behat\Common\Context\Base;
use Behat\Behat\Context\SnippetAcceptingContext;
use Exception;
use Symfony\Component\HttpKernel\KernelInterface;

final class Index extends Base implements SnippetAcceptingContext
{
    protected string $environment;
    private KernelInterface $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
        $this->environment = $kernel->getEnvironment();
    }

    /**
     * @Given Kernel environment should be :environment
     */
    public function kernelEnvironmentShouldBe(string $environment): void
    {
        if ($this->environment !== $environment) {
            throw new Exception("expected kernel environment to be {$environment}, but found {$this->environment}");
        }
        echo "Autowiring works :) \n";
        echo "given environment {$environment} matches kernel environment {$this->environment} :) \n";
    }

    /**
     * @When a demo scenario sends a request to :path
     */
    public function aDemoScenarioSendsARequestTo(string $path): void
    {
        $session = $this->getSession();
        $this->visitPath($path);
        echo 'Current URL: '.$session->getCurrentUrl()."\n";
    }

    /**
     * @Then the response should be received
     */
    public function theResponseShouldBeReceived(): void
    {
        sleep(10);
        $session = $this->getSession();
        $page = $session->getPage();
        echo 'Current URL: '.$session->getCurrentUrl()."\n";
        echo "I have got the response :) \n";
    }
}
