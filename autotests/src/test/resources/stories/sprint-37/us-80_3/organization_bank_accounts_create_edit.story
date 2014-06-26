Meta:
@sprint_37
@us_80.3

Narrative:
Как владелец торговой точки,
Я хочу добавить в систему данные нескольких счетов своей организации,
Что бы в дальнейшем использовать эти данные в документах

Scenario: Create bank account

Meta:
@smoke

Given the user opens the authorization page
And the user logs in using 'owner@lighthouse.pro' userName and 'lighthouse' password
And user have organization with name 'organization-s37u803_1'
And user is on company page
When user clicks bank accounts list link
And user clicks create new bank account link
And user fill bank account inputs
 | elementName | value |
 | bic | 123456789 |
 | bankName | ООО "Банк s37u803_1" |
 | bankAddress | Россия, СПб, Корачаево, дом 10 |
 | correspondentAccount | 123456789012345678901234567890 |
 | account | s37u803_1 |
And user clicks create new bank account form button
Then the user sees no error messages
When user clicks to bank accounts in list with bank 'ООО "Банк s37u803_1"' and account 's37u803_1'
Then user checks bank account fields data