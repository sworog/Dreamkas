22 Создание/редактирование/просмотр магазина

Narrative:
In order to управлять торговой сетью
As a коммерческий директор
I want to создавать, редактировать и просмотривать магазины торговой сети

Meta:
@sprint 14
@us 22

Scenario: Create new store from stores list page
Given user is on stores list page
And the user logs in as 'commercialManager'
When When user clicks create new store button
And user fills store form with following data
| elementName | value |
| number | store22 |
| address | ул. Профессора Попова д.37б, 4 этаж |
| contacts | тел.: +7 (812) 331-2255\nфакс: +7 (812) 331-2256  |
And user clicks store form create button
Then user checks store data in list
When user clicks on store row in list
Then user checks store card data
When the user logs out

Scenario: Create new store direct from create new store page
Given the user is on create store page
And the user logs in as 'commercialManager'
When user fills store form with following data
| elementName | value |
| number | store221 |
| address | ул. Профессора Попова д.37б, 4 этаж |
| contacts | тел.: +7 (812) 331-2255\nфакс: +7 (812) 331-2256  |
And user clicks store form create button
Then user checks store data in list
When user clicks on store row in list
Then user checks store card data
When the user logs out

Scenario: Edit store

Meta:
@debug us:22:edit

Given there is created store with number 'store222', address 'ул. Профессора Попова д.37б, 5 этаж', contacts 'тел.: +7 (812) 331-2255'
And user navigates to created store page
And the user logs in as 'commercialManager'
When user clicks edit button on store card page
And user fills store form with following data
| elementName | value |
| number | store222 |
| address | ул. Профессора Попова д.37б, 7 этаж |
| contacts | факс: +7 (812) 331-2256  |
And user clicks store form save button
Then user checks store card data
When the user logs out
