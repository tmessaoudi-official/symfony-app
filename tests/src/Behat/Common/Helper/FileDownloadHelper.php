<?php

namespace App\Tests\Behat\Common\Helper;

class FileDownloadHelper
{
    public static function clearDownloads($boolean, $path)
    {
        if ($boolean) {
            $files = glob($path.'*'); // get all file names
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
            unset($file);
            unset($files);
        }
    }
}
