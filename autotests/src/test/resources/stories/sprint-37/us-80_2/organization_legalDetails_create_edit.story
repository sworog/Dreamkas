Meta:
@sprint_37
@us_80.2

Narrative:
Как владелец торговой точки,
Я хочу добавить в систему юридические реквизиты,
Что бы в дальнейшем использовать эти данные в документах

Scenario: Create legal details on organization IP

Meta:
@smoke

Given the user opens the authorization page
And the user logs in using 'owner@lighthouse.pro' userName and 'lighthouse' password
And user have organization with name 'organization-s37us802_1'
And user is on organization page name 'organization-s37us802_1'
When user clicks legal details link
And user selects legal details type 'Индивидуальный предприниматель'
And user fill legal details inputs
 | elementName | value |
 | fullName | ООО "organization-s37us802_1" |
 | legalAddress | СПб, Васи Томчака, д 35, оф. 302 |
 | inn | 631401231404 |
 | okpo | 0161644627 |
 | ogrnip | 308631716200038 |
 | certificateNumber | 2356 254558654846552124 |
 | certificateDate | 17.12.2004 |
And user clicks save legal details button
Then the user sees success message 'Данные успешно сохранены'
And user checks legal details fields data
Given user is on organization page name 'organization-s37us802_1'
When user clicks legal details link
Then user checks legal details fields data

Scenario: Create legal details on organization IP validation

Given the user opens the authorization page
And the user logs in using 'owner@lighthouse.pro' userName and 'lighthouse' password
And user have organization with name 'organization-s37us802_2'
And user is on organization page name 'organization-s37us802_2'
When user clicks legal details link
And user selects legal details type 'Индивидуальный предприниматель'
And user fill legal details inputs
 | elementName | value |
 | fullName | length301char01234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789T |
 | legalAddress | length301char01234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789T |
 | inn | 1234567890123 |
 | okpo | 12345678901 |
 | ogrnip | 1234567890123456 |
 | certificateNumber | 12345678901234567890123456 |
 | certificateDate | notDate |
And user clicks save legal details button
Then the user checks the element field 'fullName' has error message 'Не более 300 символов'
And the user checks the element field 'legalAddress' has error message 'Не более 300 символов'
And the user checks the element field 'inn' has error message 'ИНН должен быть ровно 12 цифр'
And the user checks the element field 'okpo' has error message 'ОКПО должно содержать 10 цифр'
And the user checks the element field 'ogrnip' has error message 'ОГРНИП должно содержать 15 цифр'
And the user checks the element field 'certificateNumber' has error message 'Не более 25 символов'
And the user checks the element field 'certificateDate' has error message 'Должна быть дата'
When user fill legal details inputs
 | elementName | value |
 | fullName | valid |
 | legalAddress | valid |
 | inn | 12345678901 |
 | okpo | 123456789 |
 | ogrnip | 12345678901234 |
 | certificateNumber | valid |
 | certificateDate | 1.12.2001 |
And user clicks save legal details button
Then the user checks the element field 'inn' has error message 'ИНН должен быть ровно 12 цифр'
And the user checks the element field 'okpo' has error message 'ОКПО должно содержать 10 цифр'
And the user checks the element field 'ogrnip' has error message 'ОГРНИП должно содержать 15 цифр'
When user fill legal details inputs
 | elementName | value |
 | fullName | valid |
 | legalAddress | valid |
 | inn | notNumeric12 |
 | okpo | notNumeric1 |
 | ogrnip | notNumeric123456 |
 | certificateNumber | valid |
 | certificateDate | 1.12.2001 |
And user clicks save legal details button
Then the user checks the element field 'inn' has error message 'ИНН должен быть ровно 12 цифр'
And the user checks the element field 'okpo' has error message 'ОКПО должно содержать 10 цифр'
And the user checks the element field 'ogrnip' has error message 'ОГРНИП должно содержать 15 цифр'
When user fill legal details inputs
 | elementName | value |
 | fullName | valid |
 | legalAddress | valid |
 | inn | 123456789012 |
 | okpo | 1234567890 |
 | ogrnip | 123456789012345 |
 | certificateNumber | valid |
 | certificateDate | 1.12.2001 |
