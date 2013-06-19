
Scenario: name field length validation 100 symbols
Given the user opens create new user page
When the user inputs values in the user page element fields
| elementName | inputText |
| position | nflv100 |
| username | nflv100 |
| password | password |
| role | commercialManager |
And the user generates charData with '100' number in the 'name' user page field
Then the user checks 'name' user page field contains only '100' symbols
When the user clicks the create new user button
Then the user sees no error messages

Scenario: name field length validation negative 101 symbols
Given the user opens create new user page
When the user inputs values in the user page element fields
| elementName | inputText |
| position | nflv100 |
| username | nflv101 |
| password | password |
| role | commercialManager |
And the user generates charData with '101' number in the 'name' user page field
Then the user checks 'name' user page field contains only '101' symbols
When the user clicks the create new user button
Then the user sees error messages
| error message |
| Не более 100 символов |

Scenario: name field is required
Given the user opens create new user page
When the user inputs values in the user page element fields
| elementName | inputText |
| position | nflv100 |
| username | nflv102 |
| password | password |
| role | commercialManager |
When the user clicks the create new user button
Then the user sees error messages
| error message |
| Заполните это поле |

Scenario: position field length validation 100 symbols
Given the user opens create new user page
When the user inputs values in the user page element fields
| elementName | inputText |
| name | Имя1 |
| username | nflv103 |
| password | Пароль1 |
| role | commercialManager |
And the user generates charData with '100' number in the 'position' user page field
Then the user checks 'position' user page field contains only '100' symbols
When the user clicks the create new user button
Then the user sees no error messages

Scenario: position field length validation negative 101 symbols
Given the user opens create new user page
When the user inputs values in the user page element fields
| elementName | inputText |
| name | Имя1 |
| username | nflv104 |
| password | Пароль1 |
| role | commercialManager |
And the user generates charData with '101' number in the 'position' user page field
Then the user checks 'position' user page field contains only '101' symbols
When the user clicks the create new user button
Then the user sees error messages
| error message |
| Не более 100 символов |

Scenario: position field is required
Given the user opens create new user page
When the user inputs values in the user page element fields
| elementName | inputText |
| name | Имя1 |
| username | nflv105 |
| password | Пароль1 |
| role | commercialManager |
When the user clicks the create new user button
Then the user sees error messages
| error message |
| Заполните это поле |

Scenario: role field is required
Given the user opens create new user page
When the user inputs values in the user page element fields
| elementName | inputText |
| name | Имя1 |
| position | Позиция2 |
| username | nflv106 |
| password | Пароль1 |
When the user clicks the create new user button
Then the user sees error messages
| error message |
| Заполните это поле |

Scenario: password minimum length validation invalid
Given the user opens create new user page
When the user inputs values in the user page element fields
| elementName | inputText |
| name | Имя1 |
| position | Позиция2 |
| username | nflv107 |
| password | 12345 |
| role | commercialManager |
And the user clicks the create new user button
Then the user sees error messages
| error message |
| Значение слишком короткое. Должно быть равно 6 символам или больше. |

Scenario: password minimum length validation positive
Given the user opens create new user page
When the user inputs values in the user page element fields
| elementName | inputText |
| name | Имя1 |
| position | Позиция2 |
| username | nflv108 |
| password | 123456 |
| role | commercialManager |
And the user clicks the create new user button
Then the user sees no error messages

Scenario: password must be not equal to username
Given the user opens create new user page
When the user inputs values in the user page element fields
| elementName | inputText |
| name | Имя1 |
| position | Позиция2 |
| username | pmbnetl |
| password | pmbnetl |
| role | commercialManager |
And the user clicks the create new user button
Then the user sees error messages
| error message |
| Логин и пароль не должны совпадать |

Scenario: password validation positive
Given the user opens create new user page
When the user inputs values in the user page element fields
| elementName | inputText |
| name | Имя1 |
| position | Позиция2 |
| username | nflv109 |
| password | ФыEf3!@$#$$%() |
| role | commercialManager |
And the user clicks the create new user button
Then the user sees no error messages

Scenario: username field length validation 100 symbols
Given the user opens create new user page
When the user inputs values in the user page element fields
| elementName | inputText |
| name | Имя1 |
| position | Позиция2 |
| password | Пароль1 |
| role | commercialManager |
And the user clicks the create new user button
And the user generates charData with '100' number without spaces  in the 'username' user page field
Then the user checks 'username' user page field contains only '100' symbols
When the user clicks the create new user button
Then the user sees no error messages

Scenario: username field length validation negative 101 symbols
Given the user opens create new user page
When the user inputs values in the user page element fields
| elementName | inputText |
| name | Имя1 |
| position | Позиция2 |
| password | Пароль1 |
| role | commercialManager |
And the user clicks the create new user button
And the user generates charData with '101' number without spaces  in the 'username' user page field
Then the user checks 'username' user page field contains only '101' symbols
When the user clicks the create new user button
Then the user sees error messages
| error message |
| Не более 100 символов |

