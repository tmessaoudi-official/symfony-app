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

use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\RawMinkContext;

class Base extends RawMinkContext implements Context
{
}
