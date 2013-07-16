17 Создание, редактирование, просмотр подкатегорий товарного классификатора

Narrative:
In order to управлять ассортиментом по подкатегориям
As a коммерческий директор,
I want to создавать, редактировать и просматривать структуру подкатегорий внутри категорий товарного классификатора,

Meta:
@us 17
@sprint 14

Scenario: subCategory create
Given there is the category with name 'subCategoryTestCategory' related to group named 'subCategoryTestGroup'
And the user navigates to the category with name 'subCategoryTestCategory' related to group named 'subCategoryTestGroup'
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user creates new subCategory with name 'First subCategory create'
Then the user checks the subCategory with 'First subCategory create' name is present
When the user clicks on end edition link and ends the edition
Then the user checks the subCategory with 'First subCategory create' name is present
When the user logs out

Scenario: subCategory delete
Given there is the subCategory with name 'subCategoryTestDelete' related to group named 'subCategoryTestGroup' and category named 'subCategoryTestCategory'
And the user navigates to the category with name 'subCategoryTestCategory' related to group named 'subCategoryTestGroup'
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
Then the user checks the subCategory with 'subCategoryTestDelete' name is present
When the user opens pop up menu of subCategory 'subCategoryTestDelete' element
And the user deletes element through pop up menu
Then the user checks the subCategory with 'subCategoryTestDelete' name is not present
When the user clicks on end edition link and ends the edition
Then the user checks the subCategory with 'subCategoryTestDelete' name is not present
When the user logs out

Scenario: subCategory edit
Given there is the subCategory with name 'subCategoryTestEdit' related to group named 'subCategoryTestGroup' and category named 'subCategoryTestCategory'
And the user navigates to the category with name 'subCategoryTestCategory' related to group named 'subCategoryTestGroup'
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
Then the user checks the subCategory with 'subCategoryTestEdit' name is present
When the user opens pop up menu of subCategory 'subCategoryTestEdit' element
And the user edits the element name through pop up menu
When the user inputs 'new subCategoryTestEdit' in 'name' field of pop up
And the user accept pop up menu changes
Then the user checks the subCategory with 'new subCategoryTestEdit' name is present
When the user clicks on end edition link and ends the edition
Then the user checks the subCategory with 'new subCategoryTestEdit' name is present
When the user logs out

Scenario: subCategory edit cancel
Given there is the subCategory with name 'subCategoryTestEditCancel' related to group named 'subCategoryTestGroup' and category named 'subCategoryTestCategory'
And the user navigates to the category with name 'subCategoryTestCategory' related to group named 'subCategoryTestGroup'
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
Then the user checks the subCategory with 'subCategoryTestEditCancel' name is present
When the user opens pop up menu of subCategory 'subCategoryTestEditCancel' element
And the user edits the element name through pop up menu
When the user inputs 'new subCategoryTestEditCancel' in 'name' field of pop up
And the user discards pop up menu changes
Then the user checks the subCategory with 'new subCategoryTestEditCancel' name is not present
And the user checks the subCategory with 'subCategoryTestEditCancel' name is present
When the user clicks on end edition link and ends the edition
Then the user checks the subCategory with 'new subCategoryTestEditCancel' name is not present
And the user checks the subCategory with 'subCategoryTestEditCancel' name is present
When the user logs out
