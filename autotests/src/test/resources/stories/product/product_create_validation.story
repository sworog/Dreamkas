Meta:
@sprint_38
@us_101

Narrative:
Как владелец,
Я хочу создавать, редактировать и удалять товары,
Чтобы отслеживать их движение и продавать, используя возможности LH

Scenario: Product create name is required

Meta:
@id_s38u101s19

GivenStories: precondition/sprint-38/us-101/aPreconditionToUserCreation.story

Given the user with email 's28u101@lighthouse.pro' creates group with name 'groups30u101'
And the user navigates to the group with name 'groups30u101'
And the user logs in using 's28u101@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on create product button on group page

And the user confirms OK in create new product modal window

Then the user checks the create new product modal window 'name' field has error message with text 'Заполните это поле'

Scenario: Product create name max size validation good (300 symbols)

Meta:
@id_s38u101s20

GivenStories: precondition/sprint-38/us-101/aPreconditionToUserCreation.story

Given the user with email 's28u101@lighthouse.pro' creates group with name 'groups30u101'
And the user navigates to the group with name 'groups30u101'
And the user logs in using 's28u101@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on create product button on group page
And the user generates symbols with count '300' in the create new product modal window 'name' field
And the user confirms OK in create new product modal window

Then the user waits for modal window closing

Then the user asserts the groups list contain product with storedName

Scenario: Product create name max size validation negative (301 symbols)

Meta:
@id_s38u101s21

GivenStories: precondition/sprint-38/us-101/aPreconditionToUserCreation.story

Given the user with email 's28u101@lighthouse.pro' creates group with name 'groups30u101'
And the user navigates to the group with name 'groups30u101'
And the user logs in using 's28u101@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on create product button on group page
And the user generates symbols with count '301' in the create new product modal window 'name' field
And the user confirms OK in create new product modal window

Then the user checks the create new product modal window 'name' field has error message with text 'Не более 300 символов'

Scenario: Product create unit, barcode, vat, purhasePrice, sellingPrice field is not required

Meta:
@id_s38u101s22

GivenStories: precondition/sprint-38/us-101/aPreconditionToUserCreation.story

Given the user with email 's28u101@lighthouse.pro' creates group with name 'groups30u101'
And the user navigates to the group with name 'groups30u101'
And the user logs in using 's28u101@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on create product button on group page
And the user inputs values in create new product modal window
| elementName | value |
| name | s38u101validationName3 |

And the user confirms OK in create new product modal window

Then the user waits for modal window closing

Then the user asserts the groups list contain product with name 's38u101validationName3'

Scenario: Product create barcode max size validation good (200 symbols)

Meta:
@id_s38u101s23

GivenStories: precondition/sprint-38/us-101/aPreconditionToUserCreation.story

Given the user with email 's28u101@lighthouse.pro' creates group with name 'groups30u101'
And the user navigates to the group with name 'groups30u101'
And the user logs in using 's28u101@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on create product button on group page
And the user inputs values in create new product modal window
| elementName | value |
| name | s38u101validationName4 |
And the user generates symbols with count '200' in the create new product modal window 'barcode' field
And the user confirms OK in create new product modal window

Then the user waits for modal window closing

Then the user asserts the groups list contain product with name 's38u101validationName4'


Scenario: Product create barcode max size validation negative (201 symbols)

Meta:
@id_s38u101s24

GivenStories: precondition/sprint-38/us-101/aPreconditionToUserCreation.story

Given the user with email 's28u101@lighthouse.pro' creates group with name 'groups30u101'
And the user navigates to the group with name 'groups30u101'
And the user logs in using 's28u101@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on create product button on group page
And the user inputs values in create new product modal window
| elementName | value |
| name | s38u101validationName5 |
And the user generates symbols with count '201' in the create new product modal window 'barcode' field
And the user confirms OK in create new product modal window

Then the user checks the create new product modal window 'barcode' field has error message with text 'Не более 200 символов'

Scenario: Product create units max size validation good (50 symbols)

Meta:
@id_s38u101s25

GivenStories: precondition/sprint-38/us-101/aPreconditionToUserCreation.story

Given the user with email 's28u101@lighthouse.pro' creates group with name 'groups30u101'
And the user navigates to the group with name 'groups30u101'
And the user logs in using 's28u101@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on create product button on group page
And the user inputs values in create new product modal window
| elementName | value |
| name | s38u101validationName6 |
And the user generates symbols with count '50' in the create new product modal window 'unit' field
And the user confirms OK in create new product modal window

Then the user waits for modal window closing

Then the user asserts the groups list contain product with name 's38u101validationName6'

Scenario: Product create units max size validation negative (51 symbols)

Meta:
@id_s38u101s26

GivenStories: precondition/sprint-38/us-101/aPreconditionToUserCreation.story

Given the user with email 's28u101@lighthouse.pro' creates group with name 'groups30u101'
And the user navigates to the group with name 'groups30u101'
And the user logs in using 's28u101@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on create product button on group page
And the user inputs values in create new product modal window
| elementName | value |
| name | s38u101validationName7 |
And the user generates symbols with count '51' in the create new product modal window 'unit' field
And the user confirms OK in create new product modal window

Then the user checks the create new product modal window 'unit' field has error message with text 'Не более 50 символов'

Scenario: Product create purchasePrice field length validation good

Meta:
@id_s38u101s27

GivenStories: precondition/sprint-38/us-101/aPreconditionToUserCreation.story

Given the user with email 's28u101@lighthouse.pro' creates group with name 'groups30u101'
And the user navigates to the group with name 'groups30u101'
And the user logs in using 's28u101@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on create product button on group page
And the user inputs values in create new product modal window
| elementName | value |
| name | s38u101validationName8 |
| purchasePrice | 10000000 |
And the user confirms OK in create new product modal window

Then the user waits for modal window closing

Then the user asserts the groups list contain product with name 's38u101validationName8'

Scenario: Product create sellingPrice field length validation good

Meta:
@id_s38u101s28

GivenStories: precondition/sprint-38/us-101/aPreconditionToUserCreation.story

Given the user with email 's28u101@lighthouse.pro' creates group with name 'groups30u101'
And the user navigates to the group with name 'groups30u101'
And the user logs in using 's28u101@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on create product button on group page
And the user inputs values in create new product modal window
| elementName | value |
| name | s38u101validationName9 |
| sellingPrice | 10000000 |
And the user confirms OK in create new product modal window

Then the user waits for modal window closing

Then the user asserts the groups list contain product with name 's38u101validationName9'

Scenario: Product create purchasePrice field validation negative

Meta:
@id_s38u101s29

Given the user runs the symfony:user:create command with params: email 's28u101@lighthouse.pro' and password 'lighthouse'

Given the user with email 's28u101@lighthouse.pro' creates group with name 'groups30u101'
And the user navigates to the group with name 'groups30u101'
And the user logs in using 's28u101@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on create product button on group page
And the user inputs value in create new product modal windows 'purchasePrice' field
And the user confirms OK in create new product modal window

Then the user checks the create new product modal window 'purchasePrice' field has errorMessage

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

Scenario: Product create sellingprice field validation negative

Meta:
@id_s38u101s30

Given the user runs the symfony:user:create command with params: email 's28u101@lighthouse.pro' and password 'lighthouse'

Given the user with email 's28u101@lighthouse.pro' creates group with name 'groups30u101'
And the user navigates to the group with name 'groups30u101'
And the user logs in using 's28u101@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on create product button on group page
And the user inputs value in create new product modal windows 'sellingPrice' field
And the user confirms OK in create new product modal window

Then the user checks the create new product modal window 'sellingPrice' field has errorMessage

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