Meta:
@sprint 25
@us 57.3

Narrative:
As a user
I want to perform an action
So that I can achieve a business goal

Scenario: Gross sale by subCategories contains Zero sales

Meta:
@id s25u57.3s1
@description checks the gross sale by subCategories contains zero sales(0,00 р.)if no sales are registered

GivenStories: precondition/sprint-25/us-57_3/aPreconditionToScenarioS1.story

Given the user opens the authorization page
When the user logs in using 'storeManager-s25u573' userName and 'lighthouse' password
And the user clicks the menu report item
And the user clicks on gross sale by products report link
When the user clicks the catalog item with name 'defaultGroup-s25u573'
And the user clicks the catalog item with name 'defaultCategory-s25u573'
Then the user checks the gross sale by subCategory report contains zero sales value entry

Scenario: Gross sale by groups/Categories/subCategories contains data not red highlighted (todayValue is bigger than yesterdaya and weekAgo)

Meta:
@id s25u57.3s2
@description gross sale by groups/Categories/subCategories contains data not red highlighted (todayValue is bigger than yesterday and weekAgo)

GivenStories: precondition/sprint-25/us-57_3/aPreconditionToScenarioS1.story

Given the user prepares data for red highlighted checks - today data is bigger than yesterday and smaller than weekAgo one for story 57.3
Given the user runs the symfony:reports:recalculate command

Given the user opens the authorization page
When the user logs in using 'storeManager-s25u573' userName and 'lighthouse' password
And the user clicks the menu report item
And the user clicks on gross sale by products report link
Then the user checks the catalog item today entry value by locator 'defaultGroup-s25u573' is not red highlighted
When the user clicks the catalog item with name 'defaultGroup-s25u573'
Then the user checks the catalog item today entry value by locator 'defaultCategory-s25u573' is not red highlighted
When the user clicks the catalog item with name 'defaultCategory-s25u573'
Then the user checks the subCategory today entry value by locator 'defaultSubCategory-s25u573' is not red highlighted

Scenario: Gross sale by groups/Categories/subCategories contains data not red highlighted (todayValue is equal yesterday and weekAgo)

Meta:
@id s25u57.3s3
@description gross sale by groups/Categories/subCategories contains data not red highlighted (todayValue is equal yesterday and weekAgo)

GivenStories: precondition/sprint-25/us-57_3/aPreconditionToScenarioS1.story

Given the user prepares data for red highlighted checks - today data is equal yesterday/weekAgo one for story 57.3
Given the user runs the symfony:reports:recalculate command

Given the user opens the authorization page
When the user logs in using 'storeManager-s25u573' userName and 'lighthouse' password
And the user clicks the menu report item
And the user clicks on gross sale by products report link
Then the user checks the catalog item today entry value by locator 'defaultGroup-s25u573' is not red highlighted
When the user clicks the catalog item with name 'defaultGroup-s25u573'
Then the user checks the catalog item today entry value by locator 'defaultCategory-s25u573' is not red highlighted
When the user clicks the catalog item with name 'defaultCategory-s25u573'
Then the user checks the subCategory today entry value by locator 'defaultSubCategory-s25u573' is not red highlighted

Scenario: Gross sale by groups/Categories/subCategories contains data not red highlighted (todayValue is smaller than yesterday and bigger than weekAgo)

Meta:
@id s25u57.3s4
@description gross sale by groups/Categories/subCategories contains data not red highlighted (todayValue is smaller than yesterday and bigger than weekAgo)

GivenStories: precondition/sprint-25/us-57_3/aPreconditionToScenarioS1.story

Given the user prepares data for red highlighted checks - today data is smaller than yesterday and bigger than weekAgo one for story 57.3
Given the user runs the symfony:reports:recalculate command

Given the user opens the authorization page
When the user logs in using 'storeManager-s25u573' userName and 'lighthouse' password
And the user clicks the menu report item
And the user clicks on gross sale by products report link
Then the user checks the catalog item today entry value by locator 'defaultGroup-s25u573' is not red highlighted
When the user clicks the catalog item with name 'defaultGroup-s25u573'
Then the user checks the catalog item today entry value by locator 'defaultCategory-s25u573' is not red highlighted
When the user clicks the catalog item with name 'defaultCategory-s25u573'
Then the user checks the subCategory today entry value by locator 'defaultSubCategory-s25u573' is not red highlighted

Scenario: Gross sale by groups/Categories/subCategories contains data not red highlighted (todayValue is bigger than yesterday and smaller than weekAgo)

Meta:
@id s25u57.3s5
@description gross sale by groups/Categories/subCategories contains data not red highlighted (todayValue is bigger than yesterday and smaller than weekAgo)

GivenStories: precondition/sprint-25/us-57_3/aPreconditionToScenarioS1.story

Given the user prepares data for red highlighted checks - today data is bigger than yesterday and smaller than weekAgo one for story 57.3
Given the user runs the symfony:reports:recalculate command

