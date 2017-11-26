<?php

namespace Jawira\PhingDiagram;

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
    protected $format;

    public function __construct(string $input, string $output, string $format)
    {
        $this->loadFormat($format)->loadInput($input)->loadOutput($output);
    }

    protected function loadInput(string $input)
    {
        // Input must exist
        if (!is_file($input)) {
            throw new DiagramException('Invalid input.');
        }

        $this->input = $input;

        return $this;
    }

    protected function loadOutput(string $output)
    {
        // Adding filename if necessary
        if (is_dir($output)) {
            $inputInfo = pathinfo($this->input);
            $output .= DIRECTORY_SEPARATOR . $inputInfo['filename'] . '.' . $this->format;
        }

        // Check if path is available
        if (!is_dir(dirname($output))) {
            throw new DiagramException('Invalid output.');
        }

        $this->output = $output;

        return $this;
    }

    protected function loadFormat(string $format)
    {
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

    public function save()
    {
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

    protected function generateImage(string $puml)
    {
        $encoded = encodep($puml);
        $url = sprintf(self::URL, $this->format, $encoded);

        return file_get_contents($url);
    }

}
