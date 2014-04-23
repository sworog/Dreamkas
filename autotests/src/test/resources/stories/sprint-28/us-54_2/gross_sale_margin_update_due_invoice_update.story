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
@description
@smoke

GivenStories: precondition/sprint-28/us-54_4/aPreconditionToStoryUs54.4.story

Given the user opens the authorization page

When the user logs in using 'storeManager-s28u544' userName and 'lighthouse' password
And the user clicks the menu report item
And the user clicks on store gross sale margin report link

Then the user checks the gross sale margin table contains expected value entries for story 54.4

When the user logs out

Given the user opens last created invoice page
When the user logs in using 'departmentManager-s28u544' userName and 'lighthouse' password

When the user clicks on the invoice product by name 'name-28544'
And the user inputs quantity '8' on the invoice product with name 'name-28544'
And the user presses 'ENTER' key button

Then the user waits for the invoice product edition preloader finish

When the user accepts products and saves the invoice

When the user logs out

Given the user runs the symfony:reports:recalculate command

Given the user opens the authorization page
And the user logs in using 'storeManager-s28u544' userName and 'lighthouse' password

When the user clicks the menu report item
And the user clicks on store gross sale margin report link

Then the user checks the gross sale margin table contains expected value entries after invoice product quantity is updated for story 54.2

Scenario: Gross sale margin update due invoice product price update

Meta:
@id_s28u54.2s2
@description
@smoke

GivenStories: precondition/sprint-28/us-54_4/aPreconditionToStoryUs54.4.story

Given the user opens the authorization page

When the user logs in using 'storeManager-s28u544' userName and 'lighthouse' password
And the user clicks the menu report item
And the user clicks on store gross sale margin report link

Then the user checks the gross sale margin table contains expected value entries for story 54.4

When the user logs out

Given the user opens one invoice ago created invoice page
When the user logs in using 'departmentManager-s28u544' userName and 'lighthouse' password

When the user clicks on the invoice product by name 'name-28544'
And the user presses 'TAB' key button

Then the user waits for the invoice product edition preloader finish

When the user inputs price '100' on the invoice product with name 'name-28544'
And the user presses 'ENTER' key button

Then the user waits for the invoice product edition preloader finish

When the user accepts products and saves the invoice

When the user logs out

Given the user runs the symfony:reports:recalculate command

Given the user opens the authorization page
And the user logs in using 'storeManager-s28u544' userName and 'lighthouse' password

When the user clicks the menu report item
And the user clicks on store gross sale margin report link

Then the user checks the gross sale margin table contains expected value entries after invoice product price is updated for story 54.2

Scenario: Gross sale margin update due invoice product deletion

Meta:
@id_s28u54.2s3
@description
@smoke

GivenStories: precondition/sprint-28/us-54_4/aPreconditionToStoryUs54.4.story

Given the user opens the authorization page

When the user logs in using 'storeManager-s28u544' userName and 'lighthouse' password
And the user clicks the menu report item
And the user clicks on store gross sale margin report link

Then the user checks the gross sale margin table contains expected value entries for story 54.4

When the user logs out

Given the user opens last created invoice page
When the user logs in using 'departmentManager-s28u544' userName and 'lighthouse' password

When the user clicks on the invoice product by name 'name-28544'
And the user clicks on delete icon and deletes invoice product with name 'name-28544'

When the user accepts products and saves the invoice

When the user logs out

Given the user runs the symfony:reports:recalculate command

And the user opens the authorization page
And the user logs in using 'storeManager-s28u544' userName and 'lighthouse' password

When the user clicks the menu report item
And the user clicks on store gross sale margin report link

Then the user checks the gross sale margin table contains expected value entries after invoice product deletion for story 54.2

Scenario: Gross sale margin update due invoice date update to the past

Meta:
@id_s28u54.2s4
@description_
@smoke

GivenStories: precondition/sprint-28/us-54_4/aPreconditionToStoryUs54.4.story

Given the user opens the authorization page

When the user logs in using 'storeManager-s28u544' userName and 'lighthouse' password
And the user clicks the menu report item
And the user clicks on store gross sale margin report link

Then the user checks the gross sale margin table contains expected value entries for story 54.4

When the user logs out

Given the user opens last created invoice page
When the user logs in using 'departmentManager-s28u544' userName and 'lighthouse' password

When the user inputs values on invoice page
| elementName | value |
| acceptanceDate | todayDate-3days |

When the user accepts products and saves the invoice

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
@description
@smoke

GivenStories: precondition/sprint-28/us-54_4/aPreconditionToStoryUs54.4.story

Given the user opens the authorization page

When the user logs in using 'storeManager-s28u544' userName and 'lighthouse' password
And the user clicks the menu report item
And the user clicks on store gross sale margin report link

Then the user checks the gross sale margin table contains expected value entries for story 54.4

When the user logs out

Given the user opens last created invoice page
When the user logs in using 'departmentManager-s28u544' userName and 'lighthouse' password

When the user inputs values on invoice page
| elementName | value |
| acceptanceDate | todayDateAndTime |

When the user accepts products and saves the invoice

When the user logs out

Given the user runs the symfony:reports:recalculate command
Given the user opens the authorization page

When the user logs in using 'storeManager-s28u544' userName and 'lighthouse' password
And the user clicks the menu report item
And the user clicks on store gross sale margin report link

Then the user checks the gross sale margin table contains expected value entries for story 54.4



