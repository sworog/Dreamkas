23 Создание/редактирование/просмотр отделов магазина

Narrative:
Как комерческий директор
Я хочу создавать отделы в магазине
Что бы управлять ассортиментом магазинов

Meta:
@sprint 14
@us 23

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
When the user logs out

