Meta:
@sprint_24
@us_55.2
@smoke
@id_s24u55.2s6
@id_s24u55.2s7
@id_s24u55.2s8

Scenario: A scenario that prepares data

Given there is created store with number '2455212', address '245521', contacts '245521'
And there is created store with number '2455222', address '245522', contacts '245522'

Given the user prepares data for us 55.2 story
And the user prepares data for us 55.2 story part two
And the user runs the symfony:reports:recalculate command
