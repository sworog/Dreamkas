Meta:
@sprint 11
@us 16

Scenario: Can't delete not empty group from catalog page

Given there is the category with name 'category cdnecfcp' related to group named 'group cdnecfcp'
And the user opens catalog page
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user opens pop up menu of 'group cdnecfcp' element
And the user deletes element through pop up menu
Then the user checks alert text is equal to 'Группа не пуста. Сначала удалите из нее все категории'
When the user clicks on end edition link and ends the edition
And the user logs out

Scenario: Can't delete not empty group from group page

Given there is the category with name 'category cdnecfcp1' related to group named 'group cdnecfcp1'
And the user opens catalog page
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user clicks on the group name 'group cdnecfcp1'
And the user opens pop up menu of 'group cdnecfcp1' element
And the user deletes element through pop up menu
Then the user checks alert text is equal to 'Группа не пуста. Сначала удалите из нее все категории'
When the user clicks on end edition link and ends the edition
And the user logs out

Scenario: Group validation name good - 100 symbols

Given the user opens catalog page
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user clicks create new group button
And the user generates charData with '100' number in the 'name' pop up field
Then the user checks 'name' pop up field contains only '100' symbols
When the user clicks the create new group button in pop up
Then the user sees no error messages
When the user clicks on end edition link and ends the edition
And the user logs out

Scenario: Group validation name invalid - 101 symbols

Given the user opens catalog page
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user clicks create new group button
And the user generates charData with '101' number in the 'name' pop up field
Then the user checks 'name' pop up field contains only '101' symbols
When the user clicks the create new group button in pop up
Then the user sees error messages
| error message |
| Не более 100 символов |
When the user clicks on end edition link and ends the edition
And the user logs out

Scenario: Group validation name is required

Given the user opens catalog page
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user clicks create new group button
When the user clicks the create new group button in pop up
Then the user sees error messages
| error message |
| Заполните это поле |
When the user clicks on end edition link and ends the edition
And the user logs out

Scenario: Group validation - can't create group with equal name

Given the user opens catalog page
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user creates new group with name 'CV-CCCWEN group'
Then the user checks the group with 'CV-CCCWEN group' name is present
When the user inputs 'CV-CCCWEN group' in 'name' field of pop up
And the user clicks the create new group button in pop up
Then the user sees error messages
| error message |
| Такая группа уже есть |
When the user clicks on end edition link and ends the edition
And the user logs out

Scenario: Group edit from catalog page - validation name good - 100 symbols

Given there is the group with name 'group vng-100'
And the user opens catalog page
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user opens pop up menu of 'group vng-100' element
And the user edits the element name through pop up menu
And the user generates charData with '100' number with string char 'b' in the 'name' pop up field
Then the user checks 'name' pop up field contains only '100' symbols
When the user accept pop up menu changes
Then the user sees no error messages
When the user clicks on end edition link and ends the edition
And the user logs out

Scenario: Group edit from catalog page -  validation name invalid - 101 symbols

Given there is the group with name 'group vng-101'
And the user opens catalog page
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user opens pop up menu of 'group vng-101' element
And the user edits the element name through pop up menu
And the user generates charData with '101' number in the 'name' pop up field
Then the user checks 'name' pop up field contains only '101' symbols
When the user accept pop up menu changes
Then the user sees error messages
| error message |
| Не более 100 символов |
When the user discards pop up menu changes
And the user clicks on end edition link and ends the edition
And the user logs out

Scenario: Group edit from catalog page - validation name is required

Given there is the group with name 'group vng-101'
And the user opens catalog page
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user opens pop up menu of 'group vng-101' element
And the user edits the element name through pop up menu
When the user inputs '' in 'name' field of pop up
And the user accept pop up menu changes
Then the user sees error messages
| error message |
| Заполните это поле |
When the user discards pop up menu changes
And the user clicks on end edition link and ends the edition
And the user logs out

Scenario: Group edit from catalog page - validation - can't create group with equal name

