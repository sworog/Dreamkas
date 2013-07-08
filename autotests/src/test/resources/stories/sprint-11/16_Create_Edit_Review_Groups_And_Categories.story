16 Создание, редактирование, просмотр групп и категорий товарного классификатора

Narrative:
Как коммерческий директор,
Я хочу создавать, редактироватьи и просматривать структуру классов и групп товарного классификатора,
Чтобы управлять ассортиментом по классам и группам

Scenario: group create from catalog page
Given the user opens catalog page
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user creates new group with name 'First group create'
Then the user checks the group with 'First group create' name is present
When the user clicks on end edition link and ends the edition
Then the user checks the group with 'First group create' name is present
When the user logs out

Scenario: group delete from catalog page
Given the user opens catalog page
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
When the user creates new group with name 'group delete from catalog'
Then the user checks the group with 'group delete from catalog' name is present
When the user opens pop up menu of 'group delete from catalog' element
And the user deletes element through pop up menu
Then the user checks the group with 'group delete from catalog' name is not present
When the user clicks on end edition link and ends the edition
Then the user checks the group with 'group delete from catalog' name is not present
When the user logs out

Scenario: group delete from group page
Given the user opens catalog page
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
When the user creates new group with name 'group delete from group page'
Then the user checks the group with 'group delete from group page' name is present
When the user clicks on the group name 'group delete from group page'
And the user opens pop up menu of 'group delete from group page' element
And the user deletes element through pop up menu
Then the user checks the group with 'group delete from group page' name is not present
When the user clicks on end edition link and ends the edition
Then the user checks the group with 'group delete from group page' name is not present
Given the user opens catalog page
Then the user checks the group with 'group delete from group page' name is not present
When the user logs out

Scenario: group edit from catalog page
Given the user opens catalog page
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user creates new group with name 'group edits from catalog'
Then the user checks the group with 'group edits from catalog' name is present
When the user opens pop up menu of 'group edits from catalog' element
And the user edits the element name through pop up menu
When the user inputs 'new group edits from catalog' in 'name' field of pop up
And the user accept pop up menu changes
Then the user checks the group with 'new group edits from catalog' name is present
When the user clicks on end edition link and ends the edition
Then the user checks the group with 'new group edits from catalog' name is present
When the user logs out

Scenario: group edit from group page
Given the user opens catalog page
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user creates new group with name 'group edits from group page'
Then the user checks the group with 'group edits from group page' name is present
When the user clicks on the group name 'group edits from group page'
And the user opens pop up menu of 'group edits from group page' element
And the user edits the element name through pop up menu
When the user inputs 'new group edits from group page' in 'name' field of pop up
And the user accept pop up menu changes
When the user clicks on end edition link and ends the edition
Then the user checks the group with 'new group edits from group page' name is present
Given the user opens catalog page
Then the user checks the group with 'new group edits from group page' name is present
When the user logs out

Scenario: group edit cancel from catalog page
Given the user opens catalog page
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user creates new group with name 'group edits from catalog cancel'
Then the user checks the group with 'group edits from catalog cancel' name is present
When the user opens pop up menu of 'group edits from catalog cancel' element
And the user edits the element name through pop up menu
When the user inputs 'new group edits from catalog cancel' in 'name' field of pop up
And the user discards pop up menu changes
When the user clicks on end edition link and ends the edition
Then the user checks the group with 'new group edits from catalog cancel' name is not present
And the user checks the group with 'group edits from catalog cancel' name is present
When the user logs out

Scenario: group edit cancel from group page
Given the user opens catalog page
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user creates new group with name 'group edits cancel from group page'
Then the user checks the group with 'group edits cancel from group page' name is present
When the user clicks on the group name 'group edits cancel from group page'
And the user opens pop up menu of 'group edits cancel from group page' element
And the user edits the element name through pop up menu
When the user inputs 'new group edits cancel from group page' in 'name' field of pop up
And the user discards pop up menu changes
When the user clicks on end edition link and ends the edition
Then the user checks the group with 'group edits cancel from group page' name is present
And the user checks the group with 'new group edits cancel from group page' name is not present
Given the user opens catalog page
Then the user checks the group with 'group edits cancel from group page' name is present
And the user checks the group with 'new group edits cancel from group page' name is not present
When the user logs out

Scenario: category create from group page
Given the user opens catalog page
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user creates new group with name 'GcFcP'
Then the user checks the group with 'GcFcP' name is present
When the user clicks on the group name 'GcFcP'
And the user creates new category with name 'First category create'
And the user clicks on end edition link and ends the edition
Then the user checks the category with 'First category create' name is present
Given the user opens catalog page
Then the user checks the category with 'First category create' name is related to group 'GcFcP'
When the user logs out

