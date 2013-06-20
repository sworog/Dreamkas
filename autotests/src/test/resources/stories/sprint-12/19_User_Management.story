Narrative:
Как администратор,
Я хочу создавать, редактировать и просматривать профили пользователей,
Чтобы они могли работать с системой


Scenario: Create user Commercial Manager type
Given the user opens create new user page
When the user inputs values in the user page element fields
| elementName | inputText |
| name | Имя1 |
| position | Позиция2 |
| username | CommercialManager |
| password | Пароль1 |
| role | commercialManager |
And the user clicks the create new user button
Then the user checks the user with 'CommercialManager' username is present
And the user checks the user with 'CommercialManager' username has 'name' element equal to 'Имя1' on users page
And the user checks the user with 'CommercialManager' username has 'position' element equal to 'Позиция2' on users page
And the user checks the user with 'CommercialManager' username has 'username' element equal to 'CommercialManager' on users page
And the user checks the user with 'CommercialManager' username has 'role' element equal to 'Коммерческий директор сети' on users page
When the user opens the user card with 'CommercialManager' username
Then the user checks the user page elements values
| elementName | expectedValue |
| name | Имя1 |
| position | Позиция2 |
| username | CommercialManager |
| role | Коммерческий директор сети |

Scenario: Create user store Manager type
Given the user opens create new user page
When the user inputs values in the user page element fields
| elementName | inputText |
| name | Имя1 |
| position | Позиция2 |
| username | storeManager |
| password | Пароль1 |
| role | storeManager |
And the user clicks the create new user button
Then the user checks the user with 'storeManager' username is present
And the user checks the user with 'storeManager' username has 'name' element equal to 'Имя1' on users page
And the user checks the user with 'storeManager' username has 'position' element equal to 'Позиция2' on users page
And the user checks the user with 'storeManager' username has 'username' element equal to 'storeManager' on users page
And the user checks the user with 'storeManager' username has 'role' element equal to 'Директор магазина' on users page
When the user opens the user card with 'storeManager' username
Then the user checks the user page elements values
| elementName | expectedValue |
| name | Имя1 |
| position | Позиция2 |
| username | storeManager |
| role | Директор магазина |

Scenario: Create user department Manager type
Given the user opens create new user page
When the user inputs values in the user page element fields
| elementName | inputText |
| name | Имя1 |
| position | Позиция2 |
| username | departmentManager |
| password | Пароль1 |
| role | departmentManager |
And the user clicks the create new user button
Then the user checks the user with 'departmentManager' username is present
And the user checks the user with 'departmentManager' username has 'name' element equal to 'Имя1' on users page
And the user checks the user with 'departmentManager' username has 'position' element equal to 'Позиция2' on users page
And the user checks the user with 'departmentManager' username has 'username' element equal to 'departmentManager' on users page
And the user checks the user with 'departmentManager' username has 'role' element equal to 'Заведующий отделом' on users page
When the user opens the user card with 'departmentManager' username
Then the user checks the user page elements values
| elementName | expectedValue |
| name | Имя1 |
| position | Позиция2 |
| username | departmentManager |
| role | Заведующий отделом |

Scenario: Create user administrator type
Given the user opens create new user page
When the user inputs values in the user page element fields
| elementName | inputText |
| name | Имя1 |
| position | Позиция2 |
| username | administrator |
| password | Пароль1 |
| role | administrator |
And the user clicks the create new user button
Then the user checks the user with 'administrator' username is present
And the user checks the user with 'administrator' username has 'name' element equal to 'Имя1' on users page
And the user checks the user with 'administrator' username has 'position' element equal to 'Позиция2' on users page
And the user checks the user with 'administrator' username has 'username' element equal to 'administrator' on users page
And the user checks the user with 'administrator' username has 'role' element equal to 'Системный администратор' on users page
When the user opens the user card with 'administrator' username
Then the user checks the user page elements values
| elementName | expectedValue |
| name | Имя1 |
| position | Позиция2 |
| username | administrator |
| role | Системный администратор |

Scenario: user create from users list page
Given the user is on the users list page
When the user clicks the create new user button from users list page
And the user inputs values in the user page element fields
| elementName | inputText |
| name | Имя11 |
| position | Позиция22 |
| username | createfromuserslistpage |
| password | Пароль11 |
| role | administrator |
And the user clicks the create new user button
Then the user checks the user with 'createfromuserslistpage' username is present

Scenario: user edition
Given there is the user with name 'Name1', position 'Position1', username 'Login1', password 'password', role 'commercialManager'
And the user navigates to the user page with username 'Login1'
When the user clicks the edit button on the user card page
And the user inputs values in the user page element fields
| elementName | inputText |
| name | Name_edited |
| position | Position_edited |
| username | Login_edited |
| password | Password_edited |
| role | administrator |
And the user clicks the create new user button
Then the user checks the user page elements values
| elementName | expectedValue |
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

Scenario: user edition commercialManager type
Given there is the user with name 'User edition', position 'User edition', username 'User-edition', password 'User edition', role 'commercialManager'
And the user navigates to the user page with username 'User-edition'
When the user clicks the edit button on the user card page
And the user inputs values in the user page element fields
| elementName | inputText |
| role | commercialManager |
And the user clicks the create new user button
Then the user checks the user page elements values
| elementName | expectedValue |
| role | Коммерческий директор сети |
When the user clicks on the users list page link
Then the user checks the user with 'User-edition' username is present
And the user checks the user with 'User-edition' username has 'role' element equal to 'Коммерческий директор сети' on users page

Scenario: user edition storeManager type
Given there is the user with name 'User edition', position 'User edition', username 'User-edition', password 'User edition', role 'commercialManager'
And the user navigates to the user page with username 'User-edition'
When the user clicks the edit button on the user card page
And the user inputs values in the user page element fields
| elementName | inputText |
| role | storeManager |
And the user clicks the create new user button
Then the user checks the user page elements values
| elementName | expectedValue |
| role | Директор магазина |
When the user clicks on the users list page link
Then the user checks the user with 'User-edition' username is present
And the user checks the user with 'User-edition' username has 'role' element equal to 'Директор магазина' on users page

Scenario: user edition section chief type
Given there is the user with name 'User edition', position 'User edition', username 'User-edition', password 'User edition', role 'commercialManager'
And the user navigates to the user page with username 'User-edition'
When the user clicks the edit button on the user card page
And the user inputs values in the user page element fields
| elementName | inputText |
| role | departmentManager |
And the user clicks the create new user button
Then the user checks the user page elements values
| elementName | expectedValue |
| role | Заведующий отделом |
When the user clicks on the users list page link
Then the user checks the user with 'User-edition' username is present
And the user checks the user with 'User-edition' username has 'role' element equal to 'Заведующий отделом' on users page

Scenario: user edition administrator type
Given there is the user with name 'User edition', position 'User edition', username 'User-edition', password 'User edition', role 'commercialManager'
And the user navigates to the user page with username 'User-edition'
When the user clicks the edit button on the user card page
And the user inputs values in the user page element fields
| elementName | inputText |
| role | administrator |
And the user clicks the create new user button
Then the user checks the user page elements values
| elementName | expectedValue |
| role | Системный администратор |
When the user clicks on the users list page link
Then the user checks the user with 'User-edition' username is present
And the user checks the user with 'User-edition' username has 'role' element equal to 'Системный администратор' on users page


