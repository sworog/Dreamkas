Meta:
@sprint_37
@us_80.2

Narrative:
Как владелец торговой точки,
Я хочу добавить в систему юридические реквизиты поставщика,
Что бы в дальнейшем использовать эти данные в документах

Scenario: Create legal details on supplier IP

Meta:
@smoke

Given the user opens the authorization page
And the user logs in as 'owner'
And there is the supplier with name 'supplier-s37us802_1'
And the user navigates to supplier page with name 'supplier-s37us802_1'
When the user clicks supplier legal details link
And user selects legal details type 'Индивидуальный предприниматель'
And user fill legal details inputs
 | elementName | value |
 | legalDetails.fullName | ООО "supplier-s37us802_1" |
 | legalDetails.legalAddress | СПб, Васи Томчака, д 35, оф. 302 |
 | legalDetails.inn | 631401231404 |
 | legalDetails.okpo | 0161644627 |
 | legalDetails.ogrnip | 308631716200038 |
 | legalDetails.certificateNumber | 2356 254558654846552124 |
 | legalDetails.certificateDate | 17.12.2004 |
And user clicks save legal details button
Then the user sees success message 'Данные успешно сохранены'
And user checks legal details fields data
Given the user navigates to supplier page with name 'supplier-s37us802_1'
When the user clicks supplier legal details link
Then user checks legal details fields data

Scenario: Create legal details on supplier IP validation

Given the user opens the authorization page
And the user logs in as 'owner'
And there is the supplier with name 'supplier-s37us802_2'
And the user navigates to supplier page with name 'supplier-s37us802_2'
When the user clicks supplier legal details link
And user selects legal details type 'Индивидуальный предприниматель'
And user fill legal details inputs
 | elementName | value |
 | legalDetails.fullName | length301char01234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789T |
 | legalDetails.legalAddress | length301char01234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789T |
 | legalDetails.inn | 1234567890123 |
 | legalDetails.okpo | 12345678901 |
 | legalDetails.ogrnip | 1234567890123456 |
 | legalDetails.certificateNumber | 12345678901234567890123456 |
 | legalDetails.certificateDate | notDate |
And user clicks save legal details button
Then user checks legal details form the element field 'legalDetails.fullName' has error message 'Не более 300 символов'
And user checks legal details form the element field 'legalDetails.legalAddress' has error message 'Не более 300 символов'
And user checks legal details form the element field 'legalDetails.inn' has error message 'ИНН индивидуально предпринимателя должен состоять из 12 цифр'
And user checks legal details form the element field 'legalDetails.okpo' has error message 'ОКПО индивидуально предпринимателя должен состоять из 10 цифр'
And user checks legal details form the element field 'legalDetails.ogrnip' has error message 'ОГРНИП должен состоять из 15 цифр'
And user checks legal details form the element field 'legalDetails.certificateNumber' has error message 'Не более 25 символов'
And user checks legal details form the element field 'legalDetails.certificateDate' has error message 'Значение недопустимо.'
When user fill legal details inputs
 | elementName | value |
 | legalDetails.fullName | valid |
 | legalDetails.legalAddress | valid |
 | legalDetails.inn | 12345678901 |
 | legalDetails.okpo | 123456789 |
 | legalDetails.ogrnip | 12345678901234 |
 | legalDetails.certificateNumber | valid |
 | legalDetails.certificateDate | 1.12.2001 |
And user clicks save legal details button
Then user checks legal details form the element field 'legalDetails.inn' has error message 'ИНН индивидуально предпринимателя должен состоять из 12 цифр'
And user checks legal details form the element field 'legalDetails.okpo' has error message 'ОКПО индивидуально предпринимателя должен состоять из 10 цифр'
And user checks legal details form the element field 'legalDetails.ogrnip' has error message 'ОГРНИП должен состоять из 15 цифр'
When user fill legal details inputs
 | elementName | value |
 | legalDetails.fullName | valid |
 | legalDetails.legalAddress | valid |
 | legalDetails.inn | notNumeric12 |
 | legalDetails.okpo | notNumeric1 |
 | legalDetails.ogrnip | notNumeric123456 |
 | legalDetails.certificateNumber | valid |
 | legalDetails.certificateDate | 1.12.2001 |
