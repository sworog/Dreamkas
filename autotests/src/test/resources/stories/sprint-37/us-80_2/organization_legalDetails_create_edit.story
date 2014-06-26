Meta:
@sprint_37
@us_80.2

Narrative:
Как владелец торговой точки,
Я хочу добавить в систему юридические реквизиты,
Что бы в дальнейшем использовать эти данные в документах

Scenario: Create legal notes on organization

Meta:
@smoke

Given the user opens the authorization page
And the user logs in using 'owner@lighthouse.pro' userName and 'lighthouse' password
And user have organization with name 'organization-s37us802'
And user is on organization page name 'organization-s37us802'
When user clicks legal details link
And user selects legal details type 'Индивидуальный предприниматель'
And user fill legal details inputs
| elementName | value |
| fullName | ООО "organization-s37us802" |
| legalAddress | СПб, Васи Томчака, д 35, оф. 302 |
| inn | 631401231404 |
| okpo | 0161644627 |
| ogrnip | 308631716200038 |
| certificateNumber | 2356 254558654846552124 |
| certificateDate | 17.12.2004 |
And user clicks save legal details button
Then the user sees success message 'Данные успешно сохранены'
And user checks legal details fields data
Given user is on organization page name 'organization-s37us802'
When user clicks legal details link
Then user checks legal details fields data
