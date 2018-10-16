Feature: Create Phing diagram
  In order to see Phing visualization
  As a dev
  I need to instantiate and use Diagram class

  Scenario Outline: Create diagram from build.xml
    Given I use "<input>" as input file
    When I instantiate Diagram class
    And save Diagram using "<format>" as format and "<output>" as output
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


  Scenario Outline: Create diagram from build.xml using default output
    Given I use "<input>" as input file
    When I instantiate Diagram class
    And save Diagram using "<format>" as format
    Then I should have a file called "<path>"
    And File should have at least "<size>" bytes

    Examples:
      | input     | format | path       | size  |
      | build.xml | eps    | build.eps  | 87193 |
      | build.xml | puml   | build.puml | 1078  |
      | build.xml | png    | build.png  | 55441 |
      | build.xml | svg    | build.svg  | 14371 |
