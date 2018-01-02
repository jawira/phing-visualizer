<?php

namespace Jawira\PhingVisualiser;

use XSLTProcessor;
use function Jawira\PlantUml\encodep;

class Diagram
{
    const FORMAT_SVG = 'svg';
    const FORMAT_PNG = 'png';
    const FORMAT_PUML = 'puml';
    const XSL_PATH = __DIR__ . DIRECTORY_SEPARATOR . '../resources/xslt/plantuml.xsl';
    const URL = 'http://www.plantuml.com/plantuml/%s/%s';

    protected $input;
    protected $output;
    protected $format = self::FORMAT_PNG;

    /**
     * Diagram constructor.
     *
     * @param string $input Path where buildfile is located
     *
     * @throws DiagramException
     */
    public function __construct(string $input)
    {
        $this->loadInput($input);
    }

    /**
     * Load buildfile location
     *
     * @param string $input
     *
     * @return $this
     * @throws DiagramException
     */
    protected function loadInput(string $input)
    {
        // Input must exist
        if (!is_file($input)) {
            throw new DiagramException('Invalid input.');
        }

        $this->input = $input;

        return $this;
    }

    /**
     * Load PlantUML diagram output location
     *
     * @param null|string $output
     *
     * @return $this
     * @throws DiagramException
     */
    protected function loadOutput(?string $output)
    {
        $inputInfo = pathinfo($this->input);

        // Fallback
        if (empty($output)) {
            $output = $inputInfo['dirname'];
        }

        // Adding filename if necessary
        if (is_dir($output)) {
            $output .= DIRECTORY_SEPARATOR . $inputInfo['filename'] . '.' . $this->format;
        }

        // Check if path is available
        if (!is_dir(dirname($output))) {
            throw new DiagramException('Invalid output.');
        }

        $this->output = $output;

        return $this;
    }

    /**
     * @param null|string $format
     *
     * @return $this
     * @throws DiagramException
     */
    protected function loadFormat(?string $format)
    {
        if (is_null($format)) {
            $format = $this->format;
        }

        switch ($format) {
            case self::FORMAT_PNG:
            case self::FORMAT_SVG:
            case self::FORMAT_PUML:
                $this->format = $format;
                break;
            default:
                throw new DiagramException('Invalid format.');
                break;
        }

        return $this;
    }

    /**
     * @param null|string $format
     * @param null|string $output
     *
     * @throws DiagramException
     */
    public function save(?string $format, ?string $output)
    {
        $this->loadFormat($format);
        $this->loadOutput($output);

        $puml = $this->generatePuml();

        switch ($this->format) {
            case self::FORMAT_PUML:
                $content = $puml;
                break;
            case self::FORMAT_SVG:
            case self::FORMAT_PNG:
                $content = $this->generateImage($puml);
                break;
            default:
                throw new DiagramException('Invalid format to save.');
                break;
        }

        file_put_contents($this->output, $content);
    }

    /**
     * Generate PlantUml code
     *
     * @return string
     */
    protected function generatePuml()
    {
        $xmlDoc = simplexml_load_file($this->input);
        $xslDoc = simplexml_load_file(self::XSL_PATH);

        $proc = new XSLTProcessor();
        $proc->importStylesheet($xslDoc);

        return $proc->transformToXML($xmlDoc);
    }

    /**
     * Retrieves image from Internet
     *
     * @param string $puml PlantUML source code
     *
     * @return bool|string
     */
    protected function generateImage(string $puml): string
    {
        $encoded = encodep($puml);
        $url = sprintf(self::URL, $this->format, $encoded);

        return file_get_contents($url);
    }

}
