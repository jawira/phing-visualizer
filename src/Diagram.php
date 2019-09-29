<?php

namespace Jawira\PhingVisualizer;

use SimpleXMLElement;
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
    public const XSL_HEADER        = __DIR__ . '/../resources/xslt/header.xsl';
    public const XSL_TARGETS       = __DIR__ . '/../resources/xslt/targets.xsl';
    public const XSL_CALLS         = __DIR__ . '/../resources/xslt/calls.xsl';
    public const XSL_FOOTER        = __DIR__ . '/../resources/xslt/footer.xsl';
    public const URL               = 'http://www.plantuml.com/plantuml/%s/%s';

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
     * @return string Location where the diagram was written
     * @throws \Jawira\PhingVisualizer\DiagramException
     */
    public function save(string $format, ?string $output = null): string
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

        if (!file_put_contents($output, $content)) {
            throw new DiagramException('Error while writing diagram');
        }

        return $output;
    }

    /**
     * Retrieves image from Internet
     *
     * @param string $format
     *
     * @return string
     * @throws \Jawira\PhingVisualizer\DiagramException
     */
    protected function generateImage(string $format): string
    {
        $url = $this->generateUrl($format);

        $content = file_get_contents($url);

        if ($content === false) {
            $content = '';
        }

        return $content;
    }

    /**
     * @param string $format
     *
     * @return string
     * @throws \Jawira\PhingVisualizer\DiagramException
     * @throws \Exception
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
     * @throws \Jawira\PhingVisualizer\DiagramException
     */
    protected function generatePuml(): string
    {
        $puml = '';

        foreach ([self::XSL_HEADER, self::XSL_TARGETS, self::XSL_CALLS, self::XSL_FOOTER] as $xslFile) {
            $puml .= $this->transformToPuml($xslFile);
        }

        return $puml;
    }

    /**
     * Transforms buildfile using provided xsl file
     *
     * @param string $xslFile XSLT file
     *
     * @return string
     * @throws \Jawira\PhingVisualizer\DiagramException
     */
    public function transformToPuml(string $xslFile): string
    {
        // Loading xml
        $xslContent = file_get_contents($xslFile);
        if ($xslContent === false) {
            throw new DiagramException('Invalid xslt content');
        }
        $xsl = simplexml_load_string($xslContent);
        if (!($xsl instanceof SimpleXMLElement)) {
            throw new DiagramException('Cannot read xsl string');
        }

        // Loading XML
        $xmlContent = file_get_contents($this->getBuildfile());
        if ($xmlContent === false) {
            throw new DiagramException('Invalid xml content');
        }
        $xml = simplexml_load_string($xmlContent);

        // Processor
        $processor = new XSLTProcessor();
        $processor->importStylesheet($xsl);

        return $processor->transformToXml($xml) . PHP_EOL;
    }
}