And user clicks save legal details button
Then user checks legal details form the element field 'legalDetails.inn' has error message 'ИНН индивидуально предпринимателя должен состоять из 12 цифр'
And user checks legal details form the element field 'legalDetails.okpo' has error message 'ОКПО индивидуально предпринимателя должен состоять из 10 цифр'
And user checks legal details form the element field 'legalDetails.ogrnip' has error message 'ОГРНИП должен состоять из 15 цифр'
When user fill legal details inputs
 | elementName | value |
 | legalDetails.fullName | valid |
 | legalDetails.legalAddress | valid |
 | legalDetails.inn | 123456789012 |
 | legalDetails.okpo | 1234567890 |
 | legalDetails.ogrnip | 123456789012345 |
 | legalDetails.certificateNumber | valid |
 | legalDetails.certificateDate | 1.12.2001 |
And user clicks save legal details button
Then the user sees no error messages
And the user sees success message 'Данные успешно сохранены'

Scenario: Create legal details on supplier legalEntity

Meta:
@smoke

Given the user opens the authorization page
And the user logs in as 'owner'
And there is the supplier with name 'supplier-s37us802_3'
And the user navigates to supplier page with name 'supplier-s37us802_3'
When the user clicks supplier legal details link
And user selects legal details type 'Юридическое лицо'
And user fill legal details inputs
 | elementName | value |
 | legalDetails.fullName | ООО "supplier-s37us802_3" |
 | legalDetails.legalAddress.yur | СПб, Васи Томчака, д 35, оф. 302 |
 | legalDetails.inn.yur | 7825392986 |
 | legalDetails.kpp | 780201001 |
 | legalDetails.ogrn | 1037843021413 |
 | legalDetails.okpo.yur | 47954132 |
And user clicks save legal details button
Then the user sees success message 'Данные успешно сохранены'
And user checks legal details fields data
Given the user navigates to supplier page with name 'supplier-s37us802_3'
When the user clicks supplier legal details link
Then user checks legal details fields data

Scenario: Create legal details on supplier legalEntity validation

Given the user opens the authorization page
And the user logs in as 'owner'
And there is the supplier with name 'supplier-s37us802_4'
And the user navigates to supplier page with name 'supplier-s37us802_4'
When the user clicks supplier legal details link
And user selects legal details type 'Юридическое лицо'
And user fill legal details inputs
 | elementName | value |
 | legalDetails.fullName | length301char01234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789T |
 | legalDetails.legalAddress.yur | length301char01234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789T |
 | legalDetails.inn.yur | 12345678901 |
 | legalDetails.kpp | 1234567890 |
 | legalDetails.ogrn | 12345678901234 |
 | legalDetails.okpo.yur | 123456789 |
And user clicks save legal details button
Then user checks legal details form the element field 'legalDetails.fullName' has error message 'Не более 300 символов'
And user checks legal details form the element field 'legalDetails.legalAddress.yur' has error message 'Не более 300 символов'
And user checks legal details form the element field 'legalDetails.inn.yur' has error message 'ИНН юридического лица должен состоять из 10 цифр'
And user checks legal details form the element field 'legalDetails.kpp' has error message 'КПП должен состоять из 9 цифр'
And user checks legal details form the element field 'legalDetails.ogrn' has error message 'ОГРН должен состоять из 13 цифр'
And user checks legal details form the element field 'legalDetails.okpo.yur' has error message 'ОКПО юридического лица должен состоять из 8 цифр'
When user fill legal details inputs
 | elementName | value |
 | legalDetails.fullName | valid |
 | legalDetails.legalAddress.yur | valid |
 | legalDetails.inn.yur | 123456789 |
 | legalDetails.kpp | 12345678 |
 | legalDetails.ogrn | 123456789012 |
 | legalDetails.okpo.yur | 1234567 |
And user clicks save legal details button
Then user checks legal details form the element field 'legalDetails.inn.yur' has error message 'ИНН юридического лица должен состоять из 10 цифр'
And user checks legal details form the element field 'legalDetails.kpp' has error message 'КПП должен состоять из 9 цифр'
And user checks legal details form the element field 'legalDetails.ogrn' has error message 'ОГРН должен состоять из 13 цифр'
And user checks legal details form the element field 'legalDetails.okpo.yur' has error message 'ОКПО юридического лица должен состоять из 8 цифр'
When user fill legal details inputs
 | elementName | value |
 | legalDetails.fullName | valid |
 | legalDetails.legalAddress.yur | valid |
 | legalDetails.inn.yur | notNumeric |
 | legalDetails.kpp | notNumeric |
 | legalDetails.ogrn | notNumeric |
 | legalDetails.okpo.yur | notNumeric |
