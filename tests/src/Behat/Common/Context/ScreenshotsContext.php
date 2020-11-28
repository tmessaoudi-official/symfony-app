<?php

namespace App\Tests\Behat\Common\Context;

use Behat\Behat\Hook\Scope\BeforeScenarioScope;

class ScreenshotsContext
{
    /**
     * Init values required for snapshots.
     *
     * @param BeforeScenarioScope $scope scenario scope
     *
     * @BeforeScenario
     */
    public function beforeScenarioInit(BeforeScenarioScope $scope)
    {
        $screenshotsFolder = $_ENV['BEHAT_SELENIUM_SCREENSHOTS_FOLDER'];
        if (!file_exists($screenshotsFolder)) {
            mkdir($screenshotsFolder, 0777, true);
        }
        chmod($screenshotsFolder, 0777);
        /*if ($scope->getScenario()->hasTag('javascript')) {
            $driver = $this->getSession()->getDriver();
            if ($driver instanceof Selenium2Driver) {
                // Start driver's session manually if it is not already started.
                if (!$driver->isStarted()) {
                    $driver->start();
                }
                $this->getSession()->resizeWindow(1440, 900, 'current');
            }
        }*/
    }
}
