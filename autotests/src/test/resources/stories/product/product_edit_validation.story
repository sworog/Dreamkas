Meta:
@sprint_38
@us_101

Narrative:
Как владелец,
Я хочу создавать, редактировать и удалять товары,
Чтобы отслеживать их движение и продавать, используя возможности LH

Scenario: Product edit name is required

Meta:
@id_s38u101s31

GivenStories: precondition/sprint-38/us-101/aPreconditionToUserCreation.story

Given the user with email 's28u101@lighthouse.pro' creates group with name 'groups30u101val'
And the user navigates to the group with name 'groups30u101val'
And the user with email 's28u101@lighthouse.pro' creates the product with name 's38u101editValidationName1', units 'шт.', barcode 'editvalidation1', vat '0', purchasePrice '100', sellingPrice '110' in the group with name 'groups30u101val'
And пользователь авторизуется в системе используя адрес электронной почты 's28u101@lighthouse.pro' и пароль 'lighthouse'

When the user clicks on the product with name 's38u101editValidationName1'
And the user inputs values in edit product modal window
| elementName | value |
| name | |

And the user confirms OK in edit product modal window

Then the user checks the edit product modal window 'name' field has error message with text 'Заполните это поле'

Scenario: Product edit name max size validation good (300 symbols)

Meta:
@id_s38u101s32

GivenStories: precondition/sprint-38/us-101/aPreconditionToUserCreation.story

Given the user with email 's28u101@lighthouse.pro' creates group with name 'groups30u101val'
And the user navigates to the group with name 'groups30u101val'
And the user with email 's28u101@lighthouse.pro' creates the product with name 's38u101editValidationName2', units 'шт.', barcode 'editvalidation2', vat '0', purchasePrice '100', sellingPrice '110' in the group with name 'groups30u101val'
And пользователь авторизуется в системе используя адрес электронной почты 's28u101@lighthouse.pro' и пароль 'lighthouse'

When the user clicks on the product with name 's38u101editValidationName2'
And the user generates symbols with count '300' in the edit product modal window 'name' field
And the user confirms OK in edit product modal window

Then the user waits for modal window closing

Then the user asserts the groups list contain product with storedName

Scenario: Product edit name max size validation negative (301 symbols)

Meta:
@id_s38u101s33

GivenStories: precondition/sprint-38/us-101/aPreconditionToUserCreation.story

Given the user with email 's28u101@lighthouse.pro' creates group with name 'groups30u101val'
And the user navigates to the group with name 'groups30u101val'
And the user with email 's28u101@lighthouse.pro' creates the product with name 's38u101editValidationName3', units 'шт.', barcode 'editvalidation3', vat '0', purchasePrice '100', sellingPrice '110' in the group with name 'groups30u101val'
And пользователь авторизуется в системе используя адрес электронной почты 's28u101@lighthouse.pro' и пароль 'lighthouse'

When the user clicks on the product with name 's38u101editValidationName3'
And the user generates symbols with count '301' in the edit product modal window 'name' field
And the user confirms OK in edit product modal window

Then the user checks the edit product modal window 'name' field has error message with text 'Не более 300 символов'

Scenario: Product edit barcode max size validation good (200 symbols)

Meta:
@id_s38u101s34

GivenStories: precondition/sprint-38/us-101/aPreconditionToUserCreation.story

Given the user with email 's28u101@lighthouse.pro' creates group with name 'groups30u101val'
And the user navigates to the group with name 'groups30u101val'
And the user with email 's28u101@lighthouse.pro' creates the product with name 's38u101editValidationName4', units 'шт.', barcode 'editvalidation4', vat '0', purchasePrice '100', sellingPrice '110' in the group with name 'groups30u101val'
And пользователь авторизуется в системе используя адрес электронной почты 's28u101@lighthouse.pro' и пароль 'lighthouse'

When the user clicks on the product with name 's38u101editValidationName4'
And the user generates symbols with count '200' in the edit product modal window 'barcode' field
And the user confirms OK in edit product modal window

