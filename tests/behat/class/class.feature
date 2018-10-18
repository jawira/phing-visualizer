Feature: Create Phing diagram
  In order to see Phing visualization
  As a dev
  I need to instantiate and use Diagram class

  Scenario Outline: Create diagram from resources/buildfiles/dummy.xml
    Given I use "<input>" as input file
    When I instantiate Diagram class
    And save Diagram using "<format>" as format and "<output>" as output
    Then I should have a file called "<path>"
    And File should have at least "<size>" bytes

    Examples:
      | input                          | format | output           | path             | size  |
      | resources/buildfiles/dummy.xml | eps    | /tmp/            | /tmp/dummy.eps   | 43575 |
      | resources/buildfiles/dummy.xml | puml   | /tmp/            | /tmp/dummy.puml  | 472   |
      | resources/buildfiles/dummy.xml | png    | /tmp/            | /tmp/dummy.png   | 19505 |
      | resources/buildfiles/dummy.xml | svg    | /tmp/            | /tmp/dummy.svg   | 7689  |
      | resources/buildfiles/dummy.xml | eps    | /tmp/custom.eps  | /tmp/custom.eps  | 43575 |
      | resources/buildfiles/dummy.xml | puml   | /tmp/custom.puml | /tmp/custom.puml | 472   |
      | resources/buildfiles/dummy.xml | png    | /tmp/custom.png  | /tmp/custom.png  | 19505 |
      | resources/buildfiles/dummy.xml | svg    | /tmp/custom.svg  | /tmp/custom.svg  | 7689  |
      | resources/buildfiles/dummy.xml | eps    | .                | dummy.eps        | 43575 |
      | resources/buildfiles/dummy.xml | puml   | .                | dummy.puml       | 472   |
      | resources/buildfiles/dummy.xml | png    | .                | dummy.png        | 19505 |
      | resources/buildfiles/dummy.xml | svg    | .                | dummy.svg        | 7689  |


  Scenario Outline: Create diagram from resources/buildfiles/dummy.xml using default output
    Given I use "<input>" as input file
    When I instantiate Diagram class
    And save Diagram using "<format>" as format
    Then I should have a file called "<path>"
    And File should have at least "<size>" bytes

    Examples:
      | input                          | format | path                            | size  |
      | resources/buildfiles/dummy.xml | eps    | resources/buildfiles/dummy.eps  | 43575 |
      | resources/buildfiles/dummy.xml | puml   | resources/buildfiles/dummy.puml | 472   |
      | resources/buildfiles/dummy.xml | png    | resources/buildfiles/dummy.png  | 19505 |
      | resources/buildfiles/dummy.xml | svg    | resources/buildfiles/dummy.svg  | 7689  |
