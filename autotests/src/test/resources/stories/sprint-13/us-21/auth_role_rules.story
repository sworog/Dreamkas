21 Разграничение прав доступа пользователя

Narrative:
In order to фокусироваться на достижении своих целей
As a пользователь
I want to после авторизациии в системе работать только со своими данными и функционалом,

Meta:
@sprint_13
@us_21

Scenario: Administrator role username checking

Given the user opens the authorization page
And the user logs in as 'watchman'
Then the user checks that authorized is 'watchman' user

Scenario: CommercialManager role username checking

Given the user opens the authorization page
And the user logs in as 'commercialManager'
Then the user checks that authorized is 'commercialManager' user

Scenario: StoreManager role username checking

Given the user opens the authorization page
And the user logs in as 'storeManager'
Then the user checks that authorized is 'storeManager' user

Scenario: DepartmentManager role username checking

Given the user opens the authorization page
And the user logs in as 'departmentManager'
Then the user checks that authorized is 'departmentManager' user

Scenario: Administrator role valid rules - dashboard links - users

Given the user opens the authorization page
And the user logs in as 'watchman'
Then the user checks the users navigation menu item is visible

Scenario: CommercialManager role valid rules - dashboard links - catalog

Given the user opens the authorization page
And the user logs in as 'commercialManager'
Then the user checks the catalog navigation menu item is visible

Scenario: DepartmentManager role valid rules - dashboard links - invoices

Given skipped. Info: 'Test is not actual', Details: 'It became not actual after sprint 19 - us-42, us-44'
Given the user opens the authorization page
And the user logs in as 'departmentManager'
Then the user checks the invoices navigation menu item is visible

Scenario: DepartmentManager role valid rules - dashboard links - balance

Given skipped. Info: 'Test is not actual', Details: 'It became not actual after sprint 19 - us-42, us-43'

Given the user opens the authorization page
And the user logs in as 'departmentManager'
Then the user checks the dashboard link to 'balance' section is present

Scenario: DepartmentManager role valid rules - dashboard links - writeOffs

Given skipped. Info: 'Test is not actual', Details: 'It became not actual after sprint 19 - us-42, us-45'
Given the user opens the authorization page
And the user logs in as 'departmentManager'
Then the user checks the writeOffs navigation menu item is visible

Scenario: Administrator role invalid rules - dashboard links - catalog

Given the user opens the authorization page
And the user logs in as 'watchman'
Then the user checks the catalog navigation menu item is not visible

Scenario: Administrator role invalid rules - dashboard links - invoices

Given the user opens the authorization page
And the user logs in as 'watchman'
Then the user checks the invoices navigation menu item is not visible

Scenario: Administrator role invalid rules - dashboard links - balance

Given skipped. Info: 'Test is not actual', Details: 'It became not actual after sprint 19 - us-42, us-43'

Given the user opens the authorization page
And the user logs in as 'watchman'
Then the user checks the dashboard link to 'balance' section is not present

Scenario: Administrator role invalid rules - dashboard links - writeOffs

Given the user opens the authorization page
And the user logs in as 'watchman'
Then the user checks the writeOffs navigation menu item is not visible

Scenario: Commercial manager role invalid rules - dashboard links - invoices

Given the user opens the authorization page
And the user logs in as 'commercialManager'
Then the user checks the invoices navigation menu item is not visible

Scenario: Commercial manager role invalid rules - dashboard links - balance

Given skipped. Info: 'Test is not actual', Details: 'It became not actual after sprint 19'

Given the user opens the authorization page
And the user logs in as 'commercialManager'
Then the user checks the dashboard link to 'balance' section is present

Scenario: Commercial manager role invalid rules - dashboard links - writeOffs

Given the user opens the authorization page
And the user logs in as 'commercialManager'
Then the user checks the writeOffs navigation menu item is not visible

Scenario: Commercial manager role invalid rules - dashboard links - users

Given the user opens the authorization page
And the user logs in as 'commercialManager'
Then the user checks the users navigation menu item is not visible

Scenario: StoreManager role invalid rules - dashboard links - users

