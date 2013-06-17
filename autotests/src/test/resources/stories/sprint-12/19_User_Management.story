Narrative:
Как администратор,
Я хочу создавать, редактировать и просматривать профили пользователей,
Чтобы они могли работать с системой

Scenario: Create user Commercial Director type
Given the user opens create new user page
When the user inputs values in the user page element fields
| elementName | inputText |
| name | Имя1 |
| position | Позиция2 |
| login | Логин1 |
| password | Пароль1 |
| role | Коммерческий директор сети |
And the user clicks the create new user button
Then the user checks the user with 'Логин1' login is present
And the user checks the user with 'Логин1' sku has 'name' element equal to 'Имя1' on amounts page
And the user checks the user with 'Логин1' sku has 'position' element equal to 'Позиция1' on amounts page
And the user checks the user with 'Логин1' sku has 'login' element equal to 'Логин1' on amounts page
And the user checks the user with 'Логин1' sku has 'password' element equal to 'Пароль1' on amounts page
And the user checks the user with 'Логин1' sku has 'role' element equal to 'Коммерческий директор сети' on amounts page
When the user opens the user card with 'Логин1' login
Then the user checks the user page elements values
| elementName | expectedValue |
| name | Имя1 |
| position | Позиция1 |
| login | Логин1 |
| password | Пароль1 |
| role | Коммерческий директор сети |

Scenario: Create user sales manager type
Given the user opens create new user page
When the user inputs values in the user page element fields
| elementName | inputText |
| name | Имя1 |
| position | Позиция2 |
| login | Логин1 |
| password | Пароль1 |
| role | Директор сети |
And the user clicks the create new user button
Then the user checks the user with 'Логин1' login is present
And the user checks the user with 'Логин1' sku has 'name' element equal to 'Имя1' on amounts page
And the user checks the user with 'Логин1' sku has 'position' element equal to 'Позиция1' on amounts page
And the user checks the user with 'Логин1' sku has 'login' element equal to 'Логин1' on amounts page
And the user checks the user with 'Логин1' sku has 'password' element equal to 'Пароль1' on amounts page
And the user checks the user with 'Логин1' sku has 'role' element equal to 'Директор сети' on amounts page
When the user opens the user card with 'Логин1' login
Then the user checks the user page elements values
| elementName | expectedValue |
| name | Имя1 |
| position | Позиция1 |
| login | Логин1 |
| password | Пароль1 |
| role | Директор сети |

Scenario: Create user section chief type
Given the user opens create new user page
When the user inputs values in the user page element fields
| elementName | inputText |
| name | Имя1 |
| position | Позиция2 |
| login | Логин1 |
| password | Пароль1 |
| role | Заведующим отделом |
And the user clicks the create new user button
Then the user checks the user with 'Логин1' login is present
And the user checks the user with 'Логин1' sku has 'name' element equal to 'Имя1' on amounts page
And the user checks the user with 'Логин1' sku has 'position' element equal to 'Позиция1' on amounts page
And the user checks the user with 'Логин1' sku has 'login' element equal to 'Логин1' on amounts page
And the user checks the user with 'Логин1' sku has 'password' element equal to 'Пароль1' on amounts page
And the user checks the user with 'Логин1' sku has 'role' element equal to 'Заведующим отделом' on amounts page
When the user opens the user card with 'Логин1' login
Then the user checks the user page elements values
| elementName | expectedValue |
| name | Имя1 |
| position | Позиция1 |
| login | Логин1 |
| password | Пароль1 |
| role | Заведующим отделом |

Scenario: Create user administrator type
Given the user opens create new user page
When the user inputs values in the user page element fields
| elementName | inputText |
| name | Имя1 |
| position | Позиция2 |
| login | Логин1 |
| password | Пароль1 |
| role | Администратор |
And the user clicks the create new user button
Then the user checks the user with 'Логин1' login is present
And the user checks the user with 'Логин1' sku has 'name' element equal to 'Имя1' on amounts page
And the user checks the user with 'Логин1' sku has 'position' element equal to 'Позиция1' on amounts page
And the user checks the user with 'Логин1' sku has 'login' element equal to 'Логин1' on amounts page
And the user checks the user with 'Логин1' sku has 'password' element equal to 'Пароль1' on amounts page
And the user checks the user with 'Логин1' sku has 'role' element equal to 'Администратор' on amounts page
When the user opens the user card with 'Логин1' login
Then the user checks the user page elements values
| elementName | expectedValue |
| name | Имя1 |
| position | Позиция1 |
| login | Логин1 |
| password | Пароль1 |
| role | Администратор |

