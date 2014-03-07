Meta:
@sprint_22
@us_48

Narrative:
As a user
I want to perform an action
So that I can achieve a business goal

Scenario: Nothing found- WriteOff

Meta:
@id_s22u48s1
@description nothing found with non exist writeOff number

GivenStories: precondition/sprint-22/us-48/aPreconditionToStoryUs48.story

Given the user opens write off list page
When the user logs in using 'departmentManager-SCPBC' userName and 'lighthouse' password
And the user clicks the local navigation writeOff search link
And the user searches writeOff by number 'Янехочуискатьсписанияяхочуплатье'
And the user clicks the writeOff search buttton and starts the search
Then the user checks the form results text is 'Мы не смогли найти списание с номером Янехочуискатьсписанияяхочуплатье'

Scenario: WriteOff found by number

Meta:
@id_s22u48s2
@description writeOff with number can be found

GivenStories: precondition/sprint-22/us-48/aPreconditionToStoryUs48.story,
              precondition/sprint-22/us-48/aPreconditionToScenarioS2.story

Given the user opens write off list page
When the user logs in using 'departmentManager-SCPBC' userName and 'lighthouse' password
And the user clicks the local navigation writeOff search link
And the user searches writeOff by number 'SCPBC-10'
And the user clicks the writeOff search buttton and starts the search
Then the user checks the form results text is 'Нашлось 1 списание'
And the user checks the writeOff with sku 'SCPBC-10' in search results
And the user checks the writeOff search result list contains stored values entry
And the user checks writeOff highlighted text is 'Списание № SCPBC-10 от 02.04.2013'

Scenario: Two writeOffs found by number

Meta:
@id_s22u48s3
@description two writeOffs with equal numbers can be found

GivenStories: precondition/sprint-22/us-48/aPreconditionToStoryUs48.story,
              precondition/sprint-22/us-48/aPreconditionToScenarioS3.story

Given the user opens write off list page
When the user logs in using 'departmentManager-SCPBC' userName and 'lighthouse' password
When the user clicks the local navigation writeOff search link
And the user searches writeOff by number 'SCPBC-10'
And the user clicks the writeOff search buttton and starts the search
Then the user checks the form results text is 'Нашлось 2 списания'
And the user checks the writeOff with sku 'SCPBC-10' in search results
And the user checks the writeOff search result list contains stored values entry
And the user checks writeOff highlighted text is 'Списание № SCPBC-10 от 02.04.2013'

Scenario: WriteOff with product found by number

Meta:
@id_s22u48s4
@description writeOffs with product can be found

GivenStories: precondition/sprint-22/us-48/aPreconditionToStoryUs48.story,
              precondition/sprint-22/us-48/aPreconditionToScenarioS4.story

Given the user opens write off list page
When the user logs in using 'departmentManager-SCPBC' userName and 'lighthouse' password
And the user clicks the local navigation writeOff search link
And the user searches writeOff by number 'SCPBC-11'
And the user clicks the writeOff search buttton and starts the search
Then the user checks the form results text is 'Нашлось 1 списание'
And the user checks the writeOff with sku 'SCPBC-11' in search results
Then the user checks write off elements values
| elementName | value |
| writeOff number review | SCPBC-11 |
| writeOff date review | 02.04.2013 |
And the user checks the write off product with 'SCPBC-sku-11' sku is present
And the user checks the product with 'SCPBC-sku-11' sku has elements on the write off page
| elementName | value |
| writeOff product name review | SCPBC-name-11 |
| writeOff product sku review | SCPBC-sku-11 |
| writeOff product barCode review | SCPBC-barcode-11 |
| writeOff product quantity review | 1 |
| writeOff product price review | 12,34 |
| writeOff cause review | Плохо продавался |
Then the user checks write off elements values
| elementName | value |
| totalProducts | 1 |
| totalSum | 12,34 |

Scenario: WriteOff with product found by number click

Meta:
@id_s22u48s5
@description writeOffs with product can be found, the result is clickable and leads to writeOff page
@smoke

GivenStories: precondition/sprint-22/us-48/aPreconditionToStoryUs48.story,
              precondition/sprint-22/us-48/aPreconditionToScenarioS5.story

Given the user opens write off list page
When the user logs in using 'departmentManager-SCPBC' userName and 'lighthouse' password
And the user clicks the local navigation writeOff search link
And the user searches writeOff by number 'SCPBC-12'
And the user clicks the writeOff search buttton and starts the search
Then the user checks the form results text is 'Нашлось 1 списание'
And the user checks the writeOff with sku 'SCPBC-12' in search results
When the user clicks on the search result writeOff with number 'SCPBC-12'
Then the user checks write off elements values
| elementName | value |
| writeOff number review | SCPBC-12 |
| writeOff date review | 02.04.2013 |
And the user checks the write off product with 'SCPBC-sku-11' sku is present
And the user checks the product with 'SCPBC-sku-11' sku has elements on the write off page
| elementName | value |
| writeOff product name review | SCPBC-name-11 |
| writeOff product sku review | SCPBC-sku-11 |
| writeOff product barCode review | SCPBC-barcode-11 |
| writeOff product quantity review | 1 |
| writeOff product price review | 12,34 |
| writeOff cause review | Плохо продавался |
Then the user checks write off elements values
| elementName | value |
| totalProducts | 1 |
| totalSum | 12,34 |