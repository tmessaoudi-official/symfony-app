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

namespace App\Tests\Behat\Common\Helper;

class FileDownload
{
    public static function clearDownloads($boolean, $path): void
    {
        if ($boolean) {
            $files = glob($path.'*'); // get all file names
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
            unset($file, $files);
        }
    }
}