And user clicks save legal details button
Then user checks legal details form the element field 'legalDetails.inn.yur' has error message 'ИНН юридического лица должен состоять из 10 цифр'
And user checks legal details form the element field 'legalDetails.kpp' has error message 'КПП должен состоять из 9 цифр'
And user checks legal details form the element field 'legalDetails.ogrn' has error message 'ОГРН должен состоять из 13 цифр'
And user checks legal details form the element field 'legalDetails.okpo.yur' has error message 'ОКПО юридического лица должен состоять из 8 цифр'
When user fill legal details inputs
 | elementName | value |
 | legalDetails.fullName | ООО "supplier-s37us802_4" |
 | legalDetails.legalAddress.yur | СПб, Васи Томчака, д 35, оф. 302 |
 | legalDetails.inn.yur | 7825392986 |
 | legalDetails.kpp | 780201001 |
 | legalDetails.ogrn | 1037843021413 |
 | legalDetails.okpo.yur | 47954132 |
And user clicks save legal details button
Then the user sees no error messages
And the user sees success message 'Данные успешно сохранены'


Scenario: Edit legal details on supplier IP

Meta:
@smoke

Given the user opens the authorization page
And the user logs in as 'owner'
And there is the supplier with name 'supplier-s37us802_5'
And the user navigates to supplier page with name 'supplier-s37us802_5'
When the user clicks supplier legal details link
And user selects legal details type 'Индивидуальный предприниматель'
And user fill legal details inputs
 | elementName | value |
 | legalDetails.fullName | ООО "supplier-s37us802_5" |
 | legalDetails.legalAddress | СПб, Васи Томчака, д 35, оф. 302 |
 | legalDetails.inn | 631401231404 |
 | legalDetails.okpo | 0161644627 |
 | legalDetails.ogrnip | 308631716200038 |
 | legalDetails.certificateNumber | 2356 254558654846552124 |
 | legalDetails.certificateDate | 17.12.2004 |
And user clicks save legal details button
Then the user sees success message 'Данные успешно сохранены'
And user checks legal details fields data
Given the user navigates to supplier page with name 'supplier-s37us802_5'
When the user clicks supplier legal details link
And user selects legal details type 'Индивидуальный предприниматель'
And user fill legal details inputs
 | elementName | value |
 | legalDetails.fullName | ООО "supplier-s37us802_5Edited" |
 | legalDetails.legalAddress | Edited |
 | legalDetails.inn | 123456789012 |
 | legalDetails.okpo | 1234567890 |
 | legalDetails.ogrnip | 123456789012345 |
 | legalDetails.certificateNumber | Edited |
 | legalDetails.certificateDate | 11.11.2003 |
And user clicks save legal details button
Then the user sees success message 'Данные успешно сохранены'
And user checks legal details fields data
Given the user navigates to supplier page with name 'supplier-s37us802_5'
When the user clicks supplier legal details link
Then user checks legal details fields data

Scenario: Edit legal details on supplier IP validation

Given the user opens the authorization page
And the user logs in as 'owner'
And there is the supplier with name 'supplier-s37us802_6'
And the user navigates to supplier page with name 'supplier-s37us802_6'
When the user clicks supplier legal details link
And user selects legal details type 'Индивидуальный предприниматель'
And user fill legal details inputs
 | elementName | value |
 | legalDetails.fullName | ООО "supplier-s37us802_6" |
 | legalDetails.legalAddress | СПб, Васи Томчака, д 35, оф. 302 |
 | legalDetails.inn | 631401231404 |
 | legalDetails.okpo | 0161644627 |
 | legalDetails.ogrnip | 308631716200038 |
 | legalDetails.certificateNumber | 2356 254558654846552124 |
 | legalDetails.certificateDate | 17.12.2004 |
And user clicks save legal details button
Then the user sees success message 'Данные успешно сохранены'
And user checks legal details fields data
Given the user navigates to supplier page with name 'supplier-s37us802_6'
When the user clicks supplier legal details link
And user selects legal details type 'Индивидуальный предприниматель'
And user fill legal details inputs
 | elementName | value |
 | legalDetails.fullName | length301char01234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789T |
 | legalDetails.legalAddress | length301char01234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789T |
 | legalDetails.inn | 1234567890123 |
 | legalDetails.okpo | 12345678901 |
 | legalDetails.ogrnip | 1234567890123456 |
 | legalDetails.certificateNumber | 12345678901234567890123456 |
 | legalDetails.certificateDate | notDate |
