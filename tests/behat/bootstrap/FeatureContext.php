<?php

namespace Jawira\PhingVisualizer\Behat;

use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use kamermans\Command\Command;

/**
 * Defines application features from the specific context.
 */
class FeatureContext implements Context
{

    /**
     * @var \kamermans\Command\Command
     */
    protected $command;

    /**
     * @var string
     */
    protected $output;

    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
    }

    /**
     * @Given executable is located in :arg1
     *
     * @param string $location phing-visualizer location
     */
    public function executableIsLocatedIn($location)
    {
        $this->command = Command::factory($location);
    }

    /**
     * @When I run given executable
     * @throws \kamermans\Command\CommandException
     */
    public function iRunGivenExecutable()
    {

        $this->output = $this->command->run()->getStdOut();
    }

    /**
     * @Given I use option :arg1
     *
     * @param string $arg1 Option (example: --help)
     */
    public function iUseOption($arg1)
    {
        $this->command->option($arg1);
    }


    /**
     * @Then The output should contain :arg1
     *
     * @param string $arg1 String to search in output
     *
     * @throws \Exception
     */
    public function theOutputShouldContain($arg1)
    {

        if (strpos($this->output, $arg1) === false) {
            throw new \Exception("Output does not contain $arg1");
        }
    }

    /**
     * @Then The output should contain
     *
     * @param \Behat\Gherkin\Node\PyStringNode $string
     *
     * @throws \Exception
     */
    public function theOutputShouldContainLong(PyStringNode $string)
    {
        $this->theOutputShouldContain(trim($string));
    }
}
