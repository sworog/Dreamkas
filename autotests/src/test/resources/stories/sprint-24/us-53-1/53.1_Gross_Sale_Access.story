Meta:
@sprint 24
@us 53.1

Narrative:
As a директор магазина
I want to знать сумму продаж своего магазина на этот час в сравнении с суммой продаж на этот же час вчера и неделю назад
In order to оперативно отследить провалы в продажах и успеть принять меры

Scenario: No store gross sale for administrator

Meta:
@id s24u53.1s15
@description checks the store gross sale is not available for administrator

Given the user opens the authorization page
And the user logs in as 'watchman'
Then the user checks the gross sale is not available

Scenario: No store gross sale for commercialManager

Meta:
@id s24u53.1s16
@description checks the store gross sale is not available for commercialManager

Given the user opens the authorization page
And the user logs in as 'commercialManager'
Then the user checks the gross sale is not available

Scenario: No store gross sale for departmentManager

Meta:
@id s24u53.1s17
@description checks the store gross sale is not available for departmentManager

Given the user opens the authorization page
And the user logs in as 'departmentManager'
Then the user checks the gross sale is not available