Given there is the group with name 'group vng-101'
And there is the group with name 'group vng-102'
And the user opens catalog page
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user opens pop up menu of 'group vng-102' element
And the user edits the element name through pop up menu
When the user inputs 'group vng-101' in 'name' field of pop up
And the user accept pop up menu changes
Then the user sees error messages
| error message |
| Такая группа уже есть |
When the user discards pop up menu changes
And the user clicks on end edition link and ends the edition
And the user logs out

Scenario: Group edit from group page - validation name good - 100 symbols

Given there is the group with name 'group vng-103'
And the user navigates to the group with name 'group vng-103'
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user opens pop up menu of 'group vng-103' element
And the user edits the element name through pop up menu
And the user generates charData with '100' number with string char 'c' in the 'name' pop up field
Then the user checks 'name' pop up field contains only '100' symbols
When the user accept pop up menu changes
Then the user sees no error messages
When the user clicks on end edition link and ends the edition
And the user logs out

Scenario: Group edit from group page - validation name invalid - 101 symbols

Given there is the group with name 'group vng-104'
And the user navigates to the group with name 'group vng-104'
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user opens pop up menu of 'group vng-104' element
And the user edits the element name through pop up menu
And the user generates charData with '101' number in the 'name' pop up field
Then the user checks 'name' pop up field contains only '101' symbols
When the user accept pop up menu changes
Then the user sees error messages
| error message |
| Не более 100 символов |
When the user discards pop up menu changes
And the user clicks on end edition link and ends the edition
And the user logs out

Scenario: Group edit from group page - validation name is required

Given there is the group with name 'group vng-105'
And the user navigates to the group with name 'group vng-105'
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user opens pop up menu of 'group vng-105' element
And the user edits the element name through pop up menu
When the user inputs '' in 'name' field of pop up
And the user accept pop up menu changes
Then the user sees error messages
| error message |
| Заполните это поле |
When the user discards pop up menu changes
And the user clicks on end edition link and ends the edition
And the user logs out

Scenario: Group edit from group page - validation - can't create group with equal name

Given there is the group with name 'group vng-106'
And there is the group with name 'group vng-107'
And the user navigates to the group with name 'group vng-107'
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user opens pop up menu of 'group vng-107' element
And the user edits the element name through pop up menu
When the user inputs 'group vng-106' in 'name' field of pop up
And the user accept pop up menu changes
Then the user sees error messages
| error message |
| Такая группа уже есть |
When the user discards pop up menu changes
And the user clicks on end edition link and ends the edition
And the user logs out

Scenario: Category validation name good - 100 symbols

Given there is the group with name 'GVNG group'
And the user navigates to the group with name 'GVNG group'
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user clicks create new category button
And the user generates charData with '100' number in the 'name' pop up field
Then the user checks 'name' pop up field contains only '100' symbols
When the user clicks the create new group button in pop up
Then the user sees no error messages
When the user logs out

Scenario: Category validation name invalid - 101 symbols

Given there is the group with name 'GVNG group'
And the user navigates to the group with name 'GVNG group'
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user clicks create new category button
And the user generates charData with '101' number in the 'name' pop up field
Then the user checks 'name' pop up field contains only '101' symbols
When the user clicks the create new group button in pop up
Then the user sees error messages
| error message |
| Не более 100 |
When the user logs out

Scenario: Category validation name is required

Given there is the group with name 'GVNG group'
And the user navigates to the group with name 'GVNG group'
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user clicks create new category button
When the user clicks the create new group button in pop up
Then the user sees error messages
| error message |
| Заполните это поле |
When the user logs out

Scenario: Category validation - can't create category with equal name

Given there is the group with name 'GVNG group'
And the user navigates to the group with name 'GVNG group'
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user clicks create new category button
And the user inputs 'GVHDT45' in 'name' field of pop up
When the user clicks the create new group button in pop up
Then the user checks the category with 'GVHDT45' name is present
When the user inputs 'GVHDT45' in 'name' field of pop up
When the user clicks the create new group button in pop up
Then the user sees error messages
| error message |
| Категория с таким названием уже существует в этой группе |
When the user logs out

Scenario: Category edit from catalog page - validation name good - 100 symbols

