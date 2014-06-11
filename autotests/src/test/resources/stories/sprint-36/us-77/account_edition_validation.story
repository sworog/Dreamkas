Meta:
@sprint_36
@us_77

Narrative:
Как владелец торговой точки,
Я хочу изменить данные своей учетной записи,
Чтобы держать доступ к аккаунту под контролем

Scenario: Account name field length validation 100 symbols

Meta:
@id_s36u77s6

Given the user runs the symfony:user:create command with params: generated email and common password

Given the user opens user edit page
And the user logs in using generated email and common password

When the user generates charData with '100' number in the 'name' user page field

Then the user checks 'name' user page field contains only '100' symbols

When the user inputs password 'lighthouse' on the user edit page
And the user clicks on save user data button

Then the user sees no error messages

Scenario: Account name field length validation negative 101 symbols

Meta:
@id_s36u77s7

Given the user runs the symfony:user:create command with params: generated email and common password

Given the user opens user edit page
And the user logs in using generated email and common password

When the user generates charData with '101' number in the 'name' user page field

Then the user checks 'name' user page field contains only '101' symbols

When the user inputs password 'lighthouse' on the user edit page
And the user clicks on save user data button

Then the user sees exact error messages
| error message |
| Не более 100 символов |

Scenario: Account name field is not required

Meta:
@id_s36u77s8

Given the user runs the symfony:user:create command with params: generated email and common password

Given the user opens user edit page
And the user logs in using generated email and common password

When the user inputs values on user edit page
| elementName | value |
| name |  |
| password | lighthouse |
And the user clicks on save user data button

Then the user sees no error messages

Scenario: Account password minimum length validation invalid

Meta:
@id_s36u77s9

Given the user runs the symfony:user:create command with params: generated email and common password

Given the user opens user edit page
And the user logs in using generated email and common password

When the user inputs values on user edit page
| elementName | value |
| password | 12345 |
And the user clicks on save user data button

Then the user sees exact error messages
| error message |
| Значение слишком короткое. Должно быть равно 6 символам или больше. |

Scenario: Account password minimum length validation positive

Meta:
@id_s36u77s10

Given the user runs the symfony:user:create command with params: generated email and common password

Given the user opens user edit page
And the user logs in using generated email and common password

When the user inputs values on user edit page
| elementName | value |
| password | 123456 |
And the user clicks on save user data button

Then the user sees no error messages

Scenario: Account password must be not equal to email

Meta:
@id_s36u77s11

Given the user runs the symfony:user:create command with params: generated email and common password

Given the user opens user edit page
And the user logs in using generated email and common password

When the user inputs values on user edit page
| elementName | value |
| email | test.s36u77s6@lighthouse.pro |
| password | test.s36u77s6@lighthouse.pro |
And the user clicks on save user data button

Then the user sees exact error messages
| error message |
| E-mail и пароль не должны совпадать |

Scenario: Account password validation positive

Meta:
@id_s36u77s12

Given the user runs the symfony:user:create command with params: generated email and common password

Given the user opens user edit page
And the user logs in using generated email and common password

When the user inputs values on user edit page
| elementName | value |
| password | ФыEf3!@$#$$%() |
And the user clicks on save user data button

Then the user sees no error messages

Scenario: Account email must be unique

Meta:
@id_s36u77s13

Given the user runs the symfony:user:create command with params: email 'test.s36u77s8@lighthouse.pro' and password 'lighthouse'
And the user runs the symfony:user:create command with params: email 'test.s36u77s9@lighthouse.pro' and password 'lighthouse'

Given the user opens user edit page
And the user logs in using 'test.s36u77s9@lighthouse.pro' userName and 'lighthouse' password

When the user inputs values on user edit page
| elementName | value |
| email | test.s36u77s8@lighthouse.pro |
| password | lighthouse |
And the user clicks on save user data button

Then the user sees exact error messages
| error message |
| Пользователь с таким email уже существует |

Scenario: Account email is required

Meta:
@id_s36u77s14

Given the user runs the symfony:user:create command with params: generated email and common password

Given the user opens user edit page
And the user logs in using generated email and common password

When the user inputs values on user edit page
| elementName | value |
| email | |
| password | lighthouse |
And the user clicks on save user data button

Then the user sees exact error messages
| error message |
| Заполните это поле |