Then the user waits for modal window closing

Then the user asserts the groups list contain product with name 's38u101editValidationName4'

Scenario: Product edit barcode max size validation negative (201 symbols)

Meta:
@id_s38u101s35

GivenStories: precondition/sprint-38/us-101/aPreconditionToUserCreation.story

Given the user with email 's28u101@lighthouse.pro' creates group with name 'groups30u101val'
And the user navigates to the group with name 'groups30u101val'
And the user with email 's28u101@lighthouse.pro' creates the product with name 's38u101editValidationName5', units 'шт.', barcode 'editvalidation5', vat '0', purchasePrice '100', sellingPrice '110' in the group with name 'groups30u101val'
And пользователь авторизуется в системе используя адрес электронной почты 's28u101@lighthouse.pro' и пароль 'lighthouse'

When the user clicks on the product with name 's38u101editValidationName5'
And the user generates symbols with count '201' in the edit product modal window 'barcode' field
And the user confirms OK in edit product modal window

Then the user checks the edit product modal window 'barcode' field has error message with text 'Не более 200 символов'

Scenario: Product edit units max size validation good (50 symbols)

Meta:
@id_s38u101s36

GivenStories: precondition/sprint-38/us-101/aPreconditionToUserCreation.story

Given the user with email 's28u101@lighthouse.pro' creates group with name 'groups30u101val'
And the user navigates to the group with name 'groups30u101val'
And the user with email 's28u101@lighthouse.pro' creates the product with name 's38u101editValidationName6', units 'шт.', barcode 'editvalidation6', vat '0', purchasePrice '100', sellingPrice '110' in the group with name 'groups30u101val'
And пользователь авторизуется в системе используя адрес электронной почты 's28u101@lighthouse.pro' и пароль 'lighthouse'

When the user clicks on the product with name 's38u101editValidationName6'
And the user generates symbols with count '50' in the edit product modal window 'unit' field
And the user confirms OK in edit product modal window

Then the user waits for modal window closing

Then the user asserts the groups list contain product with name 's38u101editValidationName6'

Scenario: Product edit units max size validation negative (51 symbols)

Meta:
@id_s38u101s37

GivenStories: precondition/sprint-38/us-101/aPreconditionToUserCreation.story

Given the user with email 's28u101@lighthouse.pro' creates group with name 'groups30u101val'
And the user navigates to the group with name 'groups30u101val'
And the user with email 's28u101@lighthouse.pro' creates the product with name 's38u101editValidationName7', units 'шт.', barcode 'editvalidation7', vat '0', purchasePrice '100', sellingPrice '110' in the group with name 'groups30u101val'
And пользователь авторизуется в системе используя адрес электронной почты 's28u101@lighthouse.pro' и пароль 'lighthouse'

When the user clicks on the product with name 's38u101editValidationName7'
And the user generates symbols with count '51' in the edit product modal window 'unit' field
And the user confirms OK in edit product modal window

Then the user checks the edit product modal window 'unit' field has error message with text 'Не более 50 символов'

Scenario: Product edit purchasePrice field length validation good

Meta:
@id_s38u101s38

GivenStories: precondition/sprint-38/us-101/aPreconditionToUserCreation.story

Given the user with email 's28u101@lighthouse.pro' creates group with name 'groups30u101val'
And the user navigates to the group with name 'groups30u101val'
And the user with email 's28u101@lighthouse.pro' creates the product with name 's38u101editValidationName8', units 'шт.', barcode 'editvalidation8', vat '0', purchasePrice '100', sellingPrice '110' in the group with name 'groups30u101val'
And пользователь авторизуется в системе используя адрес электронной почты 's28u101@lighthouse.pro' и пароль 'lighthouse'

When the user clicks on the product with name 's38u101editValidationName8'
And the user inputs values in edit product modal window
| elementName | value |
| purchasePrice | 10000000 |
And the user confirms OK in edit product modal window

