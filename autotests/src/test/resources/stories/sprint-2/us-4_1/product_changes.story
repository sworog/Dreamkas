Изменения в истории создании продукта

Meta:
@sprint_2
@us_4.1

Scenario: Verifying that there is no default values for units

Given the user is on the product create page
And the user logs in as 'commercialManager'
Then the user checks default value for 'vat' dropdown equal to ''

Scenario: Verifying that there is no default values for vats

Given the user is on the product create page
And the user logs in as 'commercialManager'
Then the user checks default value for 'vat' dropdown equal to ''