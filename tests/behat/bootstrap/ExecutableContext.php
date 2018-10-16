<?php

namespace Jawira\PhingVisualizer\Behat;

use Behat\Behat\Context\Context;
use Behat\Behat\Tester\Exception\PendingException;
use Behat\Gherkin\Node\PyStringNode;
use Exception;
use kamermans\Command\Command;

/**
 * Defines application features from the specific context.
 */
class ExecutableContext implements Context
{

    /**
     * @var \kamermans\Command\Command
     */
    protected $command;

    /**
     * @var string
     */
    protected $output;
    protected $fileLocation;

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

    public function __destruct()
    {
        if (file_exists($this->fileLocation)) {
            unlink($this->fileLocation);
        }
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
            throw new Exception("Output does not contain $arg1");
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
     * @Given I set option :arg1 with value :arg2
     */

    public function iSetOptionWithValue($option, $value)
    {
        $this->command->option($option, $value);
    }

    /**
     * @Given I use option :arg1
     */

    public function iUseOption($option)
    {
        $this->command->option($option);
    }


}
