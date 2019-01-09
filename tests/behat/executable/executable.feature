Feature: Execute phing-visualizer
  In order to have a graphical representation of buildfile
  As a developer
  I need to run the executable

  Scenario: Display help (long version)
    Given executable is located in "bin/phing-visualizer"
    And I use option "--help"
    When I run given executable
    Then The output should contain "NAME"
    And The output should contain "SYNOPSIS"
    And The output should contain "DESCRIPTION"
    And The output should contain "OPTIONS"
    And The output should contain "EXAMPLES"
    And The output should contain
    """
    NAME
        phing-visualizer - visualize Phing's buildfile

    SYNOPSIS
        phing-visualizer [-i <buildfile>] [-f <png|svg|puml|eps>] [-o <path>]
    """


  Scenario Outline: Create diagram with three options
    Given executable is located in "bin/phing-visualizer"
    And I set option "--input" with value "<input>"
    And I set option "--format" with value "<format>"
    And I set option "--output" with value "<output>"
    When I run given executable
    Then I should have a file called "<path>"
    And File should have at least "<size>" bytes

    Examples:
      | input                          | format | output           | path             | size  |
      | resources/buildfiles/dummy.xml | eps    | /tmp/            | /tmp/dummy.eps   | 43519 |
      | resources/buildfiles/dummy.xml | puml   | /tmp/            | /tmp/dummy.puml  | 472   |
      | resources/buildfiles/dummy.xml | png    | /tmp/            | /tmp/dummy.png   | 18518 |
      | resources/buildfiles/dummy.xml | svg    | /tmp/            | /tmp/dummy.svg   | 7689  |
      | resources/buildfiles/dummy.xml | eps    | /tmp/custom.eps  | /tmp/custom.eps  | 43519 |
      | resources/buildfiles/dummy.xml | puml   | /tmp/custom.puml | /tmp/custom.puml | 472   |
      | resources/buildfiles/dummy.xml | png    | /tmp/custom.png  | /tmp/custom.png  | 18518 |
      | resources/buildfiles/dummy.xml | svg    | /tmp/custom.svg  | /tmp/custom.svg  | 7689  |
      | resources/buildfiles/dummy.xml | eps    | .                | dummy.eps        | 43519 |
      | resources/buildfiles/dummy.xml | puml   | .                | dummy.puml       | 472   |
      | resources/buildfiles/dummy.xml | png    | .                | dummy.png        | 18518 |
      | resources/buildfiles/dummy.xml | svg    | .                | dummy.svg        | 7689  |


  Scenario Outline: Create diagram with one option
    Given executable is located in "bin/phing-visualizer"
    And I set option "--output" with value "<output>"
    When I run given executable
    Then I should have a file called "<path>"
    And File should have at least "<size>" bytes

    Examples:
      | output          | path            | size  |
      | /tmp/           | /tmp/build.png  | 55441 |
      | /tmp/custom.png | /tmp/custom.png | 55441 |
      | .               | build.png       | 55441 |


  Scenario Outline: Create diagram with no options
    Given executable is located in "bin/phing-visualizer"
    When I run given executable
    Then I should have a file called "<path>"
    And File should have at least "<size>" bytes

    Examples:
      | path      | size  |
      | build.png | 55441 |
