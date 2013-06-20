

Scenario: edit mode name field length validation 100 symbols
Given there is the user with name 'User validation', position 'User validation', username 'emnf1', password 'password', role 'commercialManager'
And the user navigates to the user page with username 'emnf1'
When the user clicks the edit button on the user card page
And the user generates charData with '100' number in the 'name' user page field
Then the user checks 'name' user page field contains only '100' symbols
When the user clicks the create new user button
Then the user sees no error messages

Scenario: edit mode name field length validation negative 101 symbols
Given there is the user with name 'User validation', position 'User validation', username 'emnf2', password 'password', role 'commercialManager'
And the user navigates to the user page with username 'emnf2'
When the user clicks the edit button on the user card page
And the user generates charData with '101' number in the 'name' user page field
Then the user checks 'name' user page field contains only '101' symbols
When the user clicks the create new user button
Then the user sees error messages
| error message |
| Не более 100 символов |

Scenario: edit mode name field is required
Given there is the user with name 'User validation', position 'User validation', username 'emnf3', password 'password', role 'commercialManager'
And the user navigates to the user page with username 'emnf3'
When the user clicks the edit button on the user card page
When the user inputs values in the user page element fields
| elementName | inputText |
| name |  |
When the user clicks the create new user button
Then the user sees error messages
| error message |
| Заполните это поле |

Scenario: edit mode position field length validation 100 symbols
Given there is the user with name 'User validation', position 'User validation', username 'emnf4', password 'password', role 'commercialManager'
And the user navigates to the user page with username 'emnf4'
When the user clicks the edit button on the user card page
And the user generates charData with '100' number in the 'position' user page field
Then the user checks 'position' user page field contains only '100' symbols
When the user clicks the create new user button
Then the user sees no error messages

Scenario: edit mode position field length validation negative 101 symbols
Given there is the user with name 'User validation', position 'User validation', username 'emnf5', password 'password', role 'commercialManager'
And the user navigates to the user page with username 'emnf5'
When the user clicks the edit button on the user card page
And the user generates charData with '101' number in the 'position' user page field
Then the user checks 'position' user page field contains only '101' symbols
When the user clicks the create new user button
Then the user sees error messages
| error message |
| Не более 100 символов |

Scenario: edit mode position field is required
Given there is the user with name 'User validation', position 'User validation', username 'emnf6', password 'password', role 'commercialManager'
And the user navigates to the user page with username 'emnf6'
When the user clicks the edit button on the user card page
And the user inputs values in the user page element fields
| elementName | inputText |
| position | |
When the user clicks the create new user button
Then the user sees error messages
| error message |
| Заполните это поле |

Scenario: edit mode role field is required
Given there is the user with name 'User validation', position 'User validation', username 'emnf7', password 'password', role 'commercialManager'
And the user navigates to the user page with username 'emnf7'
When the user clicks the edit button on the user card page
And the user inputs values in the user page element fields
| elementName | inputText |
| role | |
When the user clicks the create new user button
Then the user sees error messages
| error message |
| Заполните это поле |

Scenario: edit mode password minimum length validation invalid
Given there is the user with name 'User validation', position 'User validation', username 'emnf8', password 'password', role 'commercialManager'
And the user navigates to the user page with username 'emnf8'
When the user clicks the edit button on the user card page
When the user inputs values in the user page element fields
| elementName | inputText |
| password | 12345 |
And the user clicks the create new user button
Then the user sees error messages
| error message |
| Значение слишком короткое. Должно быть равно 6 символам или больше. |

Scenario: edit mode password minimum length validation positive
Given there is the user with name 'User validation', position 'User validation', username 'emnf9', password 'password', role 'commercialManager'
And the user navigates to the user page with username 'emnf9'
When the user clicks the edit button on the user card page
When the user inputs values in the user page element fields
| elementName | inputText |
| password | 123456 |
And the user clicks the create new user button
Then the user sees no error messages

