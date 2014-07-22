Meta:
@sprint_38
@us_101

Narrative:
Как владелец,
Я хочу создавать, редактировать и удалять товары,
Чтобы отслеживать их движение и продавать, используя возможности LH

Scenario: Product creation confirmation ok

Meta:
@smoke
@id_s38u101s1

GivenStories: precondition/sprint-38/us-101/aPreconditionToUserCreation.story

Given the user with email 's28u101@lighthouse.pro' creates group with name 'groups30u101'
And the user navigates to the group with name 'groups30u101'
And the user logs in using 's28u101@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on create product button on group page
And the user inputs values in create new product modal window
| elementName | value |
| name | Продукт1 |
| unit | шт |
| barcode | 12345678910 |
| vat | 0 |
| purchasePrice | 123,56 |
| retailPrice | 123,56 |

And the user confirms OK in create new product modal window

Then the user waits for modal window closing

Then the user asserts the product list contain products with values
| name | purchasePrice | barcode |
| Продукт1 | 123,56 | 12345678910|

When the user clicks on the product with name 'Продукт1'

Then the user checks stored values in edit product modal window

Scenario: Product creation confirmation cancel

Meta:
@smoke
@id_s38u101s2

GivenStories: precondition/sprint-38/us-101/aPreconditionToUserCreation.story

Given the user with email 's28u101@lighthouse.pro' creates group with name 'groups30u101'
And the user navigates to the group with name 'groups30u101'
And the user logs in using 's28u101@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on create product button on group page
And the user inputs values in create new product modal window
| elementName | value |
| name | Продукт2 |
| unit | шт |
| barcode | 12345678910 |
| vat | 0 |
| purchasePrice | 123,56 |
| retailPrice | 123,56 |

And the user clicks on close icon in create new product modal window

Then the user waits for modal window closing

Then the user asserts the groups list not contain product with name 'Продукт2'

Scenario: Product deletion

Meta:
@smoke
@id_s38u101s3

GivenStories: precondition/sprint-38/us-101/aPreconditionToUserCreation.story

Given the user with email 's28u101@lighthouse.pro' creates group with name 'groups30u101'
And the user with email 's28u101@lighthouse.pro' creates product with name 'Продукт3'
And the user navigates to the group with name 'groups30u101'
And the user logs in using 's28u101@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the product with name 'Продукт3'

And the user clicks on delete product button in edit product modal window
And the user clicks on delete product confirm button in edit product modal window

Then the user waits for modal window closing

Then the user asserts the groups list not contain product with name 'Продукт2'

Scenario: Can't delete group with products

Meta:
@smoke
@id_s38u101s4

GivenStories: precondition/sprint-38/us-101/aPreconditionToUserCreation.story

Given the user with email 's28u101@lighthouse.pro' creates group with name 'groups30u101'
And the user with email 's28u101@lighthouse.pro' creates product with name 'Продукт3'
And the user navigates to the group with name 'groups30u101'
And the user logs in using 's28u101@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the edit group icon
And the user clicks on delete group button in edit group modal window
And the user clicks on delete group confirm button in edit group modal window

Then the user user sees errorMessage Типа нельзя так сделать, брат

Scenario: Create new product modal window title assert

Meta:
@smoke
@id_s38u101s5

GivenStories: precondition/sprint-38/us-101/aPreconditionToUserCreation.story

Given the user with email 's28u101@lighthouse.pro' creates group with name 'groups30u101'
And the user navigates to the group with name 'groups30u101'
And the user logs in using 's28u101@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on create product button on group page

Then the user asserts the create product modal window title is 'Добавление товара'

Scenario: Edit product modal window title assert

Meta:
@smoke
@id_s38u101s6

GivenStories: precondition/sprint-38/us-101/aPreconditionToUserCreation.story

Given the user with email 's28u101@lighthouse.pro' creates group with name 'groups30u101'
And the user with email 's28u101@lighthouse.pro' creates product with name 'Продукт4'
And the user navigates to the group with name 'groups30u101'
And the user logs in using 's28u101@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the product with name 'Продукт4'

Then the user asserts the edit product modal window title is 'Редактирование товара #100349'

Scenario: Group contains no products message

Meta:
@smoke
@id_s38u101s7

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/sprint-38/us-101/aPreconditionToUserCreation.story

Given the user with email 's28u101@lighthouse.pro' creates group with name 'groups30u1011'
And the user navigates to the group with name 'groups30u1011'
And the user logs in using 's28u101@lighthouse.pro' userName and 'lighthouse' password

Then the user checks page contains text 'Нет продуктов типа в группе, брат'

Scenario: Product edition confirmation ok

Meta:
@smoke
@id_s38u101s8

!--not implemented yet


Scenario: Product edition confirmation cancel

Meta:
@smoke
@id_s38u101s9

!--not implemented yet

Scenario: Product markUp assert

Meta:
@smoke
@id_s38u101s10

!--not implemented yet
