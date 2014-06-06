Meta:
@sprint_15
@us_27

Narrative:
As a Коммерческий директор
I want to установить диапазон наценки для группы, категории, подкатегории
In order to управлять розничной ценой на товары и удерживать плановый уровень маржинальности

Scenario: Group mark up range set

Meta:
@smoke

Given there is the group with name 'GroupMarkUp-1'
And the user navigates to the group with name 'GroupMarkUp-1'
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user switches to 'group' properties tab
And the user sets min mark up value to '1'
And the user sets max mark up value to '1'
And the user clicks save mark up button
Then the user sees success message 'Свойства успешно сохранены'
And the user checks the stored mark up values
When the user clicks on end edition link and ends the edition
And the user logs out

Scenario: Group mark up set - checks no inheritance

Meta:
@smoke

Given there is the subCategory with name 'subCategorymarkUp-2' related to group named 'groupMarkUp-2' and category named 'categoryMarkUp-2'
And the user navigates to the group with name 'groupMarkUp-2'
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user switches to 'group' properties tab
And the user sets min mark up value to '1'
And the user sets max mark up value to '1'
And the user clicks save mark up button
Then the user checks the stored mark up values
When the user switches to 'group' main tab
And the user clicks on the category name 'categoryMarkUp-2'
And the user switches to 'category' properties tab
Then the user checks mark up values are empty
When the user switches to 'category' main tab
And the user clicks on the subCategory name 'subCategorymarkUp-2'
And the user switches to 'subCategory' properties tab
Then the user checks mark up values are empty
When the user clicks on end edition link and ends the edition
And the user logs out

Scenario: Category mark up range set

Meta:
@smoke

Given there is the category with name 'categoryMarkUp-4' related to group named 'groupMarkUp-4'
And the user navigates to the category with name 'categoryMarkUp-4' related to group named 'groupMarkUp-4'
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user switches to 'category' properties tab
And the user sets min mark up value to '1'
And the user sets max mark up value to '1'
And the user clicks save mark up button
Then the user sees success message 'Свойства успешно сохранены'
And the user checks the stored mark up values
When the user clicks on end edition link and ends the edition
And the user logs out

Scenario: Category mark up range set - no inheritance

Meta:
@smoke

Given there is the subCategory with name 'subCategorymarkUp-5' related to group named 'groupMarkUp-5' and category named 'categoryMarkUp-5'
And the user navigates to the category with name 'categoryMarkUp-5' related to group named 'groupMarkUp-5'
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user switches to 'category' properties tab
And the user sets min mark up value to '1'
And the user sets max mark up value to '1'
And the user clicks save mark up button
Then the user checks the stored mark up values
When the user switches to 'category' main tab
And the user clicks on the subCategory name 'subCategorymarkUp-5'
And the user switches to 'subCategory' properties tab
Then the user checks mark up values are empty
When the user clicks on end edition link and ends the edition
And the user logs out

Scenario: SubCategory mark up range set

Meta:
@smoke

Given there is the subCategory with name 'subCategorymarkUp-7' related to group named 'groupMarkUp-7' and category named 'categoryMarkUp-7'
And the user navigates to the subCategory 'subCategorymarkUp-7', category 'categoryMarkUp-7', group 'groupMarkUp-7' product list page
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user switches to 'subCategory' properties tab
And the user sets min mark up value to '1'
And the user sets max mark up value to '1'
And the user clicks save mark up button
Then the user sees success message 'Свойства успешно сохранены'
And the user checks the stored mark up values
When the user clicks on end edition link and ends the edition
And the user logs out

Scenario: Category mark up reset (group mark up is already set)

Meta:
@smoke

Given there is the subCategory with name 'subCategorymarkUp-8' related to group named 'groupMarkUp-8' and category named 'categoryMarkUp-8'
And the user navigates to the group with name 'groupMarkUp-8'
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user switches to 'group' properties tab
And the user sets min mark up value to '1'
And the user sets max mark up value to '1'
And the user clicks save mark up button
Then the user checks the stored mark up values
When the user switches to 'group' main tab
And the user clicks on the category name 'categoryMarkUp-8'
And the user switches to 'category' properties tab
And the user resets min mark up value to '12'
And the user resets max mark up value to '12'
And the user clicks save mark up button
Then the user checks the stored mark up values are new ones
When the user switches to 'category' main tab
And the user clicks on the subCategory name 'subCategorymarkUp-8'
And the user switches to 'subCategory' properties tab
Then the user checks mark up values are empty
When the user clicks on the group name 'groupMarkUp-8'
And the user switches to 'group' properties tab
Then the user checks the stored mark up values are not changed
When the user clicks on end edition link and ends the edition
And the user logs out

