

Scenario: edit mode name field length validation 100 symbols
Given there is the user with name 'User validation', position 'User validation', username 'emnf1', password 'password', role 'commercialManager'
And the user navigates to the user page with username 'emnf1'
And the user logs in as 'watchman'
When the user clicks the edit button on the user card page
And the user generates charData with '100' number in the 'name' user page field
Then the user checks 'name' user page field contains only '100' symbols
When the user clicks the create new user button
Then the user sees no error messages
When the user logs out

Scenario: edit mode name field length validation negative 101 symbols
Given there is the user with name 'User validation', position 'User validation', username 'emnf2', password 'password', role 'commercialManager'
And the user navigates to the user page with username 'emnf2'
And the user logs in as 'watchman'
When the user clicks the edit button on the user card page
And the user generates charData with '101' number in the 'name' user page field
Then the user checks 'name' user page field contains only '101' symbols
When the user clicks the create new user button
Then the user sees error messages
| error message |
| Не более 100 символов |
When the user logs out

Scenario: edit mode name field is required
Given there is the user with name 'User validation', position 'User validation', username 'emnf3', password 'password', role 'commercialManager'
And the user navigates to the user page with username 'emnf3'
And the user logs in as 'watchman'
When the user clicks the edit button on the user card page
When the user inputs values in the user page element fields
| elementName | value |
| name |  |
When the user clicks the create new user button
Then the user sees error messages
| error message |
| Заполните это поле |
When the user logs out

Scenario: edit mode position field length validation 100 symbols
Given there is the user with name 'User validation', position 'User validation', username 'emnf4', password 'password', role 'commercialManager'
And the user navigates to the user page with username 'emnf4'
And the user logs in as 'watchman'
When the user clicks the edit button on the user card page
And the user generates charData with '100' number in the 'position' user page field
Then the user checks 'position' user page field contains only '100' symbols
When the user clicks the create new user button
Then the user sees no error messages
When the user logs out

Scenario: edit mode position field length validation negative 101 symbols
Given there is the user with name 'User validation', position 'User validation', username 'emnf5', password 'password', role 'commercialManager'
And the user navigates to the user page with username 'emnf5'
And the user logs in as 'watchman'
When the user clicks the edit button on the user card page
And the user generates charData with '101' number in the 'position' user page field
Then the user checks 'position' user page field contains only '101' symbols
When the user clicks the create new user button
Then the user sees error messages
| error message |
| Не более 100 символов |
When the user logs out

Scenario: edit mode position field is required
Given there is the user with name 'User validation', position 'User validation', username 'emnf6', password 'password', role 'commercialManager'
And the user navigates to the user page with username 'emnf6'
And the user logs in as 'watchman'
When the user clicks the edit button on the user card page
And the user inputs values in the user page element fields
| elementName | value |
| position | |
When the user clicks the create new user button
Then the user sees error messages
| error message |
| Заполните это поле |
When the user logs out

Scenario: edit mode role field is required
Given there is the user with name 'User validation', position 'User validation', username 'emnf7', password 'password', role 'commercialManager'
And the user navigates to the user page with username 'emnf7'
And the user logs in as 'watchman'
When the user clicks the edit button on the user card page
And the user inputs values in the user page element fields
| elementName | value |
| role | |
When the user clicks the create new user button
Then the user sees error messages
| error message |
| Заполните это поле |
When the user logs out

Scenario: edit mode password minimum length validation invalid
Given there is the user with name 'User validation', position 'User validation', username 'emnf8', password 'password', role 'commercialManager'
And the user navigates to the user page with username 'emnf8'
And the user logs in as 'watchman'
When the user clicks the edit button on the user card page
When the user inputs values in the user page element fields
| elementName | value |
| password | 12345 |
And the user clicks the create new user button
Then the user sees error messages
| error message |
| Значение слишком короткое. Должно быть равно 6 символам или больше. |
When the user logs out

Scenario: edit mode password minimum length validation positive
Given there is the user with name 'User validation', position 'User validation', username 'emnf9', password 'password', role 'commercialManager'
And the user navigates to the user page with username 'emnf9'
And the user logs in as 'watchman'
When the user clicks the edit button on the user card page
When the user inputs values in the user page element fields
| elementName | value |
| password | 123456 |
And the user clicks the create new user button
Then the user sees no error messages
When the user logs out

