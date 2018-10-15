Feature: Create Phing diagram
  In order to see Phing visualization
  As a dev
  I need to instantiate and use Diagram class

  Scenario Outline: Create diagram from default buildfile
    Given I use "<input>" as input file
    When I instantiate Diagram class
    And save Diagram using "<format>" as format and "<output>" as output
    Then I should have a file called "<path>"

    Examples:
      | input     | format | output           | path             |
      | build.xml | eps    | /tmp/            | /tmp/build.eps   |
      | build.xml | puml   | /tmp/            | /tmp/build.puml  |
      | build.xml | png    | /tmp/            | /tmp/build.png   |
      | build.xml | svg    | /tmp/            | /tmp/build.svg   |
      | build.xml | eps    | /tmp/custom.eps  | /tmp/custom.eps  |
      | build.xml | puml   | /tmp/custom.puml | /tmp/custom.puml |
      | build.xml | png    | /tmp/custom.png  | /tmp/custom.png  |
      | build.xml | svg    | /tmp/custom.svg  | /tmp/custom.svg  |
      | build.xml | eps    | .                | build.eps        |
      | build.xml | puml   | .                | build.puml       |
      | build.xml | png    | .                | build.png        |
      | build.xml | svg    | .                | build.svg        |
