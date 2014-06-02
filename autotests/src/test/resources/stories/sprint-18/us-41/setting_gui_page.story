Meta:
@sprint_18
@us_41

Narrative:
As a администратор
I want to настроить интеграцию с SR10 через визуальный интерфейс LH
In order to обеспечить обмен данными между фронтальной и учетной системами

Scenario: Saving integration settings

Meta:
@smoke

Given the user opens the settings page
And the user logs in as 'watchman'
When the user input values on the setting page
| elementName | value |
| set10-integration-url | set10-integration-url |
| set10-integration-login | set10-integration-login |
| set10-integration-password | set10-integration-password |
And the user clicks save button on the setting page
Then the user sees success message 'Настройки успешно сохранены'
And the user checks the stored values on the setting page

Scenario: Saving setting after refresh

Given the user opens the settings page
And the user logs in as 'watchman'
When the user input values on the setting page
| elementName | value |
| set10-integration-url | set10-integration-url |
| set10-integration-login | set10-integration-login |
| set10-integration-password | set10-integration-password |
And the user clicks save button on the setting page
Then the user sees success message 'Настройки успешно сохранены'
When the user refreshes the current page
Then the user checks the stored values on the setting page

Scenario: Setting dashboard link is visible for administrator

Given the user opens the authorization page
And the user logs in as 'watchman'
Then the user checks the settings navigation menu item is visible

Scenario: Setting dashboard link is not visible for commercialManager

Given the user opens the authorization page
And the user logs in as 'commercialManager'
Then the user checks the settings navigation menu item is not visible

Scenario: Setting dashboard link is not visible for departmentManager

Given the user opens the authorization page
And the user logs in as 'departmentManager'
Then the user checks the settings navigation menu item is not visible

Scenario: Setting dashboard link is not visible for storeManager

Given the user opens the authorization page
And the user logs in as 'storeManager'
Then the user checks the settings navigation menu item is not visible

Scenario: Navigate to settings through the link by administrator

Given the user opens the settings page
And the user logs in as 'watchman'
Then the user dont see the 403 error

Scenario: Navigate to settings through the link by commercialManager

Given the user opens the settings page
And the user logs in as 'commercialManager'
Then the user sees the 403 error

Scenario: Navigate to settings through the link by departmentManager

Given the user opens the settings page
And the user logs in as 'departmentManager'
Then the user sees the 403 error

Scenario: Navigate to settings through the link by storeManager

Given the user opens the settings page
And the user logs in as 'storeManager'
Then the user sees the 403 error