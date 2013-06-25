
Scenario: Can't delete not empty class from catalog page
Given there is the group with name 'Group cdnecfcp' related to class named 'Class cdnecfcp'
And the user opens catalog page
And the user logs in as 'watchman'
When the user clicks on start edition link and starts the edition
And the user opens pop up menu of 'Class cdnecfcp' element
And the user deletes element through pop up menu
Then the user checks alert text is equal to 'Необходимо удалить все группы из класса'
When the user clicks on end edition link and ends the edition
And the user logs out

Scenario: Can't delete not empty class from class page
Given there is the group with name 'Group cdnecfcp1' related to class named 'Class cdnecfcp1'
And the user opens catalog page
And the user logs in as 'watchman'
When the user clicks on start edition link and starts the edition
And the user clicks on the class name 'Class cdnecfcp1'
And the user opens pop up menu of 'Class cdnecfcp1' element
And the user deletes element through pop up menu
Then the user checks alert text is equal to 'Необходимо удалить все группы из класса'
When the user clicks on end edition link and ends the edition
And the user logs out

Scenario: Class validation name good - 100 symbols
Given the user opens catalog page
And the user logs in as 'watchman'
When the user clicks on start edition link and starts the edition
And the user clicks create new class button
And the user generates charData with '100' number in the 'name' pop up field
Then the user checks 'name' pop up field contains only '100' symbols
When the user clicks the create new class button in pop up
Then the user sees no error messages
When the user clicks on end edition link and ends the edition
And the user logs out

Scenario: Class validation name invalid - 101 symbols
Given the user opens catalog page
And the user logs in as 'watchman'
When the user clicks on start edition link and starts the edition
And the user clicks create new class button
And the user generates charData with '101' number in the 'name' pop up field
Then the user checks 'name' pop up field contains only '101' symbols
When the user clicks the create new class button in pop up
Then the user sees error messages
| error message |
| Не более 100 символов |
When the user clicks on end edition link and ends the edition
And the user logs out

Scenario: Class validation name is required
Given the user opens catalog page
And the user logs in as 'watchman'
When the user clicks on start edition link and starts the edition
And the user clicks create new class button
When the user clicks the create new class button in pop up
Then the user sees error messages
| error message |
| Заполните это поле |
When the user clicks on end edition link and ends the edition
And the user logs out

Scenario: Class validation - can't create class with equal name
Given the user opens catalog page
And the user logs in as 'watchman'
When the user clicks on start edition link and starts the edition
And the user creates new class with name 'CV-CCCWEN class'
Then the user checks the class with 'CV-CCCWEN class' name is present
When the user inputs 'CV-CCCWEN class' in 'name' field of pop up
And the user clicks the create new class button in pop up
Then the user sees error messages
| error message |
| Такой класс уже есть |
When the user clicks on end edition link and ends the edition
And the user logs out

Scenario: Class edit from catalog page - validation name good - 100 symbols
Given there is the class with name 'class vng-100'
And the user opens catalog page
And the user logs in as 'watchman'
When the user clicks on start edition link and starts the edition
And the user opens pop up menu of 'class vng-100' element
And the user edits the element name through pop up menu
And the user generates charData with '100' number with string char 'b' in the 'name' pop up field
Then the user checks 'name' pop up field contains only '100' symbols
When the user accept pop up menu changes
Then the user sees no error messages
When the user clicks on end edition link and ends the edition
And the user logs out

Scenario: Class edit from catalog page -  validation name invalid - 101 symbols
Given there is the class with name 'class vng-101'
And the user opens catalog page
And the user logs in as 'watchman'
When the user clicks on start edition link and starts the edition
And the user opens pop up menu of 'class vng-101' element
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

Scenario: Class edit from catalog page - validation name is required
Given there is the class with name 'class vng-101'
And the user opens catalog page
And the user logs in as 'watchman'
When the user clicks on start edition link and starts the edition
And the user opens pop up menu of 'class vng-101' element
And the user edits the element name through pop up menu
When the user inputs '' in 'name' field of pop up
And the user accept pop up menu changes
Then the user sees error messages
| error message |
| Заполните это поле |
When the user discards pop up menu changes
And the user clicks on end edition link and ends the edition
And the user logs out

Scenario: Class edit from catalog page - validation - can't create class with equal name
Given there is the class with name 'class vng-101'
And there is the class with name 'class vng-102'
And the user opens catalog page
And the user logs in as 'watchman'
When the user clicks on start edition link and starts the edition
And the user opens pop up menu of 'class vng-102' element
And the user edits the element name through pop up menu
When the user inputs 'class vng-101' in 'name' field of pop up
And the user accept pop up menu changes
Then the user sees error messages
| error message |
| Такой класс уже есть |
When the user discards pop up menu changes
And the user clicks on end edition link and ends the edition
And the user logs out

