<?php

namespace Jawira\PhingVisualizer;

use XSLTProcessor;
use function dirname;
use function Jawira\PlantUml\encodep;

/**
 * Class Diagram
 *
 * @package Jawira\PhingVisualizer
 */
class Diagram
{
    public const BUILDFILE_DEFAULT = 'build.xml';
    public const FORMAT_EPS        = 'eps';
    public const FORMAT_PNG        = 'png';
    public const FORMAT_PUML       = 'puml';
    public const FORMAT_SVG        = 'svg';

    protected const XSL_STYLE   = __DIR__ . '/../resources/xslt/style.xsl';
    protected const XSL_TARGETS = __DIR__ . '/../resources/xslt/targets.xsl';
    protected const XSL_CALLS   = __DIR__ . '/../resources/xslt/calls.xsl';
    protected const URL         = 'http://www.plantuml.com/plantuml/%s/%s';

    /**
     * @var string
     */
    protected $buildfile;


    /**
     * Diagram constructor.
     *
     * @param string $buildfile Path where buildfile is located
     *
     * @throws \Jawira\PhingVisualizer\DiagramException
     */
    public function __construct(string $buildfile)
    {
        $this->setBuildfile($buildfile);
    }


    /**
     * @return string
     */
    protected function getBuildfile(): string
    {
        return $this->buildfile;
    }


    /**
     * Load buildfile location
     *
     * @param string $buildfile
     *
     * @return $this
     * @throws \Jawira\PhingVisualizer\DiagramException
     */
    protected function setBuildfile(string $buildfile): self
    {
        // Buildfile must exist
        if (!is_file($buildfile)) {
            throw new DiagramException(sprintf('File "%s" is invalid.', $buildfile));
        }

        $this->buildfile = $buildfile;

        return $this;
    }

    /**
     * Load PlantUML diagram output location
     *
     * @param string      $format
     * @param null|string $output
     *
     * @return string
     * @throws \Jawira\PhingVisualizer\DiagramException
     */
    protected function validateOutputLocation(string $format, ?string $output): string
    {
        $buildfileInfo = pathinfo($this->getBuildfile());

        // Fallback
        if (empty($output)) {
            $output = $buildfileInfo['dirname'];
        }

        // Adding filename if necessary
        if (is_dir($output)) {
            $output .= DIRECTORY_SEPARATOR . $buildfileInfo['filename'] . '.' . $format;
        }

        // Check if path is available
        if (!is_dir(dirname($output))) {
            throw new DiagramException(sprintf('Dir "%s" is invalid.', $output));
        }

        return $output;
    }

    /**
     * Saves image on disk
     *
     * @param string      $format
     * @param null|string $output
     *
     * @throws \Jawira\PhingVisualizer\DiagramException
     * @return int|bool The function returns the number of bytes that were written to the file, or false on failure.
     */
    public function save(string $format, ?string $output)
    {
        switch ($format) {
            case self::FORMAT_PUML:
                $content = $this->generatePuml();
                break;
            case self::FORMAT_EPS:
            case self::FORMAT_PNG:
            case self::FORMAT_SVG:
                $content = $this->generateImage($format);
                break;
            default:
                throw new DiagramException(sprintf('Format "%s" not handled.', $format));
                break;
        }
        $output = $this->validateOutputLocation($format, $output);

        return file_put_contents($output, $content);
    }

    /**
     * Retrieves image from Internet
     *
     * @param string $format
     *
     * @return bool|string
     * @throws \Jawira\PhingVisualizer\DiagramException
     */
    protected function generateImage(string $format): string
    {
        $url = $this->generateUrl($format);

        return file_get_contents($url);
    }

    /**
     * @param string $format
     *
     * @return string
     * @throws \Jawira\PhingVisualizer\DiagramException
     */
    public function generateUrl(string $format): string
    {
        switch ($format) {
            case self::FORMAT_EPS:
            case self::FORMAT_PNG:
            case self::FORMAT_SVG:
                break;
            default:
                throw new DiagramException(sprintf('Format "%s" not handled.', $format));
                break;
        }

        $puml    = $this->generatePuml();
        $encoded = encodep($puml);

        return sprintf(self::URL, $format, $encoded);
    }

    /**
     * Generate PlantUml code
     *
     * @return string
     */
    protected function generatePuml(): string
    {
        $puml = '@startuml' . PHP_EOL;

        $xslStyle   = simplexml_load_string(file_get_contents(self::XSL_STYLE));
        $xslTargets = simplexml_load_string(file_get_contents(self::XSL_TARGETS));
        $xslCalls   = simplexml_load_string(file_get_contents(self::XSL_CALLS));

        foreach ([$xslStyle, $xslTargets, $xslCalls] as $xsl) {
            $xmlDoc    = simplexml_load_string(file_get_contents($this->getBuildfile()));
            $processor = new XSLTProcessor();
            $processor->importStylesheet($xsl);
            $puml .= $processor->transformToXml($xmlDoc) . PHP_EOL;
        }

        $puml .= '@enduml' . PHP_EOL;

        return $puml;
    }
}
