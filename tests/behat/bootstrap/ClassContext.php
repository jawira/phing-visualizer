<?php

namespace Jawira\PhingVisualizer\Behat;

use Behat\Behat\Context\Context;
use Exception;
use Jawira\PhingVisualizer\Diagram;

class ClassContext implements Context
{
    /**
     * @var \Jawira\PhingVisualizer\Diagram;
     */
    protected $diagram;

    /**
     * @var string
     */
    protected $input;
    protected $format;
    protected $output;
    protected $fileLocation;

    /**
     * @Given /^I use "([^"]*)" as input file$/
     * @param string $input
     */
    public function iUseAsInputFile($input)
    {
        $this->input = $input;
    }

    /**
     * @When /^I instantiate Diagram class$/
     * @throws \Jawira\PhingVisualizer\DiagramException
     */
    public function iInstantiateDiagramClass()
    {
        $this->diagram = new Diagram($this->input);
    }

    /**
     * @Given /^save Diagram using "([^"]*)" as format and "([^"]*)" as output$/
     * @param string $format Generated file format
     * @param string $output Generated file location
     *
     * @throws \Jawira\PhingVisualizer\DiagramException
     */
    public function saveDiagramUsingAsFormatAndAsOutput($format, $output)
    {
        $this->format = $format;
        $this->output = $output;
        $this->diagram->save($format, $output);
    }

    /**
     * @Then /^I should have a file called "([^"]*)"$/
     * @param string $fileLocation
     *
     * @throws \Exception
     */
    public function iShouldHaveAFileCalled($fileLocation)
    {
        $this->fileLocation = $fileLocation;
        if (!is_file($fileLocation)) {
            throw new Exception("File '$fileLocation' does not exists.'");
        }
    }

    /**
     * @Given /^File should have at least "([^"]*)" bytes$/
     * @param int $bytes Expected file size in bytes
     *
     * @throws \Exception
     */
    public function fileShouldHaveAtLeastBytes($bytes)
    {
        $fileSize = filesize($this->fileLocation);
        if ($fileSize < $bytes) {
            throw new Exception("File too small $fileSize bytes, expecting $bytes bytes");
        }
    }

    public function __destruct()
    {
        if (file_exists($this->fileLocation)) {
            unlink($this->fileLocation);
        }
    }
}