And user clicks save legal details button
Then the user sees no error messages
And the user sees success message 'Данные успешно сохранены'

Scenario: Create legal details on organization legalEntity

Meta:
@smoke

Given the user opens the authorization page
And the user logs in using 'owner@lighthouse.pro' userName and 'lighthouse' password
And user have organization with name 'organization-s37us802_3'
And user is on organization page name 'organization-s37us802_3'
When user clicks legal details link
And user selects legal details type 'Юридическое лицо'
And user fill legal details inputs
 | elementName | value |
 | fullName | ООО "organization-s37us802_3" |
 | legalAddress | СПб, Васи Томчака, д 35, оф. 302 |
 | inn | 7825392986 |
 | kpp | 780201001 |
 | ogrn | 1037843021413 |
 | okpo | 47954132 |
And user clicks save legal details button
Then the user sees success message 'Данные успешно сохранены'
And user checks legal details fields data
Given user is on organization page name 'organization-s37us802_3'
When user clicks legal details link
Then user checks legal details fields data

Scenario: Create legal details on organization legalEntity validation

Given the user opens the authorization page
And the user logs in using 'owner@lighthouse.pro' userName and 'lighthouse' password
And user have organization with name 'organization-s37us802_4'
And user is on organization page name 'organization-s37us802_4'
When user clicks legal details link
And user selects legal details type 'Индивидуальный предприниматель'
And user fill legal details inputs
 | elementName | value |
 | fullName | length301char01234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789T |
 | legalAddress | length301char01234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789T |
 | inn | 12345678901 |
 | kpp | 1234567890 |
 | ogrn | 12345678901234 |
 | okpo | 123456789 |
And user clicks save legal details button
Then the user checks the element field 'fullName' has error message 'Не более 300 символов'
And the user checks the element field 'legalAddress' has error message 'Не более 300 символов'
And the user checks the element field 'inn' has error message 'ИНН должен быть ровно 10 цифр'
And the user checks the element field 'kpp' has error message 'КПП должен содержать 9 цифр'
And the user checks the element field 'ogrn' has error message 'ОГРН должно содержать 13 цифр'
And the user checks the element field 'okpo' has error message 'ОКПО должно содержать 8 цифр'
When user fill legal details inputs
 | elementName | value |
 | fullName | valid |
 | legalAddress | valid |
 | inn | 123456789 |
 | kpp | 12345678 |
 | ogrn | 123456789012 |
 | okpo | 1234567 |
And user clicks save legal details button
Then the user checks the element field 'inn' has error message 'ИНН должен быть ровно 10 цифр'
And the user checks the element field 'kpp' has error message 'КПП должен содержать 9 цифр'
And the user checks the element field 'ogrn' has error message 'ОГРН должно содержать 13 цифр'
And the user checks the element field 'okpo' has error message 'ОКПО должно содержать 8 цифр'
When user fill legal details inputs
 | elementName | value |
 | fullName | valid |
 | legalAddress | valid |
 | inn | notNumeric |
 | kpp | notNumeric |
 | ogrn | notNumeric |
 | okpo | notNumeric |
And user clicks save legal details button
Then the user checks the element field 'inn' has error message 'ИНН должен быть ровно 10 цифр'
And the user checks the element field 'kpp' has error message 'КПП должен содержать 9 цифр'
And the user checks the element field 'ogrn' has error message 'ОГРН должно содержать 13 цифр'
And the user checks the element field 'okpo' has error message 'ОКПО должно содержать 8 цифр'
When user fill legal details inputs
 | elementName | value |
 | fullName | ООО "organization-s37us802_4" |
 | legalAddress | СПб, Васи Томчака, д 35, оф. 302 |
 | inn | 7825392986 |
 | kpp | 780201001 |
 | ogrn | 1037843021413 |
 | okpo | 47954132 |
And user clicks save legal details button
Then the user sees no error messages
And the user sees success message 'Данные успешно сохранены'
