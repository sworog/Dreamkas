Meta:
@sprint_37
@us_80.1

Narrative:
Как владелец торговой точки,
Я хочу добавить в систему несколько организаций моей торговой компании,
Что бы в дальнейшем использовать эти данные в документах

Scenario: Create organization

Meta:
@smoke

Given user is on company page
And the user logs in as 'owner'
When user clicks create new organization link
And user fill organization inputs
| elementName | value |
| name | Организация |
| phone | +79211315542 |
| fax | +732565845 |
| email | email@email.com |
| director | Василий пупкинович |
| chiefAccountant | Карлова Евгения |
| address | Новопупкино, ул. Смородская, д. 53 |
And user clicks organization form create button
Then the user sees no error messages
When user clicks to organization in list with name 'Организация'
Then user checks organization fields data


Scenario: Validation create organization not require name field

Given user is on create organization page
And the user logs in as 'owner'
When user fill organization inputs
 | elementName | value |
 | phone | +79211315542 |
 | fax | +732565845 |
 | email | email@email.com |
 | director | Василий пупкинович |
 | chiefAccountant | Карлова Евгения |
 | address | Новопупкино, ул. Смородская, д. 53 |
And user clicks organization form create button
Then user checks organization form the element field 'name' has error message 'Заполните это поле'

Scenario: Validation create organization long value

Given user is on create organization page
And the user logs in as 'owner'
When user fill organization inputs
 | elementName | value |
 | name | 123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567_301 |
 | phone | 123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567_301 |
 | fax | 123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567_301 |
 | email | 123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567_301 |
 | director | 123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567_301 |
 | chiefAccountant | 123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567_301 |
 | address | 123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567_301 |
And user clicks organization form create button
Then user checks organization form the element field 'name' has error message 'Не более 300 символов'
And user checks organization form the element field 'phone' has error message 'Не более 300 символов'
And user checks organization form the element field 'fax' has error message 'Не более 300 символов'
And user checks organization form the element field 'email' has error message 'Не более 300 символов'
And user checks organization form the element field 'director' has error message 'Не более 300 символов'
And user checks organization form the element field 'chiefAccountant' has error message 'Не более 300 символов'
And user checks organization form the element field 'address' has error message 'Не более 300 символов'

Scenario: Edit organization

Meta:
@smoke

Given user is on company page
And the user logs in as 'owner'
When user clicks create new organization link
And user fill organization inputs
| elementName | value |
| name | OrganizationEdit |
| phone | +77777777 |
| fax | +77777776 |
| email | email@email.com |
| director | Василий пупкинович |
| chiefAccountant | Карлова Евгения |
| address | Новопупкино, ул. Смородская, д. 53 |
And user clicks organization form create button
Then the user sees no error messages
When user clicks to organization in list with name 'OrganizationEdit'
Then user checks organization fields data
When user fill organization inputs
| elementName | value |
| name | OrganizationEdited |
| phone | +66666666 |
| fax | +66666667 |
| email | email@new.com |
| director | Кирилл Нормальнов |
| chiefAccountant | Женя Пупова |
| address | Кореандр, ул. Смородская, д. 53 |
And user clicks organization form edit button
Then the user sees no error messages
And the user sees success message 'Данные организации успешно сохранены'

Scenario: Edit organization validation not require name field

Given user is on company page
And the user logs in as 'owner'
When user clicks create new organization link
And user fill organization inputs
| elementName | value |
| name | OrganizationValidationRequire |
| phone | +77777777 |
| fax | +77777776 |
| email | email@email.com |
| director | Василий пупкинович |
| chiefAccountant | Карлова Евгения |
| address | Новопупкино, ул. Смородская, д. 53 |
And user clicks organization form create button
Then the user sees no error messages
When user clicks to organization in list with name 'OrganizationValidationRequire'
Then user checks organization fields data
When user fill organization inputs
| elementName | value |
| name | |
| phone | +66666666 |
| fax | +66666667 |
| email | email@new.com |
| director | Кирилл Нормальнов |
| chiefAccountant | Женя Пупова |
| address | Кореандр, ул. Смородская, д. 53 |
And user clicks organization form edit button
Then user checks organization form the element field 'name' has error message 'Заполните это поле'

Scenario: Edit organization validation lenght

Given user is on company page
And the user logs in as 'owner'
When user clicks create new organization link
And user fill organization inputs
| elementName | value |
| name | OrganizationValidationLength |
| phone | +77777777 |
| fax | +77777776 |
| email | email@email.com |
| director | Василий пупкинович |
| chiefAccountant | Карлова Евгения |
| address | Новопупкино, ул. Смородская, д. 53 |
And user clicks organization form create button
Then the user sees no error messages
When user clicks to organization in list with name 'OrganizationValidationLength'
Then user checks organization fields data
When user fill organization inputs
 | elementName | value |
 | name | 123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567_301 |
 | phone | 123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567_301 |
 | fax | 123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567_301 |
 | email | 123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567_301 |
 | director | 123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567_301 |
 | chiefAccountant | 123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567_301 |
 | address | 123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567_301 |
And user clicks organization form edit button
Then user checks organization form the element field 'name' has error message 'Не более 300 символов'
And user checks organization form the element field 'phone' has error message 'Не более 300 символов'
And user checks organization form the element field 'fax' has error message 'Не более 300 символов'
And user checks organization form the element field 'email' has error message 'Не более 300 символов'
And user checks organization form the element field 'director' has error message 'Не более 300 символов'
And user checks organization form the element field 'chiefAccountant' has error message 'Не более 300 символов'
And user checks organization form the element field 'address' has error message 'Не более 300 символов'