Scenario: edit mode password must be not equal to username
Given there is the user with name 'User validation', position 'User validation', username 'emnf10', password 'password', role 'commercialManager'
And the user navigates to the user page with username 'emnf10'
When the user clicks the edit button on the user card page
When the user inputs values in the user page element fields
| elementName | inputText |
| username | pmbnetl23 |
| password | pmbnetl23 |
And the user clicks the create new user button
Then the user sees error messages
| error message |
| Логин и пароль не должны совпадать |

Scenario: edit mode password validation positive
Given there is the user with name 'User validation', position 'User validation', username 'emnf11', password 'password', role 'commercialManager'
And the user navigates to the user page with username 'emnf11'
When the user clicks the edit button on the user card page
When the user inputs values in the user page element fields
| elementName | inputText |
| password | ФыEf3!@$#$$%() |
And the user clicks the create new user button
Then the user sees no error messages

Scenario: edit mode username field length validation 100 symbols
Given there is the user with name 'User validation', position 'User validation', username 'emnf12', password 'password', role 'commercialManager'
And the user navigates to the user page with username 'emnf12'
When the user clicks the edit button on the user card page
And the user generates charData with '100' number without spaces and 'c' char in the 'username' user page field
Then the user checks 'username' user page field contains only '100' symbols
When the user clicks the create new user button
Then the user sees no error messages

Scenario: edit mode username field length validation negative 101 symbols
Given there is the user with name 'User validation', position 'User validation', username 'emnf13', password 'password', role 'commercialManager'
And the user navigates to the user page with username 'emnf13'
When the user clicks the edit button on the user card page
And the user generates charData with '101' number without spaces and 'b' char in the 'username' user page field
Then the user checks 'username' user page field contains only '101' symbols
When the user clicks the create new user button
Then the user sees error messages
| error message |
| Не более 100 символов |

Scenario: edit mode username must be unique
Given there is the user with name 'User validation lmbu', position 'User validation lmbu', username 'Uservalidationlmbu', password 'Uservalidationlmbu1', role 'commercialManager'
And there is the user with name 'User validation', position 'User validation', username 'emnf14', password 'password', role 'commercialManager'
And the user navigates to the user page with username 'emnf14'
When the user clicks the edit button on the user card page
And the user inputs values in the user page element fields
| elementName | inputText |
| username | Uservalidationlmbu |
And the user clicks the create new user button
Then the user sees error messages
| error message |
| Пользователь с таким логином уже существует |

Scenario: edit mode username is required
Given there is the user with name 'User validation', position 'User validation', username 'emnf15', password 'password', role 'commercialManager'
And the user navigates to the user page with username 'emnf15'
When the user clicks the edit button on the user card page
And the user inputs values in the user page element fields
| elementName | inputText |
| username | |
And the user clicks the create new user button
Then the user sees error messages
| error message |
| Заполните это поле |

Scenario: edit mode username validation positive Rus Big regiser
Given there is the user with name 'User validation', position 'User validation', username 'emnf16', password 'password', role 'commercialManager'
And the user navigates to the user page with username 'emnf16'
When the user clicks the edit button on the user card page
And the user inputs values in the user page element fields
| elementName | inputText |
| username | РУССКИЙ |
And the user clicks the create new user button
Then the user sees no error messages

Scenario: edit mode username validation positive Rus Small register
Given there is the user with name 'User validation', position 'User validation', username 'emnf17', password 'password', role 'commercialManager'
And the user navigates to the user page with username 'emnf17'
When the user clicks the edit button on the user card page
And the user inputs values in the user page element fields
| elementName | inputText |
| username | руссский |
And the user clicks the create new user button
Then the user sees no error messages

