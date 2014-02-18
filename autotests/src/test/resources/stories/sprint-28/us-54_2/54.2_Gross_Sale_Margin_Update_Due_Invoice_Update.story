Meta:
@sprint_28
@us_54.2

Narrative:
As a директор магазина
I want to чтобы валовая прибыль рассчитывалась с учетом отредактированной накладной
In order to чтобы данные о валовой прибыли были правдивы

Scenario: Gross sale margin update due invoice product quantity update

Meta:
@id_s28u54.2s1
@description_@smoke

GivenStories: precondition/sprint-28/us-54_4/aPreconditionToStoryUs54.4.story

Given the user opens the authorization page

When the user logs in using 'storeManager-s28u544' userName and 'lighthouse' password
And the user clicks the menu report item
And the user clicks on store gross sale margin report link

Then the user checks the gross sale margin table contains expected value entries for story 54.4

When the user logs out

Given the user navigates to the invoice page with name 'Invoice-28544-1'
When the user logs in using 'departmentManager-s28u544' userName and 'lighthouse' password

When the user clicks on property named 'productAmount' of invoice product named '28544'
And the user inputs the value '8' in property named 'productAmount' of invoice product named '28544'
And the user clicks OK and accepts changes

When the user logs out

Given the user runs the symfony:reports:recalculate command
Given the user opens the authorization page

When the user logs in using 'storeManager-s28u544' userName and 'lighthouse' password
And the user clicks the menu report item
And the user clicks on store gross sale margin report link

Then the user checks the gross sale margin table contains expected value entries after invoice product quantity is updated for story 54.2

Scenario: Gross sale margin update due invoice product price update

Meta:
@id_s28u54.2s2
@description_@smoke

GivenStories: precondition/sprint-28/us-54_4/aPreconditionToStoryUs54.4.story

Given the user opens the authorization page

When the user logs in using 'storeManager-s28u544' userName and 'lighthouse' password
And the user clicks the menu report item
And the user clicks on store gross sale margin report link

Then the user checks the gross sale margin table contains expected value entries for story 54.4

When the user logs out

Given the user navigates to the invoice page with name 'Invoice-28544'
When the user logs in using 'departmentManager-s28u544' userName and 'lighthouse' password

When the user clicks on property named 'productPrice' of invoice product named '28544'
And the user inputs the value '100' in property named 'productPrice' of invoice product named '28544'
And the user clicks OK and accepts changes

When the user logs out

Given the user runs the symfony:reports:recalculate command
Given the user opens the authorization page

When the user logs in using 'storeManager-s28u544' userName and 'lighthouse' password
And the user clicks the menu report item
And the user clicks on store gross sale margin report link

Then the user checks the gross sale margin table contains expected value entries after invoice product price is updated for story 54.2

Scenario: Gross sale margin update due invoice product deletion

Meta:
@id_s28u54.2s3
@description_@smoke

GivenStories: precondition/sprint-28/us-54_4/aPreconditionToStoryUs54.4.story

Given the user opens the authorization page

When the user logs in using 'storeManager-s28u544' userName and 'lighthouse' password
And the user clicks the menu report item
And the user clicks on store gross sale margin report link

Then the user checks the gross sale margin table contains expected value entries for story 54.4

When the user logs out

Given the user navigates to the invoice page with name 'Invoice-28544-1'
When the user logs in using 'departmentManager-s28u544' userName and 'lighthouse' password

And the user deletes the invoice product with '28544' sku
And the user clicks OK and accepts deletion

When the user logs out

Given the user runs the symfony:reports:recalculate command
Given the user opens the authorization page

When the user logs in using 'storeManager-s28u544' userName and 'lighthouse' password
And the user clicks the menu report item
And the user clicks on store gross sale margin report link

Then the user checks the gross sale margin table contains expected value entries after invoice product deletion for story 54.2

Scenario: Gross sale margin update due invoice date update to the past

Meta:
@id_s28u54.2s4
@description_@smoke

GivenStories: precondition/sprint-28/us-54_4/aPreconditionToStoryUs54.4.story

Given the user opens the authorization page

When the user logs in using 'storeManager-s28u544' userName and 'lighthouse' password
And the user clicks the menu report item
And the user clicks on store gross sale margin report link

Then the user checks the gross sale margin table contains expected value entries for story 54.4

When the user logs out

Given the user navigates to the invoice page with name 'Invoice-28544-1'
When the user logs in using 'departmentManager-s28u544' userName and 'lighthouse' password

And the user clicks on 'acceptanceDate' element to edit it
And the user inputs 'todayDate-3days' in the invoice 'inline acceptanceDate' field
When the user clicks OK and accepts changes

When the user logs out

Given the user runs the symfony:reports:recalculate command
Given the user opens the authorization page

When the user logs in using 'storeManager-s28u544' userName and 'lighthouse' password
And the user clicks the menu report item
And the user clicks on store gross sale margin report link

Then the user checks the gross sale margin table contains expected value entries after invoice date is updated for story 54.2

Scenario: Gross sale margin update due invoice date update to the future

Meta:
@id_s28u54.2s5
@description_@smoke

GivenStories: precondition/sprint-28/us-54_4/aPreconditionToStoryUs54.4.story

Given the user opens the authorization page

When the user logs in using 'storeManager-s28u544' userName and 'lighthouse' password
And the user clicks the menu report item
And the user clicks on store gross sale margin report link

Then the user checks the gross sale margin table contains expected value entries for story 54.4

When the user logs out

Given the user navigates to the invoice page with name 'Invoice-28544-1'
When the user logs in using 'departmentManager-s28u544' userName and 'lighthouse' password

And the user clicks on 'acceptanceDate' element to edit it
And the user inputs 'todayDateAndTime' in the invoice 'inline acceptanceDate' field
When the user clicks OK and accepts changes

When the user logs out

Given the user runs the symfony:reports:recalculate command
Given the user opens the authorization page

When the user logs in using 'storeManager-s28u544' userName and 'lighthouse' password
And the user clicks the menu report item
And the user clicks on store gross sale margin report link

Then the user checks the gross sale margin table contains expected value entries for story 54.4