Given the user opens the authorization page
And the user logs in as 'storeManager'
Then the user checks the users navigation menu item is not visible

Scenario: StoreManager role invalid rules - dashboard links - users

Given the user opens the authorization page
And the user logs in as 'storeManager'
Then the user checks the users navigation menu item is not visible

Scenario: StoreManager role invalid rules - dashboard links - catalog

Given the user opens the authorization page
And the user logs in as 'storeManager'
Then the user checks the catalog navigation menu item is not visible

Scenario: StoreManager role invalid rules - dashboard links - invoices

Given the user opens the authorization page
And the user logs in as 'storeManager'
Then the user checks the invoices navigation menu item is not visible

Scenario: StoreManager role invalid rules - dashboard links - balance

Given skipped. Info: 'Test is not actual', Details: 'It became not actual after sprint 19 - us-42, us-43'

Given the user opens the authorization page
And the user logs in as 'storeManager'
Then the user checks the dashboard link to 'balance' section is not present

Scenario: StoreManager role invalid rules - dashboard links - writeOffs

Given the user opens the authorization page
And the user logs in as 'storeManager'
Then the user checks the writeOffs navigation menu item is not visible

Scenario: DepartmentManager role invalid rules - dashboard links - users

Given the user opens the authorization page
And the user logs in as 'departmentManager'
Then the user checks the users navigation menu item is not visible

Scenario: Administrator role invalid rules - unauthorised access from product create page link

Given the user opens the authorization page
And the user logs in as 'watchman'
And the user is on the product create page
Then the user sees the 403 error

Scenario: Administrator role invalid rules - unauthorised access from product list page link

Given the user opens the authorization page
And the user logs in as 'watchman'
And the user is on the product list page
Then the user sees the 403 error

Scenario: Administrator role invalid rules - unauthorised access from catalog page link

Given the user opens the authorization page
And the user logs in as 'watchman'
And the user opens catalog page
Then the user sees the 403 error

Scenario: Administrator role invalid rules - unauthorised access from ivoice list page link

Given the user opens the authorization page
And the user logs in as 'watchman'
And the user is on the invoice list page
Then the user sees the 403 error

Scenario: Administrator role invalid rules - unauthorised access from ivoice create page link

Given the user opens the authorization page
And the user logs in as 'watchman'
And the user opens the default store invoice create page
Then the user sees the 403 error

Scenario: Administrator role invalid rules - unauthorised access from amount list page link

Given the user opens the authorization page
And the user logs in as 'watchman'
And the user opens amount list page
Then the user sees the 403 error

Scenario: Administrator role invalid rules - unauthorised access from write off create page link

Given the user opens the authorization page
And the user logs in as 'watchman'
And the user opens the write off create page
Then the user sees the 403 error

Scenario: Administrator role invalid rules - unauthorised access from write off list page link

Given the user opens the authorization page
And the user logs in as 'watchman'
And the user opens write off list page
Then the user sees the 403 error

Scenario: CommercialManager role invalid rules - unauthorised access from users page link

Given the user opens the authorization page
And the user logs in as 'commercialManager'
And the user opens create new user page
Then the user sees the 403 error

Scenario: CommercialManager role invalid rules - unauthorised access from users create page link

Given the user opens the authorization page
And the user logs in as 'commercialManager'
And the user is on the users list page
Then the user sees the 403 error

Scenario: CommercialManager role invalid rules - unauthorised access from invoices link

Given the user opens the authorization page
And the user logs in as 'commercialManager'
And the user is on the invoice list page
Then the user sees the 403 error

Scenario: CommercialManager role invalid rules - unauthorised access from invoices create page link

Given the user opens the authorization page
And the user logs in as 'commercialManager'
And the user opens the default store invoice create page
Then the user sees the 403 error

Scenario: CommercialManager role invalid rules - unauthorised access from balance link

Given skipped. Info: 'Test is not actual', Details: 'It became not actual after sprint 19'
Given the user opens the authorization page
And the user logs in as 'commercialManager'
And the user opens amount list page
Then the user dont see the 403 error

Scenario: CommercialManager role invalid rules - unauthorised access from writeOffs link