Scenario: username must be unique
Given there is the user with name 'User validation lmbu', position 'User validation lmbu', username 'Uservalidationlmbu', password 'Uservalidationlmbu1', role 'commercialManager'
And the user opens create new user page
When the user inputs values in the user page element fields
| elementName | inputText |
| name | Имя1 |
| position | Позиция2 |
| username | Uservalidationlmbu |
| password | Пароль1 |
| role | commercialManager |
And the user clicks the create new user button
Then the user sees error messages
| error message |
| Пользователь с таким логином уже существует |

Scenario: username is required
Given the user opens create new user page
When the user inputs values in the user page element fields
| elementName | inputText |
| name | Имя1 |
| position | Позиция2 |
| password | Пароль1 |
| role | commercialManager |
And the user clicks the create new user button
Then the user sees error messages
| error message |
| Заполните это поле |

Scenario: username validation positive Rus Big regiser
Given the user opens create new user page
When the user inputs values in the user page element fields
| elementName | inputText |
| name | тест1 |
| position | тест1 |
| username | РУССКИЙ |
| password | password |
| role | commercialManager |
And the user clicks the create new user button
Then the user sees no error messages

Scenario: username validation positive Rus Small register
Given the user opens create new user page
When the user inputs values in the user page element fields
| elementName | inputText |
| name | тест1 |
| position | тест1 |
| username | руссский |
| password | password |
| role | commercialManager |
And the user clicks the create new user button
Then the user sees no error messages

Scenario: username validation positive Eng Small register
Given the user opens create new user page
When the user inputs values in the user page element fields
| elementName | inputText |
| name | тест1 |
| position | тест1 |
| username | ENGLISH |
| password | password |
| role | commercialManager |
And the user clicks the create new user button
Then the user sees no error messages

Scenario: username validation positive Eng Big register
Given the user opens create new user page
When the user inputs values in the user page element fields
| elementName | inputText |
| name | тест1 |
| position | тест1 |
| username | englishh |
| password | password |
| role | commercialManager |
And the user clicks the create new user button
Then the user sees no error messages

Scenario: username validation positive digits 0-9
Given the user opens create new user page
When the user inputs values in the user page element fields
| elementName | inputText |
| name | тест1 |
| position | тест1 |
| username | 1234567890 |
| password | password |
| role | commercialManager |
And the user clicks the create new user button
Then the user sees no error messages

Scenario: username validation positive symbols _
Given the user opens create new user page
When the user inputs values in the user page element fields
| elementName | inputText |
| name | тест1 |
| position | тест1 |
| username | username_username |
| password | password |
| role | commercialManager |
And the user clicks the create new user button
Then the user sees no error messages

Scenario: username validation positive symbols -
Given the user opens create new user page
When the user inputs values in the user page element fields
| elementName | inputText |
| name | тест1 |
| position | тест1 |
| username | username-username |
| password | password |
| role | commercialManager |
And the user clicks the create new user button
Then the user sees no error messages

Scenario: username validation positive symbols .
Given the user opens create new user page
When the user inputs values in the user page element fields
| elementName | inputText |
| name | тест1 |
| position | тест1 |
| username | username.username |
| password | password |
| role | commercialManager |
And the user clicks the create new user button
Then the user sees no error messages

Scenario: username validation positive symbols @
Given the user opens create new user page
When the user inputs values in the user page element fields
| elementName | inputText |
| name | тест1 |
| position | тест1 |
| username | username@username |
| password | password |
| role | commercialManager |
And the user clicks the create new user button
Then the user sees no error messages

Scenario: username validation positive symbols mix
Given the user opens create new user page
When the user inputs values in the user page element fields
| elementName | inputText |
| name | тест1 |
| position | тест1 |
| username | username_username-username@gmaik.com |
| password | password |
| role | commercialManager |
And the user clicks the create new user button
Then the user sees no error messages

Scenario: username validation negative symbols
Given the user opens create new user page
When the user inputs values in the user page element fields
| elementName | inputText |
| name | тест1 |
| position | тест1 |
| username | ~!@#$%^&*()+= |
| password | password |
| role | commercialManager |
And the user clicks the create new user button
Then the user sees error messages
| error message |
| Значение недопустимо |

Scenario: username validation negative symbols white space
Given the user opens create new user page
When the user inputs values in the user page element fields
| elementName | inputText |
| name | тест1 |
| position | тест1 |
| username | username username |
| password | password |
| role | commercialManager |
And the user clicks the create new user button
Then the user sees error messages
| error message |
| Значение недопустимо |