Given there is the category with name 'category edit vng-100' related to group named 'category edit'
And the user opens catalog page
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user opens pop up menu of category 'category edit vng-100' element
And the user edits the element name through pop up menu
And the user generates charData with '100' number with string char 'd' in the 'name' pop up field
Then the user checks 'name' pop up field contains only '100' symbols
When the user accept pop up menu changes
Then the user sees no error messages
When the user clicks on end edition link and ends the edition
And the user logs out

Scenario: Category edit from catalog page - validation name invalid - 101 symbols

Given there is the category with name 'category edit vng-101' related to group named 'category edit'
And the user opens catalog page
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user opens pop up menu of category 'category edit vng-101' element
And the user edits the element name through pop up menu
And the user generates charData with '101' number in the 'name' pop up field
Then the user checks 'name' pop up field contains only '101' symbols
When the user accept pop up menu changes
Then the user sees error messages
| error message |
| Не более 100 |
When the user clicks on end edition link and ends the edition
And the user logs out

Scenario: Category edit from catalog page - validation name is required

Given there is the category with name 'category edit vng-102' related to group named 'category edit'
And the user opens catalog page
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user opens pop up menu of category 'category edit vng-102' element
And the user edits the element name through pop up menu
When the user inputs '' in 'name' field of pop up
When the user accept pop up menu changes
Then the user sees error messages
| error message |
| Заполните это поле |
When the user clicks on end edition link and ends the edition
And the user logs out

Scenario: Category edit from catalog page - validation - can't create category with equal name

Given there is the category with name 'category edit vng-103' related to group named 'category edit'
And there is the category with name 'category edit vng-104' related to group named 'category edit'
And the user opens catalog page
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user opens pop up menu of category 'category edit vng-104' element
And the user edits the element name through pop up menu
When the user inputs 'category edit vng-103' in 'name' field of pop up
When the user accept pop up menu changes
Then the user sees error messages
| error message |
| Категория с таким названием уже существует в этой группе |
When the user clicks on end edition link and ends the edition
And the user logs out

Scenario: Category edit from group page - validation name good - 100 symbols

Given there is the category with name 'category edit vng-105' related to group named 'category edit'
And the user navigates to the group with name 'category edit'
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user opens pop up menu of category 'category edit vng-105' element
And the user edits the element name through pop up menu
And the user generates charData with '100' number in the 'name' pop up field
Then the user checks 'name' pop up field contains only '100' symbols
When the user accept pop up menu changes
Then the user sees no error messages
When the user clicks on end edition link and ends the edition
And the user logs out

Scenario: Category edit from group page - validation name invalid - 101 symbols

Given there is the category with name 'category edit vng-106' related to group named 'category edit'
And the user navigates to the group with name 'category edit'
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user opens pop up menu of category 'category edit vng-106' element
And the user edits the element name through pop up menu
And the user generates charData with '101' number in the 'name' pop up field
Then the user checks 'name' pop up field contains only '101' symbols
When the user accept pop up menu changes
Then the user sees error messages
| error message |
| Не более 100 |
When the user clicks on end edition link and ends the edition
And the user logs out

Scenario: Category edit from group page - validation name is required

Given there is the category with name 'category edit vng-107' related to group named 'category edit'
And the user navigates to the group with name 'category edit'
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user opens pop up menu of category 'category edit vng-107' element
And the user edits the element name through pop up menu
When the user inputs '' in 'name' field of pop up
When the user accept pop up menu changes
Then the user sees error messages
| error message |
| Заполните это поле |
When the user clicks on end edition link and ends the edition
And the user logs out

Scenario: Category edit from group page - validation - can't create category with equal name

Given there is the category with name 'category edit vng-108' related to group named 'category edit'
And there is the category with name 'category edit vng-109' related to group named 'category edit'
And the user navigates to the group with name 'category edit'
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user opens pop up menu of category 'category edit vng-109' element
And the user edits the element name through pop up menu
When the user inputs 'category edit vng-108' in 'name' field of pop up
When the user accept pop up menu changes
Then the user sees error messages
| error message |
| Категория с таким названием уже существует в этой группе |
When the user clicks on end edition link and ends the edition
When the user logs out