Given the user opens the authorization page
And the user logs in as 'commercialManager'
And the user opens write off list page
Then the user sees the 403 error

Scenario: CommercialManager role invalid rules - unauthorised access from writeOffs create page link

Given the user opens the authorization page
And the user logs in as 'commercialManager'
And the user opens the write off create page
Then the user sees the 403 error

Scenario: StoreManager role invalid rules - unauthorised access from users page link

Given the user opens the authorization page
And the user logs in as 'storeManager'
And the user opens create new user page
Then the user sees the 403 error

Scenario: StoreManager role invalid rules - unauthorised access from users create page link

Given the user opens the authorization page
And the user logs in as 'storeManager'
And the user is on the users list page
Then the user sees the 403 error

Scenario: StoreManager role invalid rules - unauthorised access from products page link

Given the user opens the authorization page
And the user logs in as 'storeManager'
And the user is on the product list page
Then the user sees the 403 error

Scenario: StoreManager role invalid rules - unauthorised access from products create page link

Given the user opens the authorization page
And the user logs in as 'storeManager'
And the user is on the product create page
Then the user sees the 403 error

Scenario: StoreManager role invalid rules - unauthorised access from invoices link

Given the user opens the authorization page
And the user logs in as 'storeManager'
And the user is on the invoice list page
Then the user sees the 403 error

Scenario: StoreManager role invalid rules - unauthorised access from invoices create page link

Given the user opens the authorization page
And the user logs in as 'storeManager'
And the user opens the default store invoice create page
Then the user sees the 403 error

Scenario: StoreManager role invalid rules - unauthorised access from balance link

Given the user opens the authorization page
And the user logs in as 'storeManager'
And the user opens amount list page
Then the user sees the 403 error

Scenario: StoreManager role invalid rules - unauthorised access from writeOffs link

Given the user opens the authorization page
And the user logs in as 'storeManager'
And the user opens write off list page
Then the user sees the 403 error

Scenario: StoreManager role invalid rules - unauthorised access from writeOffs create page link

Given the user opens the authorization page
And the user logs in as 'storeManager'
And the user opens the write off create page
Then the user sees the 403 error

Scenario: StoreManager role invalid rules - unauthorised access from catalog link

Given the user opens the authorization page
And the user logs in as 'storeManager'
And the user opens catalog page
Then the user sees the 403 error

Scenario: DepartmentManager role invalid rules - unauthorised access from users page link

Given the user opens the authorization page
And the user logs in as 'departmentManager'
And the user opens create new user page
Then the user sees the 403 error

Scenario: DepartmentManager role invalid rules - unauthorised access from users create page link

Given the user opens the authorization page
And the user logs in as 'departmentManager'
And the user is on the users list page
Then the user sees the 403 error

Scenario: DepartmentManager role invalid rules - unauthorised access from products create page link

Given the user opens the authorization page
And the user logs in as 'departmentManager'
And the user is on the product create page
Then the user sees the 403 error

Scenario: DepartmentManager role valid rules - authorised access to balance

Given skipped. Info: 'skipped', Details: 'no dashboard balance link anymore'

Given the user opens the authorization page
And the user logs in as 'departmentManager'
When user opens the dashboard 'balance' section
Then the user dont see the 403 error

Scenario: DepartmentManager role valid rules - authorised access to invoice list

Given the user opens the authorization page
And the user logs in as 'departmentManager'
When the user clicks the menu invoices item
Then the user dont see the 403 error

Scenario: DepartmentManager role valid rules - authorised access to invoice create

Given the user opens the authorization page
And the user logs in as 'departmentManager'
When the user clicks the menu invoices item
And the user clicks the create button on the invoice list page
Then the user dont see the 403 error

Scenario: DepartmentManager role valid rules - authorised access to writeOffs list

Given the user opens the authorization page
And the user logs in as 'departmentManager'
When the user clicks the menu writeOffs item
Then the user dont see the 403 error

Scenario: DepartmentManager role valid rules - authorised access to writeOffs create

Given the user opens the authorization page
And the user logs in as 'departmentManager'
When the user clicks the menu writeOffs item
And the user creates write off from write off list page
Then the user dont see the 403 error

