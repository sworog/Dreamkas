Meta:

Narrative:
As a user
I want to perform an action
So that I can achieve a business goal

Scenario: Can't delete not empty category from group page
Given there is the category with name 'subCategoryTestCategoryValidation' related to group named 'subCategoryTestGroup'
And there is the subCategory with name 'subCategoryTest11' related to group named 'subCategoryTestGroup' and category named 'subCategoryTestCategoryValidation'
And the user navigates to the group with name 'subCategoryTestGroup'
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user opens pop up menu of category 'subCategoryTestGroup' element
And the user deletes element through pop up menu
Then the user checks alert text is equal to 'Необходимо удалить все группы из класса'
When the user clicks on end edition link and ends the edition
And the user logs out

Scenario: Can't delete not empty category from catalog page
Given there is the category with name 'subCategoryTestCategoryValidation' related to group named 'subCategoryTestGroup'
And there is the subCategory with name 'subCategoryTest10' related to group named 'subCategoryTestGroup' and category named 'subCategoryTestCategoryValidation'
And the user opens catalog page
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user opens pop up menu of category 'subCategoryTestGroup' element
And the user deletes element through pop up menu
Then the user checks alert text is equal to 'Необходимо удалить все группы из класса'
When the user clicks on end edition link and ends the edition
And the user logs out

Scenario: Can't delete not empty category from category page
Given there is the category with name 'subCategoryTestCategoryValidation' related to group named 'subCategoryTestGroup'
And there is the subCategory with name 'subCategoryTest12' related to group named 'subCategoryTestGroup' and category named 'subCategoryTestCategoryValidation'
And the user navigates to the category with name 'subCategoryTestCategoryValidation' related to group named 'subCategoryTestGroup'
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user opens pop up menu of category 'subCategoryTestGroup' element
And the user deletes element through pop up menu
Then the user checks alert text is equal to 'Необходимо удалить все группы из класса'
When the user clicks on end edition link and ends the edition
And the user logs out

Scenario: subCategory validation name good - 100 symbols
Given there is the category with name 'subCategoryTestCategoryValidation' related to group named 'subCategoryTestGroup'
And the user navigates to the category with name 'subCategoryTestCategoryValidation' related to group named 'subCategoryTestGroup'
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user clicks create new subCategory button
And the user generates charData with '100' number in the 'name' pop up field
Then the user checks 'name' pop up field contains only '100' symbols
When the user clicks the create new subCategory button in pop up
Then the user sees no error messages
When the user clicks on end edition link and ends the edition
And the user logs out

Scenario: subCategory validation name invalid - 101 symbols
Given there is the category with name 'subCategoryTestCategoryValidation' related to group named 'subCategoryTestGroup'
And the user navigates to the category with name 'subCategoryTestCategoryValidation' related to group named 'subCategoryTestGroup'
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user clicks create new subCategory button
And the user generates charData with '101' number in the 'name' pop up field
Then the user checks 'name' pop up field contains only '101' symbols
When the user clicks the create new subCategory button in pop up
Then the user sees error messages
| error message |
| Не более 100 символов |
When the user clicks on end edition link and ends the edition
And the user logs out

Scenario: subCategory validation name is required
Given there is the category with name 'subCategoryTestCategoryValidation' related to group named 'subCategoryTestGroup'
And the user navigates to the category with name 'subCategoryTestCategoryValidation' related to group named 'subCategoryTestGroup'
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user clicks create new subCategory button
And the user clicks the create new subCategory button in pop up
Then the user sees error messages
| error message |
| Заполните это поле |
When the user clicks on end edition link and ends the edition
And the user logs out

Scenario: subCategory validation - can't create group with equal name
Given there is the category with name 'subCategoryTestCategoryValidation' related to group named 'subCategoryTestGroup'
And there is the subCategory with name 'subCategoryTestEqualName' related to group named 'subCategoryTestGroup' and category named 'subCategoryTestCategoryValidation'
And the user navigates to the category with name 'subCategoryTestCategoryValidation' related to group named 'subCategoryTestGroup'
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
Then the user checks the subCategory with 'subCategoryTestEqualName' name is present
When the user clicks create new subCategory button
When the user inputs 'subCategoryTestEqualName' in 'name' field of pop up
And the user clicks the create new subCategory button in pop up
Then the user sees error messages
| error message |
| Такая подкатегория уже есть |
When the user clicks on end edition link and ends the edition
And the user logs out

Scenario: subCategory edit validation name good - 100 symbols
Given there is the category with name 'subCategoryTestCategoryValidation' related to group named 'subCategoryTestGroup'
And there is the subCategory with name 'edit subCategoryTest' related to group named 'subCategoryTestGroup' and category named 'subCategoryTestCategoryValidation'
And the user navigates to the category with name 'subCategoryTestCategoryValidation' related to group named 'subCategoryTestGroup'
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user opens pop up menu of 'edit subCategoryTest' element
And the user edits the element name through pop up menu
And the user generates charData with '100' number with string char 'f' in the 'name' pop up field
Then the user checks 'name' pop up field contains only '100' symbols
When the user clicks the create new subCategory button in pop up
Then the user sees no error messages
When the user clicks on end edition link and ends the edition
And the user logs out

Scenario: subCategory edit validation name invalid - 101 symbols
Given there is the category with name 'subCategoryTestCategoryValidation' related to group named 'subCategoryTestGroup'
And there is the subCategory with name 'edit subCategoryTest1' related to group named 'subCategoryTestGroup' and category named 'subCategoryTestCategoryValidation'
And the user navigates to the category with name 'subCategoryTestCategoryValidation' related to group named 'subCategoryTestGroup'
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user opens pop up menu of 'edit subCategoryTest1' element
And the user edits the element name through pop up menu
And the user generates charData with '101' number in the 'name' pop up field
Then the user checks 'name' pop up field contains only '101' symbols
When the user clicks the create new subCategory button in pop up
Then the user sees error messages
| error message |
| Не более 100 символов |
When the user discards pop up menu changes
And the user clicks on end edition link and ends the edition
And the user logs out

Scenario: subCategory edit validation name is required
Given there is the category with name 'subCategoryTestCategoryValidation' related to group named 'subCategoryTestGroup'
And there is the subCategory with name 'edit subCategoryTest1' related to group named 'subCategoryTestGroup' and category named 'subCategoryTestCategoryValidation'
And the user navigates to the category with name 'subCategoryTestCategoryValidation' related to group named 'subCategoryTestGroup'
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user opens pop up menu of 'edit subCategoryTest1' element
And the user edits the element name through pop up menu
When the user inputs '' in 'name' field of pop up
When the user clicks the create new subCategory button in pop up
Then the user sees error messages
| error message |
| Заполните это поле |
When the user discards pop up menu changes
And the user clicks on end edition link and ends the edition
And the user logs out

Scenario: subCategory edit validation - can't edit the group name to the group name already created
Given there is the category with name 'subCategoryTestCategoryValidation' related to group named 'subCategoryTestGroup'
And there is the subCategory with name 'edit subCategoryTest2' related to group named 'subCategoryTestGroup' and category named 'subCategoryTestCategoryValidation'
And the user navigates to the category with name 'subCategoryTestCategoryValidation' related to group named 'subCategoryTestGroup'
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user opens pop up menu of 'edit subCategoryTest2' element
And the user edits the element name through pop up menu
When the user inputs 'subCategoryTestEqualName' in 'name' field of pop up
When the user clicks the create new subCategory button in pop up
Then the user sees error messages
| error message |
| Такая подкатегория уже есть |
When the user discards pop up menu changes
And the user clicks on end edition link and ends the edition
And the user logs out