Scenario: Class edit from class page - validation name good - 100 symbols
Given there is the class with name 'class vng-103'
And the user navigates to the klass with name 'class vng-103'
And the user logs in as 'watchman'
When the user clicks on start edition link and starts the edition
And the user opens pop up menu of 'class vng-103' element
And the user edits the element name through pop up menu
And the user generates charData with '100' number with string char 'c' in the 'name' pop up field
Then the user checks 'name' pop up field contains only '100' symbols
When the user accept pop up menu changes
Then the user sees no error messages
When the user clicks on end edition link and ends the edition
And the user logs out

Scenario: Class edit from class page - validation name invalid - 101 symbols
Given there is the class with name 'class vng-104'
And the user navigates to the klass with name 'class vng-104'
And the user logs in as 'watchman'
When the user clicks on start edition link and starts the edition
And the user opens pop up menu of 'class vng-104' element
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

Scenario: Class edit from class page - validation name is required
Given there is the class with name 'class vng-105'
And the user navigates to the klass with name 'class vng-105'
And the user logs in as 'watchman'
When the user clicks on start edition link and starts the edition
And the user opens pop up menu of 'class vng-105' element
And the user edits the element name through pop up menu
When the user inputs '' in 'name' field of pop up
And the user accept pop up menu changes
Then the user sees error messages
| error message |
| Заполните это поле |
When the user discards pop up menu changes
And the user clicks on end edition link and ends the edition
And the user logs out

Scenario: Class edit from class page - validation - can't create class with equal name
Given there is the class with name 'class vng-106'
And there is the class with name 'class vng-107'
And the user navigates to the klass with name 'class vng-107'
And the user logs in as 'watchman'
When the user clicks on start edition link and starts the edition
And the user opens pop up menu of 'class vng-107' element
And the user edits the element name through pop up menu
When the user inputs 'class vng-106' in 'name' field of pop up
And the user accept pop up menu changes
Then the user sees error messages
| error message |
| Такой класс уже есть |
When the user discards pop up menu changes
And the user clicks on end edition link and ends the edition
And the user logs out

Scenario: Group validation name good - 100 symbols
Given there is the class with name 'GVNG class'
And the user navigates to the klass with name 'GVNG class'
And the user logs in as 'watchman'
When the user clicks on start edition link and starts the edition
And the user clicks create new group button
And the user generates charData with '100' number in the 'name' pop up field
Then the user checks 'name' pop up field contains only '100' symbols
When the user clicks the create new class button in pop up
Then the user sees no error messages
When the user logs out

Scenario: Group validation name invalid - 101 symbols
Given there is the class with name 'GVNG class'
And the user navigates to the klass with name 'GVNG class'
And the user logs in as 'watchman'
When the user clicks on start edition link and starts the edition
And the user clicks create new group button
And the user generates charData with '101' number in the 'name' pop up field
Then the user checks 'name' pop up field contains only '101' symbols
When the user clicks the create new class button in pop up
Then the user sees error messages
| error message |
| Не более 100 |
When the user logs out

Scenario: Group validation name is required
Given there is the class with name 'GVNG class'
And the user navigates to the klass with name 'GVNG class'
And the user logs in as 'watchman'
When the user clicks on start edition link and starts the edition
And the user clicks create new group button
When the user clicks the create new class button in pop up
Then the user sees error messages
| error message |
| Заполните это поле |
When the user logs out

Scenario: Group validation - can't create Group with equal name
Given there is the class with name 'GVNG class'
And the user navigates to the klass with name 'GVNG class'
And the user logs in as 'watchman'
When the user clicks on start edition link and starts the edition
And the user clicks create new group button
And the user inputs 'GVHDT45' in 'name' field of pop up
When the user clicks the create new class button in pop up
Then the user checks the group with 'GVHDT45' name is present
When the user inputs 'GVHDT45' in 'name' field of pop up
When the user clicks the create new class button in pop up
Then the user sees error messages
| error message |
| Группа с таким названием уже существует в этом классе |
When the user logs out

Scenario: Group edit from catalog page - validation name good - 100 symbols
Given there is the group with name 'group edit vng-100' related to class named 'group edit'
And the user opens catalog page
And the user logs in as 'watchman'
When the user clicks on start edition link and starts the edition
And the user opens pop up menu of group 'group edit vng-100' element
And the user edits the element name through pop up menu
And the user generates charData with '100' number with string char 'd' in the 'name' pop up field
Then the user checks 'name' pop up field contains only '100' symbols
When the user accept pop up menu changes
Then the user sees no error messages
When the user clicks on end edition link and ends the edition
And the user logs out

