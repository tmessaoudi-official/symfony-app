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

namespace App\Tests\Behat\Common\Context;

use Behat\Behat\Context\SnippetAcceptingContext;

class Action extends Base implements SnippetAcceptingContext
{
    /**
     * @Given I wait for :arg1 seconds
     *
     * @param mixed $seconds
     */
    public function iWaitForSeconds($seconds): void
    {
        sleep($seconds);
    }

    /**
     * @Then I should be on page :arg1
     *
     * @param mixed $arg1
     */
    public function iShouldBeOnPage($arg1): void
    {
        sleep(3);
        echo 'Current URL: '.$this->getSession()->getCurrentUrl()."\n";
    }
}
