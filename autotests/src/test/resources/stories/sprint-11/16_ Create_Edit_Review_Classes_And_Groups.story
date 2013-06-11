16 Создание, редактирование, просмотр классов и групп товарного классификатора

Narrative:
Как коммерческий директор,
Я хочу создавать, редактироватьи и просматривать структуру классов и групп товарного классификатора,
Чтобы управлять ассортиментом по классам и группам

Scenario: Class create from catalog page
Given the user opens catalog page
When the user clicks on start edition link and starts the edition
And the user creates new class with name 'First class create'
Then the user checks the class with 'First class create' name is present
When the user clicks on end edition link and ends the edition
Then the user checks the class with 'First class create' name is present

Scenario: Class delete from catalog page
Given the user opens catalog page
When the user clicks on start edition link and starts the edition
When the user creates new class with name 'Class delete from catalog'
Then the user checks the class with 'Class delete from catalog' name is present
When the user opens pop up menu of 'Class delete from catalog' element
And the user deletes element through pop up menu
Then the user checks the class with 'Class delete from catalog' name is not present
When the user clicks on end edition link and ends the edition
Then the user checks the class with 'Class delete from catalog' name is not present

Scenario: Class delete from class page
Given the user opens catalog page
When the user clicks on start edition link and starts the edition
When the user creates new class with name 'Class delete from class page'
Then the user checks the class with 'Class delete from class page' name is present
When the user clicks on the class name 'Class delete from class page'
And the user opens pop up menu of 'Class delete from class page' element
And the user deletes element through pop up menu
Then the user checks the class with 'Class delete from class page' name is not present
When the user clicks on end edition link and ends the edition
Then the user checks the class with 'Class delete from class page' name is not present
Given the user opens catalog page
Then the user checks the class with 'Class delete from class page' name is not present

Scenario: Class edit from catalog page
Given the user opens catalog page
When the user clicks on start edition link and starts the edition
And the user creates new class with name 'Class edits from catalog'
Then the user checks the class with 'Class edits from catalog' name is present
When the user opens pop up menu of 'Class edits from catalog' element
And the user edits the element name through pop up menu
When the user inputs 'new Class edits from catalog' in 'name' field of pop up
And the user accept pop up menu changes
Then the user checks the class with 'new Class edits from catalog' name is present
When the user clicks on end edition link and ends the edition
Then the user checks the class with 'new Class edits from catalog' name is present

Scenario: Class edit from class page
Given the user opens catalog page
When the user clicks on start edition link and starts the edition
And the user creates new class with name 'Class edits from class page'
Then the user checks the class with 'Class edits from class page' name is present
When the user clicks on the class name 'Class edits from class page'
And the user opens pop up menu of 'Class edits from class page' element
And the user edits the element name through pop up menu
When the user inputs 'new Class edits from class page' in 'name' field of pop up
And the user accept pop up menu changes
When the user clicks on end edition link and ends the edition
Then the user checks the class with 'new Class edits from class page' name is present
Given the user opens catalog page
Then the user checks the class with 'new Class edits from class page' name is present

Scenario: Class edit cancel from catalog page
Given the user opens catalog page
When the user clicks on start edition link and starts the edition
And the user creates new class with name 'Class edits from catalog cancel'
Then the user checks the class with 'Class edits from catalog cancel' name is present
When the user opens pop up menu of 'Class edits from catalog cancel' element
And the user edits the element name through pop up menu
When the user inputs 'new Class edits from catalog cancel' in 'name' field of pop up
And the user discards pop up menu changes
When the user clicks on end edition link and ends the edition
Then the user checks the class with 'new Class edits from catalog cancel' name is not present
And the user checks the class with 'Class edits from catalog cancel' name is present

Scenario: Class edit cancel from class page
Given the user opens catalog page
When the user clicks on start edition link and starts the edition
And the user creates new class with name 'Class edits cancel from class page'
Then the user checks the class with 'Class edits cancel from class page' name is present
When the user clicks on the class name 'Class edits cancel from class page'
And the user opens pop up menu of 'Class edits cancel from class page' element
And the user edits the element name through pop up menu
When the user inputs 'new Class edits cancel from class page' in 'name' field of pop up
And the user discards pop up menu changes
When the user clicks on end edition link and ends the edition
Then the user checks the class with 'Class edits cancel from class page' name is present
And the user checks the class with 'new Class edits cancel from class page' name is not present
Given the user opens catalog page
Then the user checks the class with 'Class edits cancel from class page' name is present
And the user checks the class with 'new Class edits cancel from class page' name is not present

Scenario: Group create from class page
Given the user opens catalog page
When the user clicks on start edition link and starts the edition
And the user creates new class with name 'GcFcP'
Then the user checks the class with 'GcFcP' name is present
When the user clicks on the class name 'GcFcP'
And the user creates new group with name 'First group create'
And the user clicks on end edition link and ends the edition
Then the user checks the group with 'First group create' name is present
Given the user opens catalog page
Then the user checks the group with 'First group create' name is related to class 'GcFcP'