Scenario: CommercialManager role valid rules - authorised access to catalog

Given the user opens the authorization page
And the user logs in as 'commercialManager'
When the user clicks the menu catalog item
Then the user dont see the 403 error

Scenario: Administrator role valid rules - authorised access to users list

Given the user opens the authorization page
And the user logs in as 'watchman'
When the user clicks the menu users item
Then the user dont see the 403 error

Scenario: Administrator role valid rules - authorised access to users create

Given the user opens the authorization page
And the user logs in as 'watchman'
When the user clicks the menu users item
And the user clicks the create new user button from users list page
Then the user dont see the 403 error

Scenario: DepartmentManager - no edit button for products card

Given there is the product with 'IFBKG-119999' name, 'IFBKG-119999' sku, 'IFBKG-119999' barcode
And the user is on the product list page
And the user logs in as 'departmentManager'
When the user open the product card with 'IFBKG-119999' sku
Then the user sees no edit product button

Scenario: DepartmentManager - no create button for products list

Given the user is on the product list page
And the user logs in as 'departmentManager'
Then the user sees no create new product button

Scenario: Administrator role - user card view from dashboard

Given the user opens the authorization page
And the user logs in as 'watchman'
When the user opens menu navigation bar user card
Then the user checks the user page elements values
| elementName | value |
| name | watchman |
| position | Системный администратор |
| username | watchman |
| role | Системный администратор |

Scenario: Administrator role - user card view from dashboard - edit button is present

Given the user opens the authorization page
And the user logs in as 'watchman'
When the user opens menu navigation bar user card
Then the user sees user card edit button

Scenario: Administrator role - user card view from dashboard - users list link is present

Given the user opens the authorization page
And the user logs in as 'watchman'
When the user opens menu navigation bar user card
Then the user sees user card link to users list

Scenario: DepartmentManager role - user card view from dashboard

Given the user opens the authorization page
And the user logs in as 'departmentManager'
When the user opens menu navigation bar user card
Then the user checks the user page elements values
| elementName | value |
| name | departmentManager |
| position | Заведующий отделом |
| username | departmentManager |
| role | Заведующий отделом |

Scenario: DepartmentManager role - user card view from dashboard - edit button is not present

Given the user opens the authorization page
And the user logs in as 'departmentManager'
When the user opens menu navigation bar user card
Then the user sees no user card edit button

Scenario: DepartmentManager role - user card view from dashboard - users list link is not present

Given the user opens the authorization page
And the user logs in as 'departmentManager'
When the user opens menu navigation bar user card
Then the user sees no user card link to users list

Scenario: CommercialManager role - user card view from dashboard

Given the user opens the authorization page
And the user logs in as 'commercialManager'
When the user opens menu navigation bar user card
Then the user checks the user page elements values
| elementName | value |
| name | commercialManager |
| position | Коммерческий директор сети |
| username | commercialManager |
| role | Коммерческий директор сети |

Scenario: CommercialManager role - user card view from dashboard - edit button is not present

Given the user opens the authorization page
And the user logs in as 'commercialManager'
When the user opens menu navigation bar user card
Then the user sees no user card edit button

Scenario: CommercialManager role - user card view from dashboard - users list link is not present

Given the user opens the authorization page
And the user logs in as 'commercialManager'
When the user opens menu navigation bar user card
Then the user sees no user card link to users list

Scenario: StoreManager role - user card view from dashboard

Given the user opens the authorization page
And the user logs in as 'storeManager'
When the user opens menu navigation bar user card
Then the user checks the user page elements values
| elementName | value |
| name | storeManager |
| position | Директор магазина |
| username | storeManager |
| role | Директор магазина |

Scenario: StoreManager role - user card view from dashboard - edit button is not present

Given the user opens the authorization page
And the user logs in as 'storeManager'
When the user opens menu navigation bar user card
Then the user sees no user card edit button

Scenario: StoreManager role - user card view from dashboard - users list link is not present

Given the user opens the authorization page
And the user logs in as 'storeManager'
When the user opens menu navigation bar user card
Then the user sees no user card link to users list