Scenario: user create from users list page
Given the user is on the users list page
When the user clicks the create new user button from users list page
And the user inputs values in the user page element fields
| elementName | inputText |
| name | Имя11 |
| position | Позиция22 |
| login | Логин11 |
| password | Пароль11 |
| role | Администратор |
And the user clicks the create new user button
Then the user checks the user with 'Логин11' login is present

Scenario: user edition
Given there is the user with name 'Name1', position 'Position1', login 'Login1', password 'password', role 'Коммерческий директор сети'
And the user navigates to the user page with login 'Login1'
When the user clicks the edit button on the user card page
And the user inputs values in the user page element fields
| elementName | inputText |
| name | Name_edited |
| position | Position_edited |
| login | Login_edited |
| password | Password_edited |
| role | Администратор |
And the user clicks the create new user button
Then the user checks the user page elements values
| elementName | inputText |
| name | Name_edited |
| position | Position_edited |
| login | Login_edited |
| password | Password_edited |
| role | Администратор |
When the user clicks on the users list page link
Then the user checks the user with 'Login_edited' login is present
And the user checks the user with 'Login_edited' sku has 'name' element equal to 'Name_edited' on amounts page
And the user checks the user with 'Login_edited' sku has 'position' element equal to 'Position_edited' on amounts page
And the user checks the user with 'Login_edited' sku has 'login' element equal to 'Login_edited' on amounts page
And the user checks the user with 'Login_edited' sku has 'password' element equal to 'Password_edited' on amounts page
And the user checks the user with 'Login_edited' sku has 'role' element equal to 'Администратор' on amounts page

Scenario: user edition sales manager type
Given there is the user with name 'User edition', position 'User edition', login 'User edition', password 'User edition', role 'администратор'
And the user navigates to the user page with login 'User edition'
When the user clicks the edit button on the user card page
And the user inputs values in the user page element fields
| elementName | inputText |
| role | Директор сети |
And the user clicks the create new user button
Then the user checks the user page elements values
| role | Директор сети |
When the user clicks on the users list page link
Then the user checks the user with 'User edition' login is present
And the user checks the user with 'User edition' sku has 'role' element equal to 'Директор сети' on amounts page

Scenario: user edition Commercial Director type
Given there is the user with name 'User edition', position 'User edition', login 'User edition', password 'User edition', role 'администратор'
And the user navigates to the user page with login 'User edition'
When the user clicks the edit button on the user card page
And the user inputs values in the user page element fields
| elementName | inputText |
| role | Коммерческий директор сети |
And the user clicks the create new user button
Then the user checks the user page elements values
| role | Коммерческий директор сети |
When the user clicks on the users list page link
Then the user checks the user with 'User edition' login is present
And the user checks the user with 'User edition' sku has 'role' element equal to 'Коммерческий директор сети' on amounts page

Scenario: user edition section chief type
Given there is the user with name 'User edition', position 'User edition', login 'User edition', password 'User edition', role 'администратор'
And the user navigates to the user page with login 'User edition'
When the user clicks the edit button on the user card page
And the user inputs values in the user page element fields
| elementName | inputText |
| role | Заведующим отделом |
And the user clicks the create new user button
Then the user checks the user page elements values
| role | Заведующим отделом |
When the user clicks on the users list page link
Then the user checks the user with 'User edition' login is present
And the user checks the user with 'User edition' sku has 'role' element equal to 'Заведующим отделом' on amounts page

Scenario: user edition administrator type
Given there is the user with name 'User edition', position 'User edition', login 'User edition', password 'User edition', role 'администратор'
And the user navigates to the user page with login 'User edition'
When the user clicks the edit button on the user card page
And the user inputs values in the user page element fields
| elementName | inputText |
| role | администратор |
And the user clicks the create new user button
Then the user checks the user page elements values
| role | администратор |
When the user clicks on the users list page link
Then the user checks the user with 'User edition' login is present
And the user checks the user with 'User edition' sku has 'role' element equal to 'администратор' on amounts page

!--
!--Тест на ссылку при создании и редактировании
the user clicks on the users list page link
!--Тест на ссылку при просмотре карточки


