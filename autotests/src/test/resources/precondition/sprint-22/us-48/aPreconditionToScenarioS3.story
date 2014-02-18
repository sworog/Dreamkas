Meta:
@sprint_22
@us_48
@id_s22u48s3

Scenario: A scenario that prepares data

Given there is the writeOff in the store with number 'SCPBC' ruled by department manager with name 'departmentManager-SCPBC' with values
| elementName | elementValue |
| number | SCPBC-10 |
| date | 02.04.2013 |
Given the user opens write off list page
When the user logs in using 'departmentManager-SCPBC' userName and 'lighthouse' password
And the user clicks the local navigation writeOff create link
And the user inputs 'SCPBC-10' in the 'writeOff number' field on the write off page
And the user inputs '24.10.2012' in the 'writeOff date' field on the write off page
And the user continues the write off creation
When the user logs out

