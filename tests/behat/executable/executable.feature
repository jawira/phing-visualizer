Feature: Product basket
  In order to buy products
  As a customer
  I need to be able to put interesting products into a basket

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
      | input     | format | output           | path             | size  |
      | build.xml | eps    | /tmp/            | /tmp/build.eps   | 87193 |
      | build.xml | puml   | /tmp/            | /tmp/build.puml  | 1078  |
      | build.xml | png    | /tmp/            | /tmp/build.png   | 55441 |
      | build.xml | svg    | /tmp/            | /tmp/build.svg   | 14371 |
      | build.xml | eps    | /tmp/custom.eps  | /tmp/custom.eps  | 87193 |
      | build.xml | puml   | /tmp/custom.puml | /tmp/custom.puml | 1078  |
      | build.xml | png    | /tmp/custom.png  | /tmp/custom.png  | 55441 |
      | build.xml | svg    | /tmp/custom.svg  | /tmp/custom.svg  | 14371 |
      | build.xml | eps    | .                | build.eps        | 87193 |
      | build.xml | puml   | .                | build.puml       | 1078  |
      | build.xml | png    | .                | build.png        | 55441 |
      | build.xml | svg    | .                | build.svg        | 14371 |


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
