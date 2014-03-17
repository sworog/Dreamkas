Meta:
@sprint_30
@us_63
@order

Narrative:
Тесты на видимость

Scenario: Cannot view create order page navigation through link by commercialManager

Meta:
@id

Given the user opens order create page
And the user logs in as 'commercialManager'

Then the user sees the 403 error


Scenario: Cannot view create order page navigation through link by storeManager

Meta:
@id

Given the user opens order create page
And the user logs in as 'storeManager'

Then the user sees the 403 error

Scenario: Cannot view create order page navigation through link by administrator

Meta:
@id

Given the user opens order create page
And the user logs in as 'watchman'

Then the user sees the 403 error

Scenario: No orders menu navigation link for commercialManager

Meta:
@id

Given the user opens the authorization page
And the user logs in as 'commercialManager'

Then the user checks the orders navigation menu item is not visible

Scenario: No orders menu navigation link for storeManager

Meta:
@id

Given the user opens the authorization page
And the user logs in as 'storeManager'

Then the user checks the orders navigation menu item is not visible

Scenario: No orders menu navigation link for administrator

Meta:
@id

Given the user opens the authorization page
And the user logs in as 'watchman'

Then the user checks the orders navigation menu item is not visible