Scenario: category delete from catalog
Given the user opens catalog page
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user creates new group with name 'GDFC'
Then the user checks the group with 'GDFC' name is present
When the user clicks on the group name 'GDFC'
And the user creates new category with name 'category delete from catalog'
Then the user checks the category with 'category delete from catalog' name is present
When the user opens pop up menu of category 'category delete from catalog' element
And the user deletes element through pop up menu
Then the user checks the category with 'category delete from catalog' name is not present
When the user clicks on end edition link and ends the edition
Given the user opens catalog page
Then the user checks the category with 'category delete from catalog' name is not present
When the user logs out

Scenario: category delete from group page
Given the user opens catalog page
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user creates new group with name 'GDFCP'
Then the user checks the group with 'GDFCP' name is present
When the user clicks on the group name 'GDFCP'
And the user creates new category with name 'category delete from group page'
Then the user checks the category with 'category delete from group page' name is present
Given the user opens catalog page
When the user clicks on start edition link and starts the edition
And the user opens pop up menu of category 'category delete from group page' element
And the user deletes element through pop up menu
Then the user checks the category with 'category delete from group page' name is not present
When the user clicks on end edition link and ends the edition
Then the user checks the category with 'category delete from group page' name is not present
When the user logs out

Scenario: category edit from catalog
Given the user opens catalog page
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user creates new group with name 'GEDC'
Then the user checks the group with 'GEDC' name is present
When the user clicks on the group name 'GEDC'
And the user creates new category with name 'category edit cancel from catalog'
Then the user checks the category with 'category edit cancel from catalog' name is present
When the user opens pop up menu of category 'category edit cancel from catalog' element
And the user edits the element name through pop up menu
When the user inputs 'new category edit from catalog' in 'name' field of pop up
And the user accept pop up menu changes
Then the user checks the category with 'new category edit from catalog' name is present
When the user clicks on end edition link and ends the edition
Given the user opens catalog page
Then the user checks the category with 'new category edit from catalog' name is present
When the user logs out

Scenario: category edit from group page
Given the user opens catalog page
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user creates new group with name 'GEFCP'
Then the user checks the group with 'GEFCP' name is present
When the user clicks on the group name 'GEFCP'
And the user creates new category with name 'category edit from catalog'
Then the user checks the category with 'category edit from catalog' name is present
Given the user opens catalog page
When the user clicks on start edition link and starts the edition
And the user opens pop up menu of category 'category edit from catalog' element
And the user edits the element name through pop up menu
When the user inputs 'new category edit from catalog' in 'name' field of pop up
And the user accept pop up menu changes
Then the user checks the category with 'new category edit from catalog' name is present
When the user clicks on end edition link and ends the edition
Then the user checks the category with 'new category edit from catalog' name is present
When the user logs out

Scenario: category edit cancel from catalog
Given the user opens catalog page
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user creates new group with name 'GECFC'
Then the user checks the group with 'GECFC' name is present
When the user clicks on the group name 'GECFC'
And the user creates new category with name 'category edit cancel from catalog'
Then the user checks the category with 'category edit cancel from catalog' name is present
When the user opens pop up menu of category 'category edit cancel from catalog' element
And the user edits the element name through pop up menu
When the user inputs 'new category edit cancel from catalog' in 'name' field of pop up
And the user discards pop up menu changes
Then the user checks the category with 'new category edit cancel from catalog' name is not present
Then the user checks the category with 'category edit cancel from catalog' name is present
When the user clicks on end edition link and ends the edition
Given the user opens catalog page
Then the user checks the category with 'new category edit cancel from catalog' name is not present
Then the user checks the category with 'category edit cancel from catalog' name is present
When the user logs out

Scenario: category edit cancel from group page
Given the user opens catalog page
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user creates new group with name 'GECFCP'
Then the user checks the group with 'GECFCP' name is present
When the user clicks on the group name 'GECFCP'
And the user creates new category with name 'category edit cancel from group page'
Then the user checks the category with 'category edit cancel from group page' name is present
Given the user opens catalog page
When the user clicks on start edition link and starts the edition
And the user opens pop up menu of category 'category edit cancel from group page' element
And the user edits the element name through pop up menu
When the user inputs 'new category edit cancel from group page' in 'name' field of pop up
And the user discards pop up menu changes
Then the user checks the category with 'new category edit cancel from group page' name is not present
Then the user checks the category with 'category edit cancel from group page' name is present
When the user clicks on end edition link and ends the edition
Then the user checks the category with 'new category edit cancel from group page' name is not present
Then the user checks the category with 'category edit cancel from group page' name is present
When the user logs out

Scenario: group left panel link check
Given there is the group with name 'group vng-300'
And the user navigates to the group with name 'group vng-300'
And the user logs in as 'commercialManager'
Then the user checks the group 'group vng-300' link is present on right panel
When the user clicks the group 'group vng-300' link on right panel
Then the user checks the group with 'group vng-300' name is present
When the user logs out
