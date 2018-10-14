Feature: Product basket
  In order to buy products
  As a customer
  I need to be able to put interesting products into a basket

  Scenario: Display help (long version)
    Given I use "build.xml" as input file
    When I instantiate Diagram class
    And save Diagram using "eps" as format and "." as location
    Then I should have a file called "build.eps"
