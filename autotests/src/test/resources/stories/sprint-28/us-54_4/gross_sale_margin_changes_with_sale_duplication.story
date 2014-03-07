Meta:
@sprint_28
@us_54.4

Narrative:
As a директор магазина
I want to чтобы расчет валовой прибыли магазина учитывал ситуация, когда во фронтальной системе обнаружили, исправили ошибку в ранее пробитых чеках
In order to чтобы данные о валовой прибыли были правдивы

Scenario: Gross sale margin recalculation with sale duplication product

Meta:
@id_s28u54.4s1
@description_@smoke

GivenStories: precondition/sprint-28/us-54_4/aPreconditionToStoryUs54.4.story

Given there is the product with 'name-285441' name, '285441' sku, '285441' barcode, 'unit' units, '100' purchasePrice of group named 'defaultGroup-s28u544', category named 'defaultCategory-s28u544', subcategory named 'defaultSubCategory-s28u544'
And there is the date invoice with sku 'Invoice-28544-2' and date 'today-1days' and time set to '8:00:00' in the store with number '28544' ruled by department manager with name 'departmentManager-s28u544'
And the user adds the product to the invoice with name 'Invoice-28544-2' with sku '285441', quantity '5', price '100' in the store ruled by 'departmentManager-s28u544'

Given the user opens the authorization page

When the user logs in using 'storeManager-s28u544' userName and 'lighthouse' password
And the user clicks the menu report item
And the user clicks on store gross sale margin report link

Then the user checks the gross sale margin table contains expected value entries for story 54.4

Given the user prepares yesterday purchases duplication with updated product for us 54.4 story
And the user runs the symfony:reports:recalculate command
And the user refreshes the current page

Then the user checks the gross sale margin table contains expected value entries after sale duplication with updated product is registered for story 54.4

Scenario: Gross sale margin recalculation with sale duplication price

Meta:
@id_s28u54.4s2
@description_@smoke

GivenStories: precondition/sprint-28/us-54_4/aPreconditionToStoryUs54.4.story

Given the user opens the authorization page

When the user logs in using 'storeManager-s28u544' userName and 'lighthouse' password
And the user clicks the menu report item
And the user clicks on store gross sale margin report link

Then the user checks the gross sale margin table contains expected value entries for story 54.4

Given the user prepares yesterday purchases duplication with updated price for us 54.4 story
And the user runs the symfony:reports:recalculate command
And the user refreshes the current page

Then the user checks the gross sale margin table contains expected value entries after sale duplication with updated price is registered for story 54.4