Given the user opens the authorization page
When the user logs in using 'storeManager-s25u573' userName and 'lighthouse' password
And the user clicks the menu report item
And the user clicks on gross sale by products report link
Then the user checks the catalog item today entry value by locator 'defaultGroup-s25u573' is not red highlighted
When the user clicks the catalog item with name 'defaultGroup-s25u573'
Then the user checks the catalog item today entry value by locator 'defaultCategory-s25u573' is not red highlighted
When the user clicks the catalog item with name 'defaultCategory-s25u573'
Then the user checks the subCategory today entry value by locator 'defaultSubCategory-s25u573' is not red highlighted

Scenario: Gross sale by groups/Categories/subCategories contains data red highlighted (todayValue is smaller than yesterday/weekAgo)

Meta:
@id s25u57.3s6
@description gross sale by groups/Categories/subCategories contains data red highlighted (todayValue is smaller than yesterday/weekAgo)
@smoke

GivenStories: precondition/sprint-25/us-57_3/aPreconditionToScenarioS1.story

Given the user prepares data for red highlighted checks - today data is smaller than yesterday and weekAgo one for story 57.3
Given the user runs the symfony:reports:recalculate command

Given the user opens the authorization page
When the user logs in using 'storeManager-s25u573' userName and 'lighthouse' password
And the user clicks the menu report item
And the user clicks on gross sale by products report link
Then the user checks the catalog item today entry value by locator 'defaultGroup-s25u573' is red highlighted
When the user clicks the catalog item with name 'defaultGroup-s25u573'
Then the user checks the catalog item today entry value by locator 'defaultCategory-s25u573' is red highlighted
When the user clicks the catalog item with name 'defaultCategory-s25u573'
Then the user checks the subCategory today entry value by locator 'defaultSubCategory-s25u573' is red highlighted

Scenario: Main Gross sale by subCategories testing scenario

Meta:
@id s25u57.3s7
@description main gross sale by subCategories testing scenario
@smoke

GivenStories: precondition/sprint-25/us-57_3/aPreconditionToScenarioS2.story

Given the user prepares data for story 57.3 testing
Given the user runs the symfony:reports:recalculate command

Given the user opens the authorization page
When the user logs in using 'storeManager-s25u573' userName and 'lighthouse' password
And the user clicks the menu report item
And the user clicks on gross sale by products report link
Then the user checks the gross sale by subCategory report contains entry
| name | todayValue | yesterdayValue | weekAgoValue |
| defaultGroup-s25u573 | 0,00 р. | 0,00 р. | 0,00 р. |
When the user clicks the catalog item with name 'defaultGroup-s25u573'
Then the user checks the gross sale by subCategory report contains entry
| name | todayValue | yesterdayValue | weekAgoValue |
| defaultCategory-s25u573 | 0,00 р. | 0,00 р. | 0,00 р. |
When the user clicks the catalog item with name 'defaultCategory-s25u573'
Then the user checks the gross sale by subCategory report contains entry
| name | todayValue | yesterdayValue | weekAgoValue |
| defaultSubCategory-s25u573 | 0,00 р. | 0,00 р. | 0,00 р. |

Given the user opens the authorization page
When the user clicks the menu report item
And the user clicks on gross sale by products report link
Then the user checks the gross sale by group report contains correct data for defaultGroup-s25u5731 of shop 25573
When the user clicks the catalog item with name 'defaultGroup-s25u5731'
Then the user checks the gross sale by category report contains correct data for defaultCategory-s25u5731 of shop 25573
When the user clicks the catalog item with name 'defaultCategory-s25u5731'
Then the user checks the gross sale by subCategory report contains correct data for defaultSubCategory-s25u5731 of shop 25573
When the user logs out

Given the user opens the authorization page
When the user logs in using 'storeManager-s25u5731' userName and 'lighthouse' password
And the user clicks the menu report item
And the user clicks on gross sale by products report link
Then the user checks the gross sale by group report contains correct data for defaultGroup-s25u573 of shop 255731
When the user clicks the catalog item with name 'defaultGroup-s25u573'
Then the user checks the gross sale by category report contains correct data for defaultCategory-s25u573 of shop 255731
When the user clicks the catalog item with name 'defaultCategory-s25u573'
Then the user checks the gross sale by subCategory report contains correct data for defaultSubCategory-s25u573 of shop 255731

Given the user opens the authorization page
When the user clicks the menu report item
And the user clicks on gross sale by products report link
Then the user checks the gross sale by subCategory report contains entry
| name | todayValue | yesterdayValue | weekAgoValue |
| defaultGroup-s25u5731 | 0,00 р. | 0,00 р. | 0,00 р. |
When the user clicks the catalog item with name 'defaultGroup-s25u5731'
Then the user checks the gross sale by subCategory report contains entry
| name | todayValue | yesterdayValue | weekAgoValue |
| defaultCategory-s25u5731 | 0,00 р. | 0,00 р. | 0,00 р. |
When the user clicks the catalog item with name 'defaultCategory-s25u5731'
Then the user checks the gross sale by subCategory report contains entry
| name | todayValue | yesterdayValue | weekAgoValue |
| defaultSubCategory-s25u5731 | 0,00 р. | 0,00 р. | 0,00 р. |