Then the user waits for modal window closing

Then the user asserts the groups list contain product with name 's38u101editValidationName8'

Scenario: Product edit sellingPrice field length validation good

Meta:
@id_s38u101s39

GivenStories: precondition/sprint-38/us-101/aPreconditionToUserCreation.story

Given the user with email 's28u101@lighthouse.pro' creates group with name 'groups30u101val'
And the user navigates to the group with name 'groups30u101val'
And the user with email 's28u101@lighthouse.pro' creates the product with name 's38u101editValidationName9', units 'шт.', barcode 'editvalidation9', vat '0', purchasePrice '100', sellingPrice '110' in the group with name 'groups30u101val'
And пользователь авторизуется в системе используя адрес электронной почты 's28u101@lighthouse.pro' и пароль 'lighthouse'

When the user clicks on the product with name 's38u101editValidationName9'
And the user inputs values in edit product modal window
| elementName | value |
| sellingPrice | 10000000 |
And the user confirms OK in edit product modal window

Then the user waits for modal window closing

Then the user asserts the groups list contain product with name 's38u101editValidationName9'

Scenario: Product edit purchasePrice field validation negative

Meta:
@id_s38u101s40

Given the user runs the symfony:user:create command with params: email 's28u101@lighthouse.pro' and password 'lighthouse'

Given the user with email 's28u101@lighthouse.pro' creates group with name 'groups30u101val'
And the user navigates to the group with name 'groups30u101val'
And the user with email 's28u101@lighthouse.pro' creates the product with randomly generated name in the group with name 'groups30u101val'
And пользователь авторизуется в системе используя адрес электронной почты 's28u101@lighthouse.pro' и пароль 'lighthouse'

When the user clicks on the product with stored name
And the user inputs value in edit product modal windows 'purchasePrice' field
And the user confirms OK in edit product modal window

Then the user checks the edit product modal window 'purchasePrice' field has errorMessage

Examples:
| value | errorMessage |
| 1,678 | Цена не должна содержать больше 2 цифр после запятой |
| -1 | Цена не должна быть меньше или равна нулю |
| -100 | Цена не должна быть меньше или равна нулю |
| 0 | Цена не должна быть меньше или равна нулю |
| bigprice | Значение должно быть числом |
| BIGPRICE | Значение должно быть числом |
| большаяцена | Значение должно быть числом |
| БОЛЬШАЯЦЕНА | Значение должно быть числом |
| !@#$%^&*() | Значение должно быть числом |
| 10000001 | Цена не должна быть больше 10000000 |

Scenario: Product edit sellingprice field validation negative

Meta:
@id_s38u101s41

Given the user runs the symfony:user:create command with params: email 's28u101@lighthouse.pro' and password 'lighthouse'

Given the user with email 's28u101@lighthouse.pro' creates group with name 'groups30u101val'
And the user navigates to the group with name 'groups30u101val'
And the user with email 's28u101@lighthouse.pro' creates the product with randomly generated name in the group with name 'groups30u101val'
And пользователь авторизуется в системе используя адрес электронной почты 's28u101@lighthouse.pro' и пароль 'lighthouse'

When the user clicks on the product with stored name
And the user inputs value in edit product modal windows 'sellingPrice' field
And the user confirms OK in edit product modal window

Then the user checks the edit product modal window 'sellingPrice' field has errorMessage

Examples:
| value | errorMessage |
| 1,678 | Цена не должна содержать больше 2 цифр после запятой |
| -1 | Цена не должна быть меньше или равна нулю |
| -100 | Цена не должна быть меньше или равна нулю |
| 0 | Цена не должна быть меньше или равна нулю |
| bigprice | Значение должно быть числом |
| BIGPRICE | Значение должно быть числом |
| большаяцена | Значение должно быть числом |
| БОЛЬШАЯЦЕНА | Значение должно быть числом |
| !@#$%^&*() | Значение должно быть числом |
| 10000001 | Цена не должна быть больше 10000000 |