Scenario: Group delete from catalog
Given the user opens catalog page
When the user clicks on start edition link and starts the edition
And the user creates new class with name 'GDFC'
Then the user checks the class with 'GDFC' name is present
When the user clicks on the class name 'GDFC'
And the user creates new group with name 'Group delete from catalog'
Then the user checks the group with 'Group delete from catalog' name is present
When the user opens pop up menu of group 'Group delete from catalog' element
And the user deletes element through pop up menu
Then the user checks the group with 'Group delete from catalog' name is not present
When the user clicks on end edition link and ends the edition
Given the user opens catalog page
Then the user checks the group with 'Group delete from catalog' name is not present

Scenario: Group delete from class page
Given the user opens catalog page
When the user clicks on start edition link and starts the edition
And the user creates new class with name 'GDFCP'
Then the user checks the class with 'GDFCP' name is present
When the user clicks on the class name 'GDFCP'
And the user creates new group with name 'Group delete from class page'
Then the user checks the group with 'Group delete from class page' name is present
Given the user opens catalog page
When the user clicks on start edition link and starts the edition
And the user opens pop up menu of group 'Group delete from class page' element
And the user deletes element through pop up menu
Then the user checks the group with 'Group delete from class page' name is not present
When the user clicks on end edition link and ends the edition
Then the user checks the group with 'Group delete from class page' name is not present

Scenario: Group edit from catalog
Given the user opens catalog page
When the user clicks on start edition link and starts the edition
And the user creates new class with name 'GEDC'
Then the user checks the class with 'GEDC' name is present
When the user clicks on the class name 'GEDC'
And the user creates new group with name 'Group edit cancel from catalog'
Then the user checks the group with 'Group edit cancel from catalog' name is present
When the user opens pop up menu of group 'Group edit cancel from catalog' element
And the user edits the element name through pop up menu
When the user inputs 'new group edit from catalog' in 'name' field of pop up
And the user accept pop up menu changes
Then the user checks the group with 'new group edit from catalog' name is present
When the user clicks on end edition link and ends the edition
Given the user opens catalog page
Then the user checks the group with 'new group edit from catalog' name is present

Scenario: Group edit from class page
Given the user opens catalog page
When the user clicks on start edition link and starts the edition
And the user creates new class with name 'GEFCP'
Then the user checks the class with 'GEFCP' name is present
When the user clicks on the class name 'GEFCP'
And the user creates new group with name 'Group edit from catalog'
Then the user checks the group with 'Group edit from catalog' name is present
Given the user opens catalog page
When the user clicks on start edition link and starts the edition
And the user opens pop up menu of group 'Group edit from catalog' element
And the user edits the element name through pop up menu
When the user inputs 'new group edit from catalog' in 'name' field of pop up
And the user accept pop up menu changes
Then the user checks the group with 'new group edit from catalog' name is present
When the user clicks on end edition link and ends the edition
Then the user checks the group with 'new group edit from catalog' name is present

Scenario: Group edit cancel from catalog
Given the user opens catalog page
When the user clicks on start edition link and starts the edition
And the user creates new class with name 'GECFC'
Then the user checks the class with 'GECFC' name is present
When the user clicks on the class name 'GECFC'
And the user creates new group with name 'Group edit cancel from catalog'
Then the user checks the group with 'Group edit cancel from catalog' name is present
When the user opens pop up menu of group 'Group edit cancel from catalog' element
And the user edits the element name through pop up menu
When the user inputs 'new group edit cancel from catalog' in 'name' field of pop up
And the user discards pop up menu changes
Then the user checks the group with 'new group edit cancel from catalog' name is not present
Then the user checks the group with 'Group edit cancel from catalog' name is present
When the user clicks on end edition link and ends the edition
Given the user opens catalog page
Then the user checks the group with 'new group edit cancel from catalog' name is not present
Then the user checks the group with 'Group edit cancel from catalog' name is present

Scenario: Group edit cancel from class page
Given the user opens catalog page
When the user clicks on start edition link and starts the edition
And the user creates new class with name 'GECFCP'
Then the user checks the class with 'GECFCP' name is present
When the user clicks on the class name 'GECFCP'
And the user creates new group with name 'Group edit cancel from class page'
Then the user checks the group with 'Group edit cancel from class page' name is present
Given the user opens catalog page
When the user clicks on start edition link and starts the edition
And the user opens pop up menu of group 'Group edit cancel from class page' element
And the user edits the element name through pop up menu
When the user inputs 'new group edit cancel from class page' in 'name' field of pop up
And the user discards pop up menu changes
Then the user checks the group with 'new group edit cancel from class page' name is not present
Then the user checks the group with 'Group edit cancel from class page' name is present
When the user clicks on end edition link and ends the edition
Then the user checks the group with 'new group edit cancel from class page' name is not present
Then the user checks the group with 'Group edit cancel from class page' name is present

Scenario: Class left panel link check
Given there is the class with name 'class vng-300'
And the user navigates to the klass with name 'class vng-300'
Then the user checks the class 'class vng-300' link is present on right panel
When the user clicks the class 'class vng-300' link on right panel
Then the user checks the class with 'class vng-300' name is present