And user clicks save legal details button
Then user checks legal details form the element field 'legalDetails.fullName' has error message 'Не более 300 символов'
And user checks legal details form the element field 'legalDetails.legalAddress' has error message 'Не более 300 символов'
And user checks legal details form the element field 'legalDetails.inn' has error message 'ИНН индивидуально предпринимателя должен состоять из 12 цифр'
And user checks legal details form the element field 'legalDetails.okpo' has error message 'ОКПО индивидуально предпринимателя должен состоять из 10 цифр'
And user checks legal details form the element field 'legalDetails.ogrnip' has error message 'ОГРНИП должен состоять из 15 цифр'
And user checks legal details form the element field 'legalDetails.certificateNumber' has error message 'Не более 25 символов'
And user checks legal details form the element field 'legalDetails.certificateDate' has error message 'Значение недопустимо.'
When user fill legal details inputs
 | elementName | value |
 | legalDetails.fullName | valid |
 | legalDetails.legalAddress | valid |
 | legalDetails.inn | 12345678901 |
 | legalDetails.okpo | 123456789 |
 | legalDetails.ogrnip | 12345678901234 |
 | legalDetails.certificateNumber | valid |
 | legalDetails.certificateDate | 1.12.2001 |
And user clicks save legal details button
Then user checks legal details form the element field 'legalDetails.inn' has error message 'ИНН индивидуально предпринимателя должен состоять из 12 цифр'
And user checks legal details form the element field 'legalDetails.okpo' has error message 'ОКПО индивидуально предпринимателя должен состоять из 10 цифр'
And user checks legal details form the element field 'legalDetails.ogrnip' has error message 'ОГРНИП должен состоять из 15 цифр'
When user fill legal details inputs
 | elementName | value |
 | legalDetails.fullName | valid |
 | legalDetails.legalAddress | valid |
 | legalDetails.inn | notNumeric12 |
 | legalDetails.okpo | notNumeric1 |
 | legalDetails.ogrnip | notNumeric123456 |
 | legalDetails.certificateNumber | valid |
 | legalDetails.certificateDate | 1.12.2001 |
And user clicks save legal details button
Then user checks legal details form the element field 'legalDetails.inn' has error message 'ИНН индивидуально предпринимателя должен состоять из 12 цифр'
And user checks legal details form the element field 'legalDetails.okpo' has error message 'ОКПО индивидуально предпринимателя должен состоять из 10 цифр'
And user checks legal details form the element field 'legalDetails.ogrnip' has error message 'ОГРНИП должен состоять из 15 цифр'
When user fill legal details inputs
 | elementName | value |
 | legalDetails.fullName | valid |
 | legalDetails.legalAddress | valid |
 | legalDetails.inn | 123456789012 |
 | legalDetails.okpo | 1234567890 |
 | legalDetails.ogrnip | 123456789012345 |
 | legalDetails.certificateNumber | valid |
 | legalDetails.certificateDate | 1.12.2001 |
And user clicks save legal details button
Then the user sees no error messages
And the user sees success message 'Данные успешно сохранены'

Scenario: Edit legal details on supplier legalEntity

Meta:
@smoke

Given the user opens the authorization page
And the user logs in as 'owner'
And there is the supplier with name 'supplier-s37us802_7'
And the user navigates to supplier page with name 'supplier-s37us802_7'
When the user clicks supplier legal details link
And user selects legal details type 'Юридическое лицо'
And user fill legal details inputs
 | elementName | value |
 | legalDetails.fullName | ООО "supplier-s37us802_7" |
 | legalDetails.legalAddress.yur | СПб, Васи Томчака, д 35, оф. 302 |
 | legalDetails.inn.yur | 7825392986 |
 | legalDetails.kpp | 780201001 |
 | legalDetails.ogrn | 1037843021413 |
 | legalDetails.okpo.yur | 47954132 |
And user clicks save legal details button
Then the user sees success message 'Данные успешно сохранены'
And user checks legal details fields data
Given the user navigates to supplier page with name 'supplier-s37us802_7'
When the user clicks supplier legal details link
And user selects legal details type 'Юридическое лицо'
And user fill legal details inputs
 | elementName | value |
 | legalDetails.fullName | ООО "supplier-s37us802_7Editet" |
 | legalDetails.legalAddress.yur | Edited |
 | legalDetails.inn.yur | 1234567890 |
 | legalDetails.kpp | 123456789 |
 | legalDetails.ogrn | 1234567890123 |
 | legalDetails.okpo.yur | 12345678 |
And user clicks save legal details button
Then the user sees success message 'Данные успешно сохранены'
And user checks legal details fields data
Given the user navigates to supplier page with name 'supplier-s37us802_7'
When the user clicks supplier legal details link
Then user checks legal details fields data