Scenario: SubCategory mark up reset (group, subCategory mark up is already set)

Meta:
@smoke

Given there is the subCategory with name 'subCategorymarkUp-9' related to group named 'groupMarkUp-9' and category named 'categoryMarkUp-9'
And the user navigates to the group with name 'groupMarkUp-9'
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user switches to 'group' properties tab
And the user sets min mark up value to '1'
And the user sets max mark up value to '1'
And the user clicks save mark up button
Then the user checks the stored mark up values
Given the user navigates to the subCategory 'subCategorymarkUp-9', category 'categoryMarkUp-9', group 'groupMarkUp-9' product list page
When the user clicks on start edition link and starts the edition
And the user switches to 'subCategory' properties tab
And the user resets min mark up value to '12'
And the user resets max mark up value to '12'
And the user clicks save mark up button
Then the user checks the stored mark up values are new ones
When the user switches to 'category' properties tab
Then the user checks mark up values are empty
When the user clicks on the group name 'groupMarkUp-9'
And the user switches to 'group' properties tab
Then the user checks the stored mark up values are not changed
When the user clicks on end edition link and ends the edition
And the user logs out

Scenario: SubCategory mark up reset (group, category mark up is already set)

Meta:
@smoke

Given there is the subCategory with name 'subCategorymarkUp-10' related to group named 'groupMarkUp-10' and category named 'categoryMarkUp-10'
And the user navigates to the group with name 'groupMarkUp-10'
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user switches to 'group' properties tab
And the user sets min mark up value to '1'
And the user sets max mark up value to '1'
And the user clicks save mark up button
Then the user checks the stored mark up values
When the user switches to 'group' main tab
And the user clicks on the category name 'categoryMarkUp-10'
And the user switches to 'category' properties tab
And the user sets min mark up value to '1'
And the user sets max mark up value to '1'
And the user clicks save mark up button
Then the user checks the stored mark up values
When the user switches to 'category' main tab
And the user clicks on the subCategory name 'subCategorymarkUp-10'
And the user switches to 'subCategory' properties tab
And the user sets new min mark up value to '10'
And the user sets new max mark up value to '10'
And the user clicks save mark up button
Then the user checks the stored mark up values are new ones
When the user switches to 'category' properties tab
Then the user checks the stored mark up values are not changed
When the user clicks on the group name 'groupMarkUp-10'
And the user switches to 'group' properties tab
Then the user checks the stored mark up values are not changed
When the user clicks on end edition link and ends the edition
And the user logs out

Scenario: Create category - check inheritance

Meta:
@smoke

Given there is the group with name 'groupMarkUp-11'
And the user navigates to the group with name 'groupMarkUp-11'
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user switches to 'group' properties tab
And the user sets min mark up value to '1'
And the user sets max mark up value to '1'
And the user clicks save mark up button
Then the user checks the stored mark up values
When the user switches to 'group' main tab
And the user clicks create new category button
And the user inputs 'categoryMarkUp-11' in 'name' field of pop up
And the user clicks the create new category button in pop up
And the user clicks on the category name 'categoryMarkUp-11'
When the user switches to 'category' properties tab
Then the user checks the stored mark up values
When the user clicks on end edition link and ends the edition
And the user logs out

Scenario: Create subCategory - check inheritance

Meta:
@smoke

Given there is the category with name 'categoryMarkUp-12' related to group named 'groupMarkUp-12'
And the user navigates to the category with name 'categoryMarkUp-12' related to group named 'groupMarkUp-12'
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user switches to 'category' properties tab
And the user sets min mark up value to '1'
And the user sets max mark up value to '1'
And the user clicks save mark up button
Then the user checks the stored mark up values
When the user switches to 'category' main tab
And the user creates new subCategory with name 'subCategorymarkUp-12'
And the user clicks on the subCategory name 'subCategorymarkUp-12'
And the user switches to 'subCategory' properties tab
Then the user checks the stored mark up values
When the user clicks on end edition link and ends the edition
And the user logs out

