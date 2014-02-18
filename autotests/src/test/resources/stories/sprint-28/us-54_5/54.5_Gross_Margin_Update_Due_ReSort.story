Meta:
@sprint_28
@us_54.5

Narrative:
As a директор магазина
I want to чтобы расчет валовой прибыли все равно производился если в магазине произошел пересорт
In order to чтобы ситуация с пересортом оказала минимальное влияние на процессы в магазине

Scenario: Gross sale margin update due reSort if earlier there were invoices

Meta:
@id_s28u54.5s1
@description_@smoke

GivenStories: precondition/sprint-28/us-54_5/aPreconditionToStoryUs54.5.story,
              precondition/sprint-28/us-54_5/aPreconditionToScenario1.story

Given the user opens the authorization page

When the user logs in using 'storeManager-s28u544' userName and 'lighthouse' password
And the user clicks the menu report item
And the user clicks on store gross sale margin report link

Then the user checks the gross sale margin table contains expected value entries if there were invoices and resort happened

Scenario: Gross sale margin update due reSort if earlier there were no invoices

Meta:
@id_s28u54.5s2
@description_@smoke

GivenStories: precondition/sprint-28/us-54_5/aPreconditionToStoryUs54.5.story,
              precondition/sprint-28/us-54_5/aPreconditionToScenario2.story

Given the user opens the authorization page

When the user logs in using 'storeManager-s28u544' userName and 'lighthouse' password
And the user clicks the menu report item
And the user clicks on store gross sale margin report link

Then the user checks the gross sale margin table contains expected value entries if there were no invoices and resort happened

