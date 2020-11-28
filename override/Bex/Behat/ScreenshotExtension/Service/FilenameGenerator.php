<?php

namespace Bex\Behat\ScreenshotExtension\Service;

use Behat\Gherkin\Node\FeatureNode;
use Behat\Gherkin\Node\ScenarioInterface;
use DateTime;

class FilenameGenerator
{
    /**
     * @var string
     */
    protected string $basePath;

    public function __construct(string $basePath)
    {
        $this->basePath = $basePath;
    }

    /**
     * @param  FeatureNode  $featureNode
     * @param  ScenarioInterface $scenarioNode
     *
     * @return string
     */
    public function generateFileName(FeatureNode $featureNode, ScenarioInterface $scenarioNode)
    {
        $feature = $this->relativizePaths($featureNode->getFile());
        $line = $scenarioNode->getLine();
        $fileName = join('_', [$feature, $line]);
        return preg_replace('/[^A-Za-z0-9\-]/', '_', mb_strtolower($fileName)) . (new DateTime())->format('_Y_m_d_H_i_s') . '.png';
    }

    /**
     * Transforms path to relative.
     *
     * @param string $path
     *
     * @return string
     */
    protected function relativizePaths(string $path)
    {
        if (!$this->basePath) {
            return $path;
        }

        return str_replace($this->basePath . DIRECTORY_SEPARATOR, '', $path);
    }
}
