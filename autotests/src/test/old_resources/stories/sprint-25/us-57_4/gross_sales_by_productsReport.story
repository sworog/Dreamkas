Meta:
@sprint_25
@us_57.4

Narrative:
As a директор магазина
I want to видеть сумму продаж каждого товара подкатегории на этот час в сравнении с суммой продаж на этот час вчера и и неделю назад
In order to вовремя понять какой товар подкатегории проваливает продажи

Scenario: Gross sale by products contains zero sales

Meta:
@id_s25u57.4s1
@description check of no sales registered there are zero sales

GivenStories: precondition/sprint-25/us-57_4/aPreconditionToStoryUs57.4.story

Given there is the product with 'name-25574' name, '25574' barcode, 'unit' type, '124,5' purchasePrice of group named 'defaultGroup-s25u574', category named 'defaultCategory-s25u574', subcategory named 'defaultSubCategory-s25u574'

Given the user opens the authorization page
When the user logs in using 'storeManager-s25u574' userName and 'lighthouse' password
And the user clicks the menu report item
And the user clicks on gross sale by products report link
When the user clicks the catalog item with name 'defaultGroup-s25u574'
And the user clicks the catalog item with name 'defaultCategory-s25u574'
And the user clicks the catalog item with name 'defaultSubCategory-s25u574'
Then the user checks the gross sale by products report contains zero sales

Scenario: Gross sale by products can contains table product entry without barcode

Meta:
@id_s25u57.4s2
@description the product entry can be without barcode

GivenStories: precondition/sprint-25/us-57_4/aPreconditionToStoryUs57.4.story

Given there is the product with 'name-255741' name, '' barcode, 'unit' type, '124,5' purchasePrice of group named 'defaultGroup-s25u574', category named 'defaultCategory-s25u574', subcategory named 'defaultSubCategory-s25u574'

Given the user opens the authorization page
When the user logs in using 'storeManager-s25u574' userName and 'lighthouse' password
And the user clicks the menu report item
And the user clicks on gross sale by products report link
When the user clicks the catalog item with name 'defaultGroup-s25u574'
And the user clicks the catalog item with name 'defaultCategory-s25u574'
And the user clicks the catalog item with name 'defaultSubCategory-s25u574'
Then the user checks the gross sale by products report contains correct data if the product barCode is empty

Scenario: Gross sale by products contains correct data

Meta:
@id_s25u57.4s3
@description check the gross sale by products contains correct data
@smoke

GivenStories: precondition/sprint-25/us-57_4/aPreconditionToScenarioS3.story

Given the user opens the authorization page
When the user logs in using 'storeManager-s25u574' userName and 'lighthouse' password
And the user clicks the menu report item
And the user clicks on gross sale by products report link
When the user clicks the catalog item with name 'defaultGroup-s25u574'
And the user clicks the catalog item with name 'defaultCategory-s25u574'
And the user clicks the catalog item with name 'defaultSubCategory-s25u574'
Then the user checks the gross sale by products report contains correct data for product with sku 255742
Given the user opens the authorization page
When the user clicks the menu report item
And the user clicks on gross sale by products report link
When the user clicks the catalog item with name 'defaultGroup-s25u5742'
And the user clicks the catalog item with name 'defaultCategory-s25u5742'
And the user clicks the catalog item with name 'defaultSubCategory-s25u5742'
Then the user checks the gross sale by products report contains empty data for product with sku 255743
When the user logs out
Given the user opens the authorization page
When the user logs in using 'storeManager-s25u5742' userName and 'lighthouse' password
And the user clicks the menu report item
And the user clicks on gross sale by products report link
When the user clicks the catalog item with name 'defaultGroup-s25u574'
And the user clicks the catalog item with name 'defaultCategory-s25u574'
And the user clicks the catalog item with name 'defaultSubCategory-s25u574'
Then the user checks the gross sale by products report contains empty data for product with sku 255742
Given the user opens the authorization page
When the user clicks the menu report item
And the user clicks on gross sale by products report link
When the user clicks the catalog item with name 'defaultGroup-s25u5742'
And the user clicks the catalog item with name 'defaultCategory-s25u5742'
And the user clicks the catalog item with name 'defaultSubCategory-s25u5742'
Then the user checks the gross sale by products report contains correct data for product with sku 255743

