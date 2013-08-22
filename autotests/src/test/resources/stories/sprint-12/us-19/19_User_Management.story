Narrative:
Как администратор,
Я хочу создавать, редактировать и просматривать профили пользователей,
Чтобы они могли работать с системой

Meta:
@sprint 12
@us 19

Scenario: Create user Commercial Manager type

Given the user opens create new user page
And the user logs in as 'watchman'
When the user inputs values in the user page element fields
| elementName | value |
| name | Имя1 |
| position | Позиция2 |
| username | CommercialManager9 |
| password | Пароль1 |
| role | commercialManager |
And the user clicks the create new user button
Then the user checks the user with 'CommercialManager9' username is present
And the user checks the user with 'CommercialManager9' username has 'name' element equal to 'Имя1' on users page
And the user checks the user with 'CommercialManager9' username has 'position' element equal to 'Позиция2' on users page
And the user checks the user with 'CommercialManager9' username has 'username' element equal to 'CommercialManager9' on users page
And the user checks the user with 'CommercialManager9' username has 'role' element equal to 'Коммерческий директор сети' on users page
When the user opens the user card with 'CommercialManager9' username
Then the user checks the user page elements values
| elementName | value |
| name | Имя1 |
| position | Позиция2 |
| username | CommercialManager9 |
| role | Коммерческий директор сети |

Scenario: Create user store Manager type

Given the user opens create new user page
And the user logs in as 'watchman'
When the user inputs values in the user page element fields
| elementName | value |
| name | Имя1 |
| position | Позиция2 |
| username | storeManager9 |
| password | Пароль1 |
| role | storeManager |
And the user clicks the create new user button
Then the user checks the user with 'storeManager9' username is present
And the user checks the user with 'storeManager9' username has 'name' element equal to 'Имя1' on users page
And the user checks the user with 'storeManager9' username has 'position' element equal to 'Позиция2' on users page
And the user checks the user with 'storeManager9' username has 'username' element equal to 'storeManager9' on users page
And the user checks the user with 'storeManager9' username has 'role' element equal to 'Директор магазина' on users page
When the user opens the user card with 'storeManager9' username
Then the user checks the user page elements values
| elementName | value |
| name | Имя1 |
| position | Позиция2 |
| username | storeManager9 |
| role | Директор магазина |

Scenario: Create user department Manager type

Given the user opens create new user page
And the user logs in as 'watchman'
When the user inputs values in the user page element fields
| elementName | value |
| name | Имя1 |
| position | Позиция2 |
| username | departmentManager9 |
| password | Пароль1 |
| role | departmentManager |
And the user clicks the create new user button
Then the user checks the user with 'departmentManager9' username is present
And the user checks the user with 'departmentManager9' username has 'name' element equal to 'Имя1' on users page
And the user checks the user with 'departmentManager9' username has 'position' element equal to 'Позиция2' on users page
And the user checks the user with 'departmentManager9' username has 'username' element equal to 'departmentManager9' on users page
And the user checks the user with 'departmentManager9' username has 'role' element equal to 'Заведующий отделом' on users page
When the user opens the user card with 'departmentManager9' username
Then the user checks the user page elements values
| elementName | value |
| name | Имя1 |
| position | Позиция2 |
| username | departmentManager9 |
| role | Заведующий отделом |

Scenario: Create user administrator type

Given the user opens create new user page
And the user logs in as 'watchman'
When the user inputs values in the user page element fields
| elementName | value |
| name | Имя1 |
| position | Позиция2 |
| username | administrator9 |
| password | Пароль1 |
| role | administrator |
And the user clicks the create new user button
Then the user checks the user with 'administrator9' username is present
And the user checks the user with 'administrator9' username has 'name' element equal to 'Имя1' on users page
And the user checks the user with 'administrator9' username has 'position' element equal to 'Позиция2' on users page
And the user checks the user with 'administrator9' username has 'username' element equal to 'administrator9' on users page
And the user checks the user with 'administrator9' username has 'role' element equal to 'Системный администратор' on users page
When the user opens the user card with 'administrator9' username
Then the user checks the user page elements values
| elementName | value |
| name | Имя1 |
| position | Позиция2 |
| username | administrator9 |
| role | Системный администратор |

