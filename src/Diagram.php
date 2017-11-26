<?php

namespace Jawira\PhingDiagram;

use SimpleXMLElement;
use XSLTProcessor;

class Diagram
{
    const FORMAT_SVG = 'svg';
    const FORMAT_PNG = 'png';
    const FORMAT_PUML = 'puml';

    const XSL_PATH = '../resources/xslt/plantuml.xsl';

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
                break;
            default:
                throw new DiagramException('Invalid format.');
                break;
        }

        $this->format = $format;

        return $this;
    }

    public function save()
    {
        $xmlDoc = simplexml_load_file($this->input);
        $xslDoc = simplexml_load_file(self::XSL_PATH);
        
        $proc = new XSLTProcessor();
        $proc->importStylesheet($xslDoc);
        $content = $proc->transformToXML($xmlDoc);

        file_put_contents($this->output, $content);
    }

}