Scenario: Edit legal details on supplier legalEntity validation

Given the user opens the authorization page
And the user logs in as 'owner'
And there is the supplier with name 'supplier-s37us802_8'
And the user navigates to supplier page with name 'supplier-s37us802_8'
When the user clicks supplier legal details link
And user selects legal details type 'Юридическое лицо'
And user fill legal details inputs
 | elementName | value |
 | legalDetails.fullName | ООО "supplier-s37us802_7" |
 | legalDetails.legalAddress.yur | СПб, Васи Томчака, д 35, оф. 302 |
 | legalDetails.inn.yur | 7825392986 |
 | legalDetails.kpp | 780201001 |
 | legalDetails.ogrn | 1037843021413 |
 | legalDetails.okpo.yur | 47954132 |
And user clicks save legal details button
Then the user sees success message 'Данные успешно сохранены'
And user checks legal details fields data
Given the user navigates to supplier page with name 'supplier-s37us802_8'
When the user clicks supplier legal details link
And user selects legal details type 'Юридическое лицо'
And user fill legal details inputs
 | elementName | value |
 | legalDetails.fullName | length301char01234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789T |
 | legalDetails.legalAddress.yur | length301char01234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789012345678901234567890123456789T |
 | legalDetails.inn.yur | 12345678901 |
 | legalDetails.kpp | 1234567890 |
 | legalDetails.ogrn | 12345678901234 |
 | legalDetails.okpo.yur | 123456789 |
And user clicks save legal details button
Then user checks legal details form the element field 'legalDetails.fullName' has error message 'Не более 300 символов'
And user checks legal details form the element field 'legalDetails.legalAddress.yur' has error message 'Не более 300 символов'
And user checks legal details form the element field 'legalDetails.inn.yur' has error message 'ИНН юридического лица должен состоять из 10 цифр'
And user checks legal details form the element field 'legalDetails.kpp' has error message 'КПП должен состоять из 9 цифр'
And user checks legal details form the element field 'legalDetails.ogrn' has error message 'ОГРН должен состоять из 13 цифр'
And user checks legal details form the element field 'legalDetails.okpo.yur' has error message 'ОКПО юридического лица должен состоять из 8 цифр'
When user fill legal details inputs
 | elementName | value |
 | legalDetails.fullName | valid |
 | legalDetails.legalAddress.yur | valid |
 | legalDetails.inn.yur | 123456789 |
 | legalDetails.kpp | 12345678 |
 | legalDetails.ogrn | 123456789012 |
 | legalDetails.okpo.yur | 1234567 |
And user clicks save legal details button
Then user checks legal details form the element field 'legalDetails.inn.yur' has error message 'ИНН юридического лица должен состоять из 10 цифр'
And user checks legal details form the element field 'legalDetails.kpp' has error message 'КПП должен состоять из 9 цифр'
And user checks legal details form the element field 'legalDetails.ogrn' has error message 'ОГРН должен состоять из 13 цифр'
And user checks legal details form the element field 'legalDetails.okpo.yur' has error message 'ОКПО юридического лица должен состоять из 8 цифр'
When user fill legal details inputs
 | elementName | value |
 | legalDetails.fullName | valid |
 | legalDetails.legalAddress.yur | valid |
 | legalDetails.inn.yur | notNumeric |
 | legalDetails.kpp | notNumeric |
 | legalDetails.ogrn | notNumeric |
 | legalDetails.okpo.yur | notNumeric |
And user clicks save legal details button
Then user checks legal details form the element field 'legalDetails.inn.yur' has error message 'ИНН юридического лица должен состоять из 10 цифр'
And user checks legal details form the element field 'legalDetails.kpp' has error message 'КПП должен состоять из 9 цифр'
And user checks legal details form the element field 'legalDetails.ogrn' has error message 'ОГРН должен состоять из 13 цифр'
And user checks legal details form the element field 'legalDetails.okpo.yur' has error message 'ОКПО юридического лица должен состоять из 8 цифр'
When user fill legal details inputs
 | elementName | value |
 | legalDetails.fullName | ООО "supplier-s37us802_8" |
 | legalDetails.legalAddress.yur | СПб, Васи Томчака, д 35, оф. 302 |
 | legalDetails.inn.yur | 7825392986 |
 | legalDetails.kpp | 780201001 |
 | legalDetails.ogrn | 1037843021413 |
 | legalDetails.okpo.yur | 47954132 |
And user clicks save legal details button
Then the user sees no error messages
And the user sees success message 'Данные успешно сохранены'