Scenario: Group edit from catalog page - validation name invalid - 101 symbols
Given there is the group with name 'group edit vng-101' related to class named 'group edit'
And the user opens catalog page
And the user logs in as 'watchman'
When the user clicks on start edition link and starts the edition
And the user opens pop up menu of group 'group edit vng-101' element
And the user edits the element name through pop up menu
And the user generates charData with '101' number in the 'name' pop up field
Then the user checks 'name' pop up field contains only '101' symbols
When the user accept pop up menu changes
Then the user sees error messages
| error message |
| Не более 100 |
When the user clicks on end edition link and ends the edition
And the user logs out

Scenario: Group edit from catalog page - validation name is required
Given there is the group with name 'group edit vng-102' related to class named 'group edit'
And the user opens catalog page
And the user logs in as 'watchman'
When the user clicks on start edition link and starts the edition
And the user opens pop up menu of group 'group edit vng-102' element
And the user edits the element name through pop up menu
When the user inputs '' in 'name' field of pop up
When the user accept pop up menu changes
Then the user sees error messages
| error message |
| Заполните это поле |
When the user clicks on end edition link and ends the edition
And the user logs out

Scenario: Group edit from catalog page - validation - can't create Group with equal name
Given there is the group with name 'group edit vng-103' related to class named 'group edit'
And there is the group with name 'group edit vng-104' related to class named 'group edit'
And the user opens catalog page
And the user logs in as 'watchman'
When the user clicks on start edition link and starts the edition
And the user opens pop up menu of group 'group edit vng-104' element
And the user edits the element name through pop up menu
When the user inputs 'group edit vng-103' in 'name' field of pop up
When the user accept pop up menu changes
Then the user sees error messages
| error message |
| Группа с таким названием уже существует в этом классе |
When the user clicks on end edition link and ends the edition
And the user logs out

Scenario: Group edit from class page - validation name good - 100 symbols
Given there is the group with name 'group edit vng-105' related to class named 'group edit'
And the user navigates to the klass with name 'group edit'
And the user logs in as 'watchman'
When the user clicks on start edition link and starts the edition
And the user opens pop up menu of group 'group edit vng-105' element
And the user edits the element name through pop up menu
And the user generates charData with '100' number in the 'name' pop up field
Then the user checks 'name' pop up field contains only '100' symbols
When the user accept pop up menu changes
Then the user sees no error messages
When the user clicks on end edition link and ends the edition
And the user logs out

Scenario: Group edit from class page - validation name invalid - 101 symbols
Given there is the group with name 'group edit vng-106' related to class named 'group edit'
And the user navigates to the klass with name 'group edit'
And the user logs in as 'watchman'
When the user clicks on start edition link and starts the edition
And the user opens pop up menu of group 'group edit vng-106' element
And the user edits the element name through pop up menu
And the user generates charData with '101' number in the 'name' pop up field
Then the user checks 'name' pop up field contains only '101' symbols
When the user accept pop up menu changes
Then the user sees error messages
| error message |
| Не более 100 |
When the user clicks on end edition link and ends the edition
And the user logs out

Scenario: Group edit from class page - validation name is required
Given there is the group with name 'group edit vng-107' related to class named 'group edit'
And the user navigates to the klass with name 'group edit'
And the user logs in as 'watchman'
When the user clicks on start edition link and starts the edition
And the user opens pop up menu of group 'group edit vng-107' element
And the user edits the element name through pop up menu
When the user inputs '' in 'name' field of pop up
When the user accept pop up menu changes
Then the user sees error messages
| error message |
| Заполните это поле |
When the user clicks on end edition link and ends the edition
And the user logs out

Scenario: Group edit from class page - validation - can't create Group with equal name
Given there is the group with name 'group edit vng-108' related to class named 'group edit'
And there is the group with name 'group edit vng-109' related to class named 'group edit'
And the user navigates to the klass with name 'group edit'
And the user logs in as 'watchman'
When the user clicks on start edition link and starts the edition
And the user opens pop up menu of group 'group edit vng-109' element
And the user edits the element name through pop up menu
When the user inputs 'group edit vng-108' in 'name' field of pop up
When the user accept pop up menu changes
Then the user sees error messages
| error message |
| Группа с таким названием уже существует в этом классе |
When the user clicks on end edition link and ends the edition
When the user logs out
