20 Авторизация пользователя в системе

Narrative:
In order to работать
As a пользователь
I want to авторизироваться в системе

Meta:
@sprint_13
@us_20

Scenario: Authorization invalid password

Given the user opens the authorization page
When the user logs in using 'watchman' userName and '123456' password to check validation
Then the user sees error messages
| error message |
| Неверный логин или пароль |

Scenario: Authorization invalid password, userName

Given the user opens the authorization page
When the user logs in using '123456' userName and '123456' password to check validation
Then the user sees error messages
| error message |
| Неверный логин или пароль |

Scenario: Authorization blank userName, password

Given the user opens the authorization page
When the user logs in using '' userName and '' password to check validation
Then the user sees error messages
| error message |
| Пожалуйста заполните обязательные поля логин и пароль |

Scenario: Authorization blank only userName

Given the user opens the authorization page
When the user logs in using '' userName and '123456' password to check validation
Then the user sees error messages
| error message |
| Пожалуйста заполните обязательные поля логин и пароль |

Scenario: Authorization blank only password

Given the user opens the authorization page
When the user logs in using 'watchman' userName and '' password to check validation
Then the user sees error messages
| error message |
| Пожалуйста заполните обязательные поля логин и пароль |

Scenario: Authorization, log out successfull

Given the user opens the authorization page
When the user logs in using 'watchman' userName and 'lighthouse' password
And the user logs out
Then the user checks the login form is present

Scenario: Unathorization use - product create page

Given the user is on the product create page
Then the user checks the login form is present

Scenario: Unathorization use - product list page

Given the user is on the product list page
Then the user checks the login form is present

Scenario: Unathorization use - catalog page

Given the user opens catalog page
Then the user checks the login form is present

Scenario: Unathorization use - users list page

Given the user is on the users list page
Then the user checks the login form is present

Scenario: Unathorization use - users create page

Given the user opens create new user page
Then the user checks the login form is present

Scenario: Unathorization use - users ivoice list page

Given the user is on the invoice list page
Then the user checks the login form is present

Scenario: Unathorization use - users ivoice create page

Given the user opens the default store invoice create page
Then the user checks the login form is present

Scenario: Unathorization use - the write off create page

Given the user opens the write off create page
Then the user checks the login form is present

Scenario: Unathorization use - the write off list page

Given the user opens write off list page
Then the user checks the login form is present


