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

use Behat\Behat\Hook\Scope\BeforeScenarioScope;

class Screenshots extends Base
{
    /**
     * Init values required for snapshots.
     *
     * @param BeforeScenarioScope $scope scenario scope
     *
     * @BeforeScenario
     */
    public function beforeScenarioInit(BeforeScenarioScope $scope): void
    {
        $screenshotsFolder = $_ENV['BEHAT_SELENIUM_SCREENSHOTS_FOLDER'];
        if (!file_exists($screenshotsFolder)) {
            mkdir($screenshotsFolder, 0777, true);
        }
        chmod($screenshotsFolder, 0777);
    }
}
