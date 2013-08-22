Meta:
@sprint 15
@us 26

Narrative:
As Коммерческий директор
I want to назначить директора магазина
In order to предоставить ему соответствующие возможности и полномочия

Scenario: Promote store manager

Given there is the user with name 'promotedStoreManager', position 'promotedStoreManager', username 'promotedStoreManager', password 'lighthouse', role 'storeManager'
And there is created store with number 'PSM-1', address 'PSM-1', contacts 'PSM-1'
And user navigates to created store page
And the user logs in as 'commercialManager'
When user promotes store manager named 'promotedStoreManager'
Then user checks the promoted store manager is 'promotedStoreManager'

Scenario: Unpromote store manager

Given there is the user with name 'promotedStoreManager1', position 'promotedStoreManager1', username 'promotedStoreManager1', password 'lighthouse', role 'storeManager'
And there is created store with number 'USM-1', address 'USM-1', contacts 'USM-1'
And user navigates to created store page
And the user logs in as 'commercialManager'
When user promotes store manager named 'promotedStoreManager1'
Then user checks the promoted store manager is 'promotedStoreManager1'
When user unpromotes store manager named 'promotedStoreManager1'
Then user checks the promoted store manager is not 'promotedStoreManager1'

Scenario: Promote several store managers

Given there is the user with name 'promotedStoreManager2', position 'promotedStoreManager2', username 'promotedStoreManager2', password 'lighthouse', role 'storeManager'
Given there is the user with name 'promotedStoreManager3', position 'promotedStoreManager3', username 'promotedStoreManager3', password 'lighthouse', role 'storeManager'
And there is created store with number 'PSSM-1', address 'PSSM-1', contacts 'PSSM-1'
And user navigates to created store page
And the user logs in as 'commercialManager'
When user promotes store manager named 'promotedStoreManager2'
Then user checks the promoted store manager is 'promotedStoreManager2'
When user promotes store manager named 'promotedStoreManager3'
Then user checks the promoted store manager is 'promotedStoreManager3'
Then user checks the promoted store manager is 'promotedStoreManager2'

Scenario: Can't promote not store manager - admin

Given there is the user with name 'promotedAdministrator', position 'promotedAdministrator', username 'promotedAdministrator', password 'lighthouse', role 'administrator'
And there is created store with number 'CPNSM-A', address 'CPNSM-A', contacts 'CPNSM-A'
And user navigates to created store page
And the user logs in as 'commercialManager'
When user try to promote not store manager named 'promotedAdministrator'
Then user checks the promoted store manager is not 'promotedAdministrator'

Scenario: Can't promote not store manager - commercialManager

Given there is the user with name 'promotedCommercialManager', position 'promotedCommercialManager', username 'promotedCommercialManager', password 'lighthouse', role 'commercialManager'
And there is created store with number 'CPNSM-C', address 'CPNSM-C', contacts 'CPNSM-C'
And user navigates to created store page
And the user logs in as 'commercialManager'
When user try to promote not store manager named 'promotedCommercialManager'
Then user checks the promoted store manager is not 'promotedCommercialManager'

Scenario: Can't promote not store manager - departmentManager

Given there is the user with name 'promotedDepartmentManager', position 'promotedDepartmentManager', username 'promotedDepartmentManager', password 'lighthouse', role 'administrator'
And there is created store with number 'CPNSM-D', address 'CPNSM-D', contacts 'CPNSM-D'
And user navigates to created store page
And the user logs in as 'commercialManager'
When user try to promote not store manager named 'promotedDepartmentManager'
Then user checks the promoted store manager is not 'promotedDepartmentManager'

Scenario: Store manager manage only one store

Given there is the user with name 'promotedStoreManager4', position 'promotedStoreManager4', username 'promotedStoreManager4', password 'lighthouse', role 'storeManager'
And there is created store with number 'SMMOOS', address 'SMMOOS', contacts 'SMMOOS'
And user navigates to created store page
And the user logs in as 'commercialManager'
When user promotes store manager named 'promotedStoreManager4'
Then user checks the promoted store manager is 'promotedStoreManager4'
Given there is created store with number 'SMMOOS-1', address 'SMMOOS-1', contacts 'SMMOOS-1'
And user navigates to created store page
When user try to promote not store manager named 'promotedStoreManager4'
Then user checks the promoted store manager is not 'promotedStoreManager4'

Scenario: Store manager can view managed store card

Given there is the user with name 'promotedStoreManager5', position 'promotedStoreManager5', username 'promotedStoreManager5', password 'lighthouse', role 'storeManager'
And there is created store with number 'SMCVMSC', address 'SMCVMSC', contacts 'SMCVMSC'
And user navigates to created store page
And the user logs in as 'commercialManager'
When user promotes store manager named 'promotedStoreManager5'
Then user checks the promoted store manager is 'promotedStoreManager5'
When the user logs out
Given the user navigates to the store with number 'SMCVMSC'
When the user logs in using 'promotedStoreManager5' userName and 'lighthouse' password
Then user checks the store number is eqaul to 'SMCVMSC'
And user checks the promoted store manager is 'promotedStoreManager5'