Scenario: Gross sale by products contains data not red highlighted (todayValue is bigger than yesterday/weekAgo)

Meta:
@id_s25u57.4s4
@description

GivenStories: precondition/sprint-25/us-57_4/aPreconditionToScenarioS4.story

Given the user prepares data for red highlighted checks - today data is bigger than yesterday/weekAgo one
Given the user runs the symfony:reports:recalculate command

Given the user opens the authorization page
When the user logs in using 'storeManager-s25u574' userName and 'lighthouse' password
And the user clicks the menu report item
And the user clicks on gross sale by products report link
When the user clicks the catalog item with name 'defaultGroup-s25u574'
And the user clicks the catalog item with name 'defaultCategory-s25u574'
And the user clicks the catalog item with name 'defaultSubCategory-s25u574'
Then the user checks the table today value by locator 'name-255742' is not red highlighted

Scenario: Gross sale by products contains data not red highlighted (todayValue is equal yesterday/weekAgo)

Meta:
@id_s25u57.4s5
@description

GivenStories: precondition/sprint-25/us-57_4/aPreconditionToScenarioS4.story

Given the user prepares data for red highlighted checks - today data is equal yesterday/weekAgo one
Given the user runs the symfony:reports:recalculate command

Given the user opens the authorization page
When the user logs in using 'storeManager-s25u574' userName and 'lighthouse' password
And the user clicks the menu report item
And the user clicks on gross sale by products report link
When the user clicks the catalog item with name 'defaultGroup-s25u574'
And the user clicks the catalog item with name 'defaultCategory-s25u574'
And the user clicks the catalog item with name 'defaultSubCategory-s25u574'
Then the user checks the table today value by locator 'name-255742' is not red highlighted

Scenario: Gross sale by products contains data not red highlighted (todayValue is smaller than yesterday and bigger than weekAgo)

Meta:
@id_s25u57.4s6
@description

GivenStories: precondition/sprint-25/us-57_4/aPreconditionToScenarioS4.story

Given the user prepares data for red highlighted checks - today data is smaller than yesterday and bigger than weekAgo one
Given the user runs the symfony:reports:recalculate command

Given the user opens the authorization page
When the user logs in using 'storeManager-s25u574' userName and 'lighthouse' password
And the user clicks the menu report item
And the user clicks on gross sale by products report link
When the user clicks the catalog item with name 'defaultGroup-s25u574'
And the user clicks the catalog item with name 'defaultCategory-s25u574'
And the user clicks the catalog item with name 'defaultSubCategory-s25u574'
Then the user checks the table today value by locator 'name-255742' is not red highlighted

Scenario: Gross sale by products contains data not red highlighted (todayValue is bigger than yesterday and smaller than weekAgo)

Meta:
@id_s25u57.4s7
@description

GivenStories: precondition/sprint-25/us-57_4/aPreconditionToScenarioS4.story

Given the user prepares data for red highlighted checks - today data is bigger than yesterday and smaller than weekAgo one
Given the user runs the symfony:reports:recalculate command

Given the user opens the authorization page
When the user logs in using 'storeManager-s25u574' userName and 'lighthouse' password
And the user clicks the menu report item
And the user clicks on gross sale by products report link
When the user clicks the catalog item with name 'defaultGroup-s25u574'
And the user clicks the catalog item with name 'defaultCategory-s25u574'
And the user clicks the catalog item with name 'defaultSubCategory-s25u574'
Then the user checks the table today value by locator 'name-255742' is not red highlighted

Scenario: Gross sale by products contains data red highlighted (todayValue is smaller than yesterday/weekAgo)

Meta:
@id_s25u57.4s8
@description_@smoke

GivenStories: precondition/sprint-25/us-57_4/aPreconditionToScenarioS4.story

Given the user prepares data for red highlighted checks - today data is smaller than yesterday and weekAgo one
Given the user runs the symfony:reports:recalculate command

Given the user opens the authorization page
When the user logs in using 'storeManager-s25u574' userName and 'lighthouse' password
And the user clicks the menu report item
And the user clicks on gross sale by products report link
When the user clicks the catalog item with name 'defaultGroup-s25u574'
And the user clicks the catalog item with name 'defaultCategory-s25u574'
And the user clicks the catalog item with name 'defaultSubCategory-s25u574'
Then the user checks the table today value by locator 'name-255742' is red highlighted
