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

namespace Bex\Behat\ScreenshotExtension\Service;

use Behat\Gherkin\Node\FeatureNode;
use Behat\Gherkin\Node\ScenarioInterface;
use DateTime;

class FilenameGenerator
{
    protected string $basePath;

    public function __construct(string $basePath)
    {
        $this->basePath = $basePath;
    }

    /**
     * @return string
     */
    public function generateFileName(FeatureNode $featureNode, ScenarioInterface $scenarioNode)
    {
        $feature = $this->relativizePaths($featureNode->getFile());
        $line = $scenarioNode->getLine();
        $fileName = implode('_', [$feature, $line]);

        return preg_replace('/[^A-Za-z0-9\-\\\.]/', '_', $fileName).(new DateTime())->format('--Y_m_d_H_i_s').'.png';
    }

    /**
     * Transforms path to relative.
     *
     * @return string
     */
    protected function relativizePaths(string $path)
    {
        if (!$this->basePath) {
            return $path;
        }

        return str_replace($this->basePath.\DIRECTORY_SEPARATOR, '', $path);
    }
}