Scenario: edit mode password must be not equal to username
Given there is the user with name 'User validation', position 'User validation', username 'emnf10', password 'password', role 'commercialManager'
And the user navigates to the user page with username 'emnf10'
And the user logs in as 'watchman'
When the user clicks the edit button on the user card page
When the user inputs values in the user page element fields
| elementName | value |
| username | pmbnetl23 |
| password | pmbnetl23 |
And the user clicks the create new user button
Then the user sees error messages
| error message |
| Логин и пароль не должны совпадать |
When the user logs out

Scenario: edit mode password validation positive
Given there is the user with name 'User validation', position 'User validation', username 'emnf11', password 'password', role 'commercialManager'
And the user navigates to the user page with username 'emnf11'
And the user logs in as 'watchman'
When the user clicks the edit button on the user card page
When the user inputs values in the user page element fields
| elementName | value |
| password | ФыEf3!@$#$$%() |
And the user clicks the create new user button
Then the user sees no error messages
When the user logs out

Scenario: edit mode username field length validation 100 symbols
Given there is the user with name 'User validation', position 'User validation', username 'emnf12', password 'password', role 'commercialManager'
And the user navigates to the user page with username 'emnf12'
And the user logs in as 'watchman'
When the user clicks the edit button on the user card page
And the user generates charData with '100' number without spaces and 'c' char in the 'username' user page field
Then the user checks 'username' user page field contains only '100' symbols
When the user clicks the create new user button
Then the user sees no error messages
When the user logs out

Scenario: edit mode username field length validation negative 101 symbols
Given there is the user with name 'User validation', position 'User validation', username 'emnf13', password 'password', role 'commercialManager'
And the user navigates to the user page with username 'emnf13'
And the user logs in as 'watchman'
When the user clicks the edit button on the user card page
And the user generates charData with '101' number without spaces and 'b' char in the 'username' user page field
Then the user checks 'username' user page field contains only '101' symbols
When the user clicks the create new user button
Then the user sees error messages
| error message |
| Не более 100 символов |
When the user logs out

Scenario: edit mode username must be unique
Given there is the user with name 'User validation lmbu', position 'User validation lmbu', username 'Uservalidationlmbu', password 'Uservalidationlmbu1', role 'commercialManager'
And there is the user with name 'User validation', position 'User validation', username 'emnf14', password 'password', role 'commercialManager'
And the user navigates to the user page with username 'emnf14'
And the user logs in as 'watchman'
When the user clicks the edit button on the user card page
And the user inputs values in the user page element fields
| elementName | value |
| username | Uservalidationlmbu |
And the user clicks the create new user button
Then the user sees error messages
| error message |
| Пользователь с таким логином уже существует |
When the user logs out

Scenario: edit mode username is required
Given there is the user with name 'User validation', position 'User validation', username 'emnf15', password 'password', role 'commercialManager'
And the user navigates to the user page with username 'emnf15'
And the user logs in as 'watchman'
When the user clicks the edit button on the user card page
And the user inputs values in the user page element fields
| elementName | value |
| username | |
And the user clicks the create new user button
Then the user sees error messages
| error message |
| Заполните это поле |
When the user logs out

Scenario: edit mode username validation positive Rus Big regiser
Given there is the user with name 'User validation', position 'User validation', username 'emnf16', password 'password', role 'commercialManager'
And the user navigates to the user page with username 'emnf16'
And the user logs in as 'watchman'
When the user clicks the edit button on the user card page
And the user inputs values in the user page element fields
| elementName | value |
| username | РУССКИЙЙ |
And the user clicks the create new user button
Then the user sees no error messages
When the user logs out

Scenario: edit mode username validation positive Rus Small register
Given there is the user with name 'User validation', position 'User validation', username 'emnf17', password 'password', role 'commercialManager'
And the user navigates to the user page with username 'emnf17'
And the user logs in as 'watchman'
When the user clicks the edit button on the user card page
And the user inputs values in the user page element fields
| elementName | value |
| username | руссскийй |
And the user clicks the create new user button
Then the user sees no error messages
When the user logs out

Scenario: edit mode username validation positive Eng Small register
Given there is the user with name 'User validation', position 'User validation', username 'emnf19', password 'password', role 'commercialManager'
And the user navigates to the user page with username 'emnf19'
And the user logs in as 'watchman'
When the user clicks the edit button on the user card page
And the user inputs values in the user page element fields
| elementName | value |
| username | ENGLISHHH |
And the user clicks the create new user button
Then the user sees no error messages
When the user logs out

