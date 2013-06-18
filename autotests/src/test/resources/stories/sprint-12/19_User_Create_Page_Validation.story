
Scenario: name field length validation 100 symbols
Given the user opens create new user page
When the user inputs values in the user page element fields
| elementName | inputText |
| position | nflv100 |
| login | nflv100 |
| password | password |
| role | Коммерческий директор сети |
And the user generates charData with '100' number in the 'name' user page field
Then the user checks 'name' user page field contains only '100' symbols
When the user clicks the create new user button
Then the user sees no error messages

Scenario: name field length validation negative 101 symbols
Given the user opens create new user page
When the user inputs values in the user page element fields
| elementName | inputText |
| position | nflv100 |
| login | nflv100 |
| password | password |
| role | Коммерческий директор сети |
And the user generates charData with '101' number in the 'name' user page field
Then the user checks 'name' user page field contains only '101' symbols
When the user clicks the create new user button
Then the user sees error messages
| error message |
| Не более 101 символов |

Scenario: name field is required
Given the user opens create new user page
When the user inputs values in the user page element fields
| elementName | inputText |
| position | nflv100 |
| login | nflv100 |
| password | password |
| role | Коммерческий директор сети |
When the user clicks the create new user button
Then the user sees error messages
| error message |
| Заполните это поле |

Scenario: position field length validation 100 symbols
Given the user opens create new user page
When the user inputs values in the user page element fields
| elementName | inputText |
| name | Имя1 |
| login | Логин1 |
| password | Пароль1 |
| role | Коммерческий директор сети |
And the user generates charData with '100' number in the 'name' user page field
Then the user checks 'name' user page field contains only '100' symbols
When the user clicks the create new user button
Then the user sees no error messages

Scenario: position field length validation negative 101 symbols
Given the user opens create new user page
When the user inputs values in the user page element fields
| elementName | inputText |
| name | Имя1 |
| login | Логин1 |
| password | Пароль1 |
| role | Коммерческий директор сети |
And the user generates charData with '101' number in the 'name' user page field
Then the user checks 'name' user page field contains only '101' symbols
When the user clicks the create new user button
Then the user sees error messages
| error message |
| Не более 101 символов |

Scenario: position field is required
Given the user opens create new user page
When the user inputs values in the user page element fields
| elementName | inputText |
| name | Имя1 |
| login | Логин1 |
| password | Пароль1 |
| role | Коммерческий директор сети |
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
| login | Логин1 |
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
| login | Логин1 |
| password | 12345 |
| role | Коммерческий директор сети |
And the user clicks the create new user button
Then the user sees error messages
| error message |
| Пароль должен быть не меньше 6 символов |

Scenario: password minimum length validation positive
Given the user opens create new user page
When the user inputs values in the user page element fields
| elementName | inputText |
| name | Имя1 |
| position | Позиция2 |
| login | pmlvp |
| password | 12345 |
| role | Коммерческий директор сети |
And the user clicks the create new user button
Then the user sees no error messages

Scenario: password must be not equal to login
Given the user opens create new user page
When the user inputs values in the user page element fields
| elementName | inputText |
| name | Имя1 |
| position | Позиция2 |
| login | pmbnetl |
| password | pmbnetl |
| role | Коммерческий директор сети |
And the user clicks the create new user button
Then the user sees error messages
| error message |
| Логин должен быть отличен от пароля |

Scenario: password validation positive
Given the user opens create new user page
When the user inputs values in the user page element fields
| elementName | inputText |
| name | Имя1 |
| position | Позиция2 |
| login | pvp1234 |
| password | ФыEf3!@$#$$%() |
| role | Коммерческий директор сети |
And the user clicks the create new user button
Then the user sees no error messages

Scenario: login field length validation 100 symbols
Given the user opens create new user page
When the user inputs values in the user page element fields
| elementName | inputText |
| name | Имя1 |
| position | Позиция2 |
| password | Пароль1 |
| role | Коммерческий директор сети |
And the user clicks the create new user button
And the user generates charData with '100' number in the 'login' user page field
Then the user checks 'login' user page field contains only '100' symbols
When the user clicks the create new user button
Then the user sees no error messages

Scenario: login field length validation negative 101 symbols
Given the user opens create new user page
When the user inputs values in the user page element fields
| elementName | inputText |
| name | Имя1 |
| position | Позиция2 |
| password | Пароль1 |
| role | Коммерческий директор сети |
And the user clicks the create new user button
And the user generates charData with '101' number in the 'login' user page field
Then the user checks 'login' user page field contains only '101' symbols
When the user clicks the create new user button
Then the user sees error messages
| error message |
| Не более 101 символов |