Scenario: edit mode username validation positive Eng Small register
Given there is the user with name 'User validation', position 'User validation', username 'emnf19', password 'password', role 'commercialManager'
And the user navigates to the user page with username 'emnf19'
When the user clicks the edit button on the user card page
And the user inputs values in the user page element fields
| elementName | inputText |
| username | ENGLISH |
And the user clicks the create new user button
Then the user sees no error messages

Scenario: edit mode username validation positive Eng Big register
Given there is the user with name 'User validation', position 'User validation', username 'emnf20', password 'password', role 'commercialManager'
And the user navigates to the user page with username 'emnf20'
When the user clicks the edit button on the user card page
And the user inputs values in the user page element fields
| elementName | inputText |
| username | englishh |
And the user clicks the create new user button
Then the user sees no error messages

Scenario: edit mode username validation positive digits 0-9
Given there is the user with name 'User validation', position 'User validation', username 'emnf21', password 'password', role 'commercialManager'
And the user navigates to the user page with username 'emnf21'
When the user clicks the edit button on the user card page
When the user inputs values in the user page element fields
| elementName | inputText |
| username | 1234567890 |
And the user clicks the create new user button
Then the user sees no error messages

Scenario: edit mode username validation positive symbols _
Given there is the user with name 'User validation', position 'User validation', username 'emnf22', password 'password', role 'commercialManager'
And the user navigates to the user page with username 'emnf22'
When the user clicks the edit button on the user card page
And the user inputs values in the user page element fields
| elementName | inputText |
| username | username_username |
And the user clicks the create new user button
Then the user sees no error messages

Scenario: edit mode username validation positive symbols -
Given there is the user with name 'User validation', position 'User validation', username 'emnf23', password 'password', role 'commercialManager'
And the user navigates to the user page with username 'emnf23'
When the user clicks the edit button on the user card page
And the user inputs values in the user page element fields
| elementName | inputText |
| username | username-username |
And the user clicks the create new user button
Then the user sees no error messages

Scenario: edit mode username validation positive symbols .
Given there is the user with name 'User validation', position 'User validation', username 'emnf24', password 'password', role 'commercialManager'
And the user navigates to the user page with username 'emnf24'
When the user clicks the edit button on the user card page
And the user inputs values in the user page element fields
| elementName | inputText |
| username | username.username |
And the user clicks the create new user button
Then the user sees no error messages

Scenario: edit mode username validation positive symbols @
Given there is the user with name 'User validation', position 'User validation', username 'emnf25', password 'password', role 'commercialManager'
And the user navigates to the user page with username 'emnf25'
When the user clicks the edit button on the user card page
And the user inputs values in the user page element fields
| elementName | inputText |
| username | username@username |
And the user clicks the create new user button
Then the user sees no error messages

Scenario: edit mode username validation positive symbols mix
Given there is the user with name 'User validation', position 'User validation', username 'emnf26', password 'password', role 'commercialManager'
And the user navigates to the user page with username 'emnf26'
When the user clicks the edit button on the user card page
And the user inputs values in the user page element fields
| elementName | inputText |
| username | username_username-username@gmaik.com |
And the user clicks the create new user button
Then the user sees no error messages

Scenario: edit mode username validation negative symbols
Given there is the user with name 'User validation', position 'User validation', username 'emnf27', password 'password', role 'commercialManager'
And the user navigates to the user page with username 'emnf27'
When the user clicks the edit button on the user card page
And the user inputs values in the user page element fields
| elementName | inputText |
| username | ~!@#$%^&*()+= |
And the user clicks the create new user button
Then the user sees error messages
| error message |
| Значение недопустимо |

Scenario: edit mode username validation negative symbols white space
Given there is the user with name 'User validation', position 'User validation', username 'emnf28', password 'password', role 'commercialManager'
And the user navigates to the user page with username 'emnf28'
When the user clicks the edit button on the user card page
And the user inputs values in the user page element fields
| elementName | inputText |
| username | username username |
And the user clicks the create new user button
Then the user sees error messages
| error message |
| Значение недопустимо |