Scenario: User create from users list page

Given the user is on the users list page
And the user logs in as 'watchman'
When the user clicks the create new user button from users list page
And the user inputs values in the user page element fields
| elementName | value |
| name | Имя11 |
| position | Позиция22 |
| username | createfromuserslistpage |
| password | Пароль11 |
| role | administrator |
And the user clicks the create new user button
Then the user checks the user with 'createfromuserslistpage' username is present

Scenario: User edition

Given there is the user with name 'Name1', position 'Position1', username 'Login1', password 'password', role 'commercialManager'
And the user navigates to the user page with username 'Login1'
And the user logs in as 'watchman'
When the user clicks the edit button on the user card page
And the user inputs values in the user page element fields
| elementName | value |
| name | Name_edited |
| position | Position_edited |
| username | Login_edited |
| password | Password_edited |
| role | administrator |
And the user clicks the create new user button
Then the user checks the user page elements values
| elementName | value |
| name | Name_edited |
| position | Position_edited |
| username | Login_edited |
| role | Системный администратор |
When the user clicks on the users list page link
Then the user checks the user with 'Login_edited' username is present
And the user checks the user with 'Login_edited' username has 'name' element equal to 'Name_edited' on users page
And the user checks the user with 'Login_edited' username has 'position' element equal to 'Position_edited' on users page
And the user checks the user with 'Login_edited' username has 'username' element equal to 'Login_edited' on users page
And the user checks the user with 'Login_edited' username has 'role' element equal to 'Системный администратор' on users page

Scenario: User edition commercialManager type

Given there is the user with name 'User edition', position 'User edition', username 'User-edition', password 'User edition', role 'commercialManager'
And the user navigates to the user page with username 'User-edition'
And the user logs in as 'watchman'
When the user clicks the edit button on the user card page
And the user inputs values in the user page element fields
| elementName | value |
| role | commercialManager |
And the user clicks the create new user button
Then the user checks the user page elements values
| elementName | value |
| role | Коммерческий директор сети |
When the user clicks on the users list page link
Then the user checks the user with 'User-edition' username is present
And the user checks the user with 'User-edition' username has 'role' element equal to 'Коммерческий директор сети' on users page

Scenario: User edition storeManager type

Given there is the user with name 'User edition', position 'User edition', username 'User-edition', password 'User edition', role 'commercialManager'
And the user navigates to the user page with username 'User-edition'
And the user logs in as 'watchman'
When the user clicks the edit button on the user card page
And the user inputs values in the user page element fields
| elementName | value |
| role | storeManager |
And the user clicks the create new user button
Then the user checks the user page elements values
| elementName | value |
| role | Директор магазина |
When the user clicks on the users list page link
Then the user checks the user with 'User-edition' username is present
And the user checks the user with 'User-edition' username has 'role' element equal to 'Директор магазина' on users page

Scenario: User edition section chief type

Given there is the user with name 'User edition', position 'User edition', username 'User-edition', password 'User edition', role 'commercialManager'
And the user navigates to the user page with username 'User-edition'
And the user logs in as 'watchman'
When the user clicks the edit button on the user card page
And the user inputs values in the user page element fields
| elementName | value |
| role | departmentManager |
And the user clicks the create new user button
Then the user checks the user page elements values
| elementName | value |
| role | Заведующий отделом |
When the user clicks on the users list page link
Then the user checks the user with 'User-edition' username is present
And the user checks the user with 'User-edition' username has 'role' element equal to 'Заведующий отделом' on users page

Scenario: User edition administrator type

Given there is the user with name 'User edition', position 'User edition', username 'User-edition', password 'User edition', role 'commercialManager'
And the user navigates to the user page with username 'User-edition'
And the user logs in as 'watchman'
When the user clicks the edit button on the user card page
And the user inputs values in the user page element fields
| elementName | value |
| role | administrator |
And the user clicks the create new user button
Then the user checks the user page elements values
| elementName | value |
| role | Системный администратор |
When the user clicks on the users list page link
Then the user checks the user with 'User-edition' username is present
And the user checks the user with 'User-edition' username has 'role' element equal to 'Системный администратор' on users page