Scenario: edit mode username validation positive Eng Big register
Given there is the user with name 'User validation', position 'User validation', username 'emnf20', password 'password', role 'commercialManager'
And the user navigates to the user page with username 'emnf20'
And the user logs in as 'watchman'
When the user clicks the edit button on the user card page
And the user inputs values in the user page element fields
| elementName | value |
| username | englishhh |
And the user clicks the create new user button
Then the user sees no error messages
When the user logs out

Scenario: edit mode username validation positive digits 0-9
Given there is the user with name 'User validation', position 'User validation', username 'emnf21', password 'password', role 'commercialManager'
And the user navigates to the user page with username 'emnf21'
And the user logs in as 'watchman'
When the user clicks the edit button on the user card page
When the user inputs values in the user page element fields
| elementName | value |
| username | 12345678901 |
And the user clicks the create new user button
Then the user sees no error messages
When the user logs out

Scenario: edit mode username validation positive symbols _
Given there is the user with name 'User validation', position 'User validation', username 'emnf22', password 'password', role 'commercialManager'
And the user navigates to the user page with username 'emnf22'
And the user logs in as 'watchman'
When the user clicks the edit button on the user card page
And the user inputs values in the user page element fields
| elementName | value |
| username | username_usernamee |
And the user clicks the create new user button
Then the user sees no error messages
When the user logs out

Scenario: edit mode username validation positive symbols -
Given there is the user with name 'User validation', position 'User validation', username 'emnf23', password 'password', role 'commercialManager'
And the user navigates to the user page with username 'emnf23'
And the user logs in as 'watchman'
When the user clicks the edit button on the user card page
And the user inputs values in the user page element fields
| elementName | value |
| username | username-usernamee |
And the user clicks the create new user button
Then the user sees no error messages
When the user logs out

Scenario: edit mode username validation positive symbols .
Given there is the user with name 'User validation', position 'User validation', username 'emnf24', password 'password', role 'commercialManager'
And the user navigates to the user page with username 'emnf24'
And the user logs in as 'watchman'
When the user clicks the edit button on the user card page
And the user inputs values in the user page element fields
| elementName | value |
| username | username.usernamee |
And the user clicks the create new user button
Then the user sees no error messages
When the user logs out

Scenario: edit mode username validation positive symbols @
Given there is the user with name 'User validation', position 'User validation', username 'emnf25', password 'password', role 'commercialManager'
And the user navigates to the user page with username 'emnf25'
And the user logs in as 'watchman'
When the user clicks the edit button on the user card page
And the user inputs values in the user page element fields
| elementName | value |
| username | username@usernamee |
And the user clicks the create new user button
Then the user sees no error messages
When the user logs out

Scenario: edit mode username validation positive symbols mix
Given there is the user with name 'User validation', position 'User validation', username 'emnf26', password 'password', role 'commercialManager'
And the user navigates to the user page with username 'emnf26'
And the user logs in as 'watchman'
When the user clicks the edit button on the user card page
And the user inputs values in the user page element fields
| elementName | value |
| username | username_username-username@gmaik.comm |
And the user clicks the create new user button
Then the user sees no error messages
When the user logs out

Scenario: edit mode username validation negative symbols
Given there is the user with name 'User validation', position 'User validation', username 'emnf27', password 'password', role 'commercialManager'
And the user navigates to the user page with username 'emnf27'
And the user logs in as 'watchman'
When the user clicks the edit button on the user card page
And the user inputs values in the user page element fields
| elementName | value |
| username | ~!@#$%^&*()+= |
And the user clicks the create new user button
Then the user sees error messages
| error message |
| Значение недопустимо |
When the user logs out

Scenario: edit mode username validation negative symbols white space
Given there is the user with name 'User validation', position 'User validation', username 'emnf28', password 'password', role 'commercialManager'
And the user navigates to the user page with username 'emnf28'
And the user logs in as 'watchman'
When the user clicks the edit button on the user card page
And the user inputs values in the user page element fields
| elementName | value |
| username | username username |
And the user clicks the create new user button
Then the user sees error messages
| error message |
| Значение недопустимо |
When the user logs out

