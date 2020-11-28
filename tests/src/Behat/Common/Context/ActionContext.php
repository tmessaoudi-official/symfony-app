<?php

namespace App\Tests\Behat\Common\Context;

use Behat\Behat\Context\SnippetAcceptingContext;

class ActionContext extends BaseContext implements SnippetAcceptingContext
{
    /**
     * @Given I wait for :arg1 seconds
     */
    public function iWaitForSeconds($seconds)
    {
        sleep($seconds);
    }

    /**
     * @Then I should be on page :arg1
     */
    public function iShouldBeOnPage($arg1)
    {
        sleep(3);
        echo "Current URL: " . $this->getSession()->getCurrentUrl() . "\n";
    }
}
