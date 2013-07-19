23 Создание/редактирование/просмотр отделов магазина

Narrative:
Как комерческий директор
Я хочу создавать отделы в магазине
Что бы управлять ассортиментом магазинов

Meta:
@sprint 14
@us 23
@debug us:23:validation

Scenario: Invalid store number

Given there is created department and user starts to edit it and fills form with
| elementName | value |
| number | @ $% |
Then the user sees error messages
| error message |
| Значение недопустимо |
When the user logs out

Scenario: Empty department number

Given there is created department and user starts to edit it and fills form with
| elementName | value |
| number |  |
Then the user sees error messages
| error message |
| Заполните это поле |
When the user logs out

Scenario: Too long department number

Given there is created department and user starts to edit it and fills form with
| elementName | value | repeat |
| number | 0123456789 | 6 |
Then the user sees error messages
| error message |
| Не более 50 символов |
When the user logs out

Scenario: Duplicate department number

Given created default store with department 'departmentExists', 'departmentExists name'
When the user logs out
Given there is created department and user starts to edit it and fills form with
| elementName | value |
| number | departmentExists |
Then the user sees error messages
| error message |
| Отдел с таким названием уже существует в этом магазине |
When the user logs out

Scenario: Empty department name

Given there is created department and user starts to edit it and fills form with
| elementName | value |
| name | |
Then the user sees error messages
| error message |
| Заполните это поле |
When the user logs out

Scenario: Too long department name

Given there is created department and user starts to edit it and fills form with
| elementName | value | repeat |
| name | Миру - мир  | 31 |
Then the user sees error messages
| error message |
| Не более 100 символов |
When the user logs out
