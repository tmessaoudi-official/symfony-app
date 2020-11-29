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

use App\Tests\Behat\Common\Helper\FileDownloadHelper;
use Behat\Behat\Context\SnippetAcceptingContext;
use Behat\Mink\Exception\ElementNotFoundException;
use Exception;

class FileDownload extends Base implements SnippetAcceptingContext
{
    /**
     * @var string
     */
    private $downloadFolder;

    /** @BeforeScenario */
    public function prepareDownloadFolder(): void
    {
        $this->downloadFolder = $_ENV['BEHAT_SELENIUM_DOWNLOAD_FOLDER'];
        if (!file_exists($this->downloadFolder)) {
            mkdir($this->downloadFolder, 0777, true);
        }
        chmod($this->downloadFolder, 0777);
    }

    /**
     * @throws ElementNotFoundException
     * @throws Exception
     * @When I click the :type :selector i should have :expected downloaded, options clearBefore= :clearBefore clearAfter= :clearAfter waitFor= :waitFor
     */
    public function iShouldDownloadFile(string $type, string $selector, string $expected, string $clearBefore = 'true', string $clearAfter = 'true', string $waitFor = '10'): void
    {
        FileDownloadHelper::clearDownloads('true' === $clearBefore, $this->downloadFolder);

        $session = $this->getSession();
        $page = $session->getPage();

        switch ($type) {
            case 'link':
                $page->clickLink($selector);

                break;

            case 'button':
                $page->pressButton($selector);

                break;

            default:
                throw new Exception('unknown type '.$type);

                break;
        }
        sleep((int) $waitFor);
        $dir = scandir($this->downloadFolder);
        if (preg_match('/'.$dir[1].'/', $expected)) {
            echo 'file '.$expected.' has been downloaded';
        } else {
            throw new Exception('File '.$expected.' not downloaded');
        }
    }
}
