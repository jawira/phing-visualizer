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
     * @throws \Jawira\PhingVisualizer\DiagramException
     */
    public function saveDiagramUsingAsFormatAndAsOutput($arg1, $arg2)
    {
        $this->diagram->save($arg1, $arg2);
    }

    /**
     * @Then /^I should have a file called "([^"]*)"$/
     * @param string $fileLocation
     *
     * @throws \Exception
     */
    public function iShouldHaveAFileCalled($fileLocation)
    {
        if (!is_file($fileLocation)) {
            throw new Exception("File '$fileLocation' does not exists.'");
        }
    }

}