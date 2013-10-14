Meta:
@us 41.1
@sprint 20

Narrative:
As a администратор
I want to я хочу настроить загрузку продаж из SR10 через визуальный интерфейс LH
In order to обеспечить обмен данных между фронтальной и учетной системами.

Scenario: Saving sales import settings

Given the user opens the settings page
And the user logs in as 'watchman'
When the user input values on the setting page
| elementName | value |
| set10-import-url | set10-import-url |
| set10-import-login | set10-import-login |
| set10-import-interval | 500 |
| set10-import-password | set10-import-password |
And the user clicks import save button on the setting page
Then the user sees success message 'Настройки успешно сохранены'
And the user checks the stored values on the setting page

Scenario: Saving sales import setting after refresh

Given the user opens the settings page
And the user logs in as 'watchman'
When the user input values on the setting page
| elementName | value |
| set10-import-url | set10-import-url |
| set10-import-login | set10-import-login |
| set10-import-interval | 500 |
| set10-import-password | set10-import-password |
And the user clicks import save button on the setting page
Then the user sees success message 'Настройки успешно сохранены'
When the user refreshes the current page
Then the user checks the stored values on the setting page