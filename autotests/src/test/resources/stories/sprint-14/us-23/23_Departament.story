23 Создание/редактирование/просмотр отделов магазина

Narrative:
Как комерческий директор
Я хочу создавать отделы в магазине
Что бы управлять ассортиментом магазинов

Meta:
@sprint_14
@us_23

Scenario: Create a new department in store

Given user is on stores list page
And the user logs in as 'commercialManager'
And there is created store with number 'sprint14-us23', address 'address sprint14-us23', contacts 'contacts sprint14-us23'
And user navigates to created store page
When user clicks create new department button
And user fills department form with following data
| elementName | value |
| number | sprint14-us23-department |
| name | department |
And user clicks department form submit button
Then user checks department card data

Scenario: View department in store

Given created default store with department 'department1', 'departmentName1'
Then user checks department card data
| elementName | value |
| number | department1 |
| name | departmentName1 |

Scenario: Edit a department

Given created default store with department 'departmentEdit1', 'Department for edit test'
When user clicks edit department link
And user fills department form with following data
| elementName | value |
| number | departmentEdited1 |
| name | Department for edit test (status edited) |
And user clicks department form submit button
Then user checks department card data
| elementName | value |
| number | departmentEdited1 |
| name | Department for edit test (status edited) |
