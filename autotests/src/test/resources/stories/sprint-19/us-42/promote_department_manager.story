Meta:
@sprint_19
@us_42

Narrative:
As a коммерческий директор
I want to назначить заведующего отделом магазина,
In In order to предоставить ему соответсвующие возможности и полномочия

Scenario: Promote department manager

Given there is the user with name 'promotedDepartmentManager', position 'promotedDepartmentManager', username 'promotedDepartmentManager', password 'lighthouse', role 'departmentManager'
And there is created store with number 'PDM-1', address 'PDM-1', contacts 'PDM-1'
And user navigates to created store page
And the user logs in as 'commercialManager'
When user promotes department manager named 'promotedDepartmentManager'
Then user checks the promoted department manager is 'promotedDepartmentManager'

Scenario: Unpromote department manager

Given there is the user with name 'promotedDepartmentManager1', position 'promotedDepartmentManager1', username 'promotedDepartmentManager1', password 'lighthouse', role 'departmentManager'
And there is created store with number 'UDM-1', address 'UDM-1', contacts 'UDM-1'
And user navigates to created store page
And the user logs in as 'commercialManager'
When user promotes department manager named 'promotedDepartmentManager1'
Then user checks the promoted store manager is 'promotedDepartmentManager1'
When user unpromotes department manager named 'promotedDepartmentManager1'
Then user checks the promoted department manager is not 'promotedDepartmentManager1'

Scenario: Promote several department managers

Given there is the user with name 'promotedDepartmentManager2', position 'promotedDepartmentManager2', username 'promotedDepartmentManager2', password 'lighthouse', role 'departmentManager'
Given there is the user with name 'promotedDepartmentManager3', position 'promotedDepartmentManager3', username 'promotedDepartmentManager3', password 'lighthouse', role 'departmentManager'
And there is created store with number 'PSDM-1', address 'PSDM-1', contacts 'PSDM-1'
And user navigates to created store page
And the user logs in as 'commercialManager'
When user promotes department manager named 'promotedDepartmentManager2'
Then user checks the promoted department manager is 'promotedDepartmentManager2'
When user promotes department manager named 'promotedDepartmentManager3'
Then user checks the promoted department manager is 'promotedDepartmentManager3'
Then user checks the promoted department manager is 'promotedDepartmentManager2'

Scenario: Can't promote not department manager - admin

Given there is the user with name 'promotedAdministrator', position 'promotedAdministrator', username 'promotedAdministrator', password 'lighthouse', role 'administrator'
And there is created store with number 'CPNDM-A', address 'CPNDM-A', contacts 'CPNDM-A'
And user navigates to created store page
And the user logs in as 'commercialManager'
When user try to promote not department manager named 'promotedAdministrator'
Then user checks the promoted department manager is not 'promotedAdministrator'

Scenario: Can't promote not department manager - commercialManager

Given there is the user with name 'promotedCommercialManager', position 'promotedCommercialManager', username 'promotedCommercialManager', password 'lighthouse', role 'commercialManager'
And there is created store with number 'CPNDM-C', address 'CPNDM-C', contacts 'CPNDM-C'
And user navigates to created store page
And the user logs in as 'commercialManager'
When user try to promote not department manager named 'promotedCommercialManager'
Then user checks the promoted department manager is not 'promotedCommercialManager'

Scenario: Can't promote not department manager - storeManager

Given there is the user with name 'promotedStoreManager6', position 'promotedStoreManager6', username 'promotedStoreManager6', password 'lighthouse', role 'storeManager'
And there is created store with number 'CPNDM-D', address 'CPNDM-D', contacts 'CPNDM-D'
And user navigates to created store page
And the user logs in as 'commercialManager'
When user try to promote not department manager named 'promotedStoreManager6'
Then user checks the promoted department manager is not 'promotedStoreManager6'

Scenario: Department manager manage only one store

Given there is the user with name 'promotedDepartmentManager4', position 'promotedDepartmentManager4', username 'promotedDepartmentManager4', password 'lighthouse', role 'departmentManager'
And there is created store with number 'DMMOOS', address 'DMMOOS', contacts 'DMMOOS'
And user navigates to created store page
And the user logs in as 'commercialManager'
When user promotes department manager named 'promotedDepartmentManager4'
Then user checks the promoted department manager is 'promotedDepartmentManager4'
Given there is created store with number 'DMMOOS-1', address 'DMMOOS-1', contacts 'DMMOOS-1'
And user navigates to created store page
When user try to promote not department manager named 'promotedDepartmentManager4'
Then user checks the promoted department manager is not 'promotedDepartmentManager4'

Scenario: Department manager dont see catalog link if has no store

Given there is the user with name 'promotedDepartmentManager5', position 'promotedDepartmentManager5', username 'promotedDepartmentManager5', password 'lighthouse', role 'departmentManager'
And the user opens the authorization page
When the user logs in using 'promotedDepartmentManager5' userName and 'lighthouse' password
Then the user checks the catalog navigation menu item is not visible

Scenario: Department manager cant get in catalog if has no store

Given there is the user with name 'promotedDepartmentManager5', position 'promotedDepartmentManager5', username 'promotedDepartmentManager5', password 'lighthouse', role 'departmentManager'
And the user opens catalog page
When the user logs in using 'promotedDepartmentManager5' userName and 'lighthouse' password
Then the user sees the 403 error

Scenario: Department manager sees catalog link if has store

Given there is the user with name 'promotedDepartmentManager6', position 'promotedDepartmentManager6', username 'promotedDepartmentManager6', password 'lighthouse', role 'departmentManager'
And there is the store with number 'Dmsclihs-1' managed by department manager named 'promotedDepartmentManager6'
And the user opens the authorization page
When the user logs in using 'promotedDepartmentManager6' userName and 'lighthouse' password
Then the user checks the catalog navigation menu item is visible

Scenario: Department manager cant edit retail product price like a store manager

Given there is the user with name 'promotedDepartmentManager6', position 'promotedDepartmentManager6', username 'promotedDepartmentManager6', password 'lighthouse', role 'departmentManager'
And there is the store with number 'Dmsclihs-1' managed by department manager named 'promotedDepartmentManager6'
And there is the subCategory with name 'ProductsTestSubCategory' related to group named 'ProductsTestGroup' and category named 'ProductsTestCategory'
And the user sets subCategory 'ProductsTestSubCategory' mark up with max '30' and min '0' values
And there is the product with 'DMCERPPLAST' name, '34342478239479230479023' barcode, 'unit' type, '24' purchasePrice of group named 'ProductsTestGroup', category named 'ProductsTestCategory', subcategory named 'ProductsTestSubCategory'
And the user navigates to the product with name 'DMCERPPLAST'
When the user logs in using 'promotedDepartmentManager6' userName and 'lighthouse' password
Then the user checks the edit price button is not present
And the user checks the 'retailPrice' value is '31,20'