Scenario: login must be unique
Given there is the user with name 'User validation lmbu', position 'User validation lmbu', login 'User validation lmbu', password 'User validation lmbu', role 'администратор'
And the user opens create new user page
When the user inputs values in the user page element fields
| elementName | inputText |
| name | Имя1 |
| position | Позиция2 |
| login | User validation lmbu |
| password | Пароль1 |
| role | Коммерческий директор сети |
And the user clicks the create new user button
Then the user sees error messages
| error message |
| Такое имя пользователя уже существует |

Scenario: login is required
Given the user opens create new user page
When the user inputs values in the user page element fields
| elementName | inputText |
| name | Имя1 |
| position | Позиция2 |
| password | Пароль1 |
| role | Коммерческий директор сети |
And the user clicks the create new user button
Then the user sees error messages
| error message |
| Заполните это поле |

Scenario: login validation positive Rus Big regiser
Given the user opens create new user page
When the user inputs values in the user page element fields
| elementName | inputText |
| name | тест1 |
| position | тест1 |
| login | РУССКИЙ |
| password | password |
| role | Коммерческий директор сети |
And the user clicks the create new user button
Then the user sees no error messages

Scenario: login validation positive Rus Small register
Given the user opens create new user page
When the user inputs values in the user page element fields
| elementName | inputText |
| name | тест1 |
| position | тест1 |
| login | руссский |
| password | password |
| role | Коммерческий директор сети |
And the user clicks the create new user button
Then the user sees no error messages

Scenario: login validation positive Eng Small register
Given the user opens create new user page
When the user inputs values in the user page element fields
| elementName | inputText |
| name | тест1 |
| position | тест1 |
| login | ENGLISH |
| password | password |
| role | Коммерческий директор сети |
And the user clicks the create new user button
Then the user sees no error messages

Scenario: login validation positive Eng Big register
Given the user opens create new user page
When the user inputs values in the user page element fields
| elementName | inputText |
| name | тест1 |
| position | тест1 |
| login | englishh |
| password | password |
| role | Коммерческий директор сети |
And the user clicks the create new user button
Then the user sees no error messages

Scenario: login validation positive digits 0-9
Given the user opens create new user page
When the user inputs values in the user page element fields
| elementName | inputText |
| name | тест1 |
| position | тест1 |
| login | 1234567890 |
| password | password |
| role | Коммерческий директор сети |
And the user clicks the create new user button
Then the user sees no error messages

Scenario: login validation positive symbols _
Given the user opens create new user page
When the user inputs values in the user page element fields
| elementName | inputText |
| name | тест1 |
| position | тест1 |
| login | login_login |
| password | password |
| role | Коммерческий директор сети |
And the user clicks the create new user button
Then the user sees no error messages

Scenario: login validation positive symbols -
Given the user opens create new user page
When the user inputs values in the user page element fields
| elementName | inputText |
| name | тест1 |
| position | тест1 |
| login | login-login |
| password | password |
| role | Коммерческий директор сети |
And the user clicks the create new user button
Then the user sees no error messages

Scenario: login validation positive symbols .
Given the user opens create new user page
When the user inputs values in the user page element fields
| elementName | inputText |
| name | тест1 |
| position | тест1 |
| login | login.login |
| password | password |
| role | Коммерческий директор сети |
And the user clicks the create new user button
Then the user sees no error messages

Scenario: login validation positive symbols @
Given the user opens create new user page
When the user inputs values in the user page element fields
| elementName | inputText |
| name | тест1 |
| position | тест1 |
| login | login@login |
| password | password |
| role | Коммерческий директор сети |
And the user clicks the create new user button
Then the user sees no error messages

Scenario: login validation positive symbols mix
Given the user opens create new user page
When the user inputs values in the user page element fields
| elementName | inputText |
| name | тест1 |
| position | тест1 |
| login | login_login-login@gmaik.com |
| password | password |
| role | Коммерческий директор сети |
And the user clicks the create new user button
Then the user sees no error messages

Scenario: login validation negative symbols
Given the user opens create new user page
When the user inputs values in the user page element fields
| elementName | inputText |
| name | тест1 |
| position | тест1 |
| login | ~!@#$%^&*()+= |
| password | password |
| role | Коммерческий директор сети |
And the user clicks the create new user button
Then the user sees error messages
| error message |
| Неверный ввод! |

Scenario: login validation negative symbols white space
Given the user opens create new user page
When the user inputs values in the user page element fields
| elementName | inputText |
| name | тест1 |
| position | тест1 |
| login | login login |
| password | password |
| role | Коммерческий директор сети |
And the user clicks the create new user button
Then the user sees error messages
| error message |
| Неверный ввод! |


