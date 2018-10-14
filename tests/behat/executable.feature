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