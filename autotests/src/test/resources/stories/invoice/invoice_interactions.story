Meta:
@sprint_39
@us_102

Narrative:
Как владелец,
Я хочу
Чтобы

Scenario: Invoice creation

Meta:
@smoke
@id_s39u102s1

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/sprint-39/us-102/aPreconditionToUserCreation.story,
              precondition/sprint-39/us-102/aPreconditionToTestDataCreation.story

Given the user opens the stock movement page
And the user logs in using 's39u102@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the accept products button
And the user inputs values on the create new invoice modal window
| elementName | value |
| date | 08.11.2014 |
| store | s39u102-store |
| supplier | s39u102-supplier |
| product.name | s39u102-product1 |
| priceEntered | 150 |
| quantity | 5 |
And the user clicks on the add new invoice product button
And the user clicks on the paid check box

Then the user asserts the invoice product list contain product with values
| name | priceEntered | quantity | totalPrice |
| s39u102-product1 | 150,00  | 5,0 шт. | 750,00 |
And the user asserts invoice total sum is '750,00' in create new invoice modal window

When the user clicks on the invoice accept button

Then the user waits for modal window closing

Then the user asserts stock movement operations on the stock movement page
| date | type | status | store | sumTotal |
| 08.11.2014 | Приёмка | / оплачена | В s39u102-store | 750,00 |

When the user clicks on the invoice with number '10001' on the stock movement page

Then the user checks values on the edit invoice modal window
| elementName | value |
| date | 08.11.2014 |
| store | s39u102-store |
| supplier | s39u102-supplier |
And the user asserts the invoice product list contain product with values
| name | priceEntered | quantity | totalPrice |
| s39u102-product1 | 150,00  | 5,0 шт. | 750,00 |
And the user asserts invoice total sum is '750,00' in edit invoice modal window

Scenario: Invoice edition

Meta:
@smoke
@id_s39u102s2

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/sprint-39/us-102/aPreconditionToUserCreation.story,
              precondition/sprint-39/us-102/aPreconditionToTestDataCreation.story,
              precondition/sprint-39/us-102/aPreconditionToTestInvoiceCreation.story,
              precondition/sprint-39/us-102/aPreconditionForInvoiceEditionScenario.story

Given the user opens the stock movement page
And the user logs in using 's39u102@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the last created invoice from builder steps on the stock movement page
And the user inputs values on the edit invoice modal window
| elementName | value |
| date | 08.11.2014 |
| store | s39u102-store1 |
| supplier | s39u102-supplier1 |
| product.name | s39u102-product2 |
And the user clicks on the add new invoice product button in the edit invoice modal window

Then the user asserts the invoice product list contain product with values
| name | priceEntered | quantity | totalPrice |
| s39u102-product1 | 150,00  | 5,0 шт. | 750,00 |
| s39u102-product2 | 125,50  | 1,0 Пятюня | 125,50 |
And the user asserts invoice total sum is '875,50' in edit invoice modal window

When the user clicks on the invoice save button in the edit invoice modal window

Then the user waits for modal window closing

Then the user asserts stock movement operations on the stock movement page
| date | type | status | store | sumTotal |
| 08.11.2014 | Приёмка | / не оплачена | В s39u102-store1 | 875,50 |

When the user clicks on the invoice with number '10001' on the stock movement page

Then the user checks values on the edit invoice modal window
| elementName | value |
| date | 08.11.2014 |
| store | s39u102-store1 |
| supplier | s39u102-supplier1 |
Then the user asserts the invoice product list contain product with values
| name | priceEntered | quantity | totalPrice |
| s39u102-product1 | 150,00  | 5,0 шт. | 750,00 |
| s39u102-product2 | 125,50  | 1,0 Пятюня | 125,50 |
And the user asserts invoice total sum is '875,50' in edit invoice modal window

Scenario: Invoice deletion

Meta:
@smoke
@id_s39u102s3

GivenStories: precondition/sprint-39/us-102/aPreconditionToUserCreation.story,
              precondition/sprint-39/us-102/aPreconditionToTestDataCreation.story,
              precondition/sprint-39/us-102/aPreconditionToTestInvoiceCreation.story

Given the user opens the stock movement page
And the user logs in using 's39u102@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the last created invoice from builder steps on the stock movement page
And the user clicks on delete invoice button in edit invoice modal window
And the user clicks on delete invoice confirm button in edit invoice modal window

Then the user waits for modal window closing

Then the user asserts stock movement operations dont contain last created invoice

Scenario: Stock movement menu navigation link click

Meta:
@smoke
@id_s39u102s4

GivenStories: precondition/sprint-39/us-102/aPreconditionToUserCreation.story

Given the user opens the authorization page
And the user logs in using 's39u102@lighthouse.pro' userName and 'lighthouse' password

When the user clicks the menu stock movement item

Then the user asserts stock movement page title is 'Товародвижение'

Scenario: Invoice create modal window assert

Meta:
@id_s39u102s5

GivenStories: precondition/sprint-39/us-102/aPreconditionToUserCreation.story

Given the user opens the stock movement page
And the user logs in using 's39u102@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the accept products button

Then the user asserts create new invoice modal window title is 'Приёмка товаров от поставщика'


Scenario: Invoice edit modal window assert

Meta:
@id_s39u102s6


GivenStories: precondition/sprint-39/us-102/aPreconditionToUserCreation.story,
              precondition/sprint-39/us-102/aPreconditionToTestDataCreation.story,
              precondition/sprint-39/us-102/aPreconditionToTestInvoiceCreation.story

Given the user opens the stock movement page
And the user logs in using 's39u102@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the last created invoice from builder steps on the stock movement page

Then the user asserts edit invoice modal window title is 'Редактирование приёмки товаров от поставщика'

Scenario: No operations message assert in stock movement page

Meta:
@id_s39u102s7

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/sprint-39/us-102/aPreconditionToUserCreation.story

Given the user opens the stock movement page
And the user logs in using 's39u102@lighthouse.pro' userName and 'lighthouse' password

Then the user checks page contains text 'Не найдено ни одной операции с товарами.'

Scenario: Invoice date set automatically in invoice creation

Meta:
@id_s39u102s8
@smoke

GivenStories: precondition/sprint-39/us-102/aPreconditionToUserCreation.story

Given the user opens the stock movement page
And the user logs in using 's39u102@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the accept products button

Then the user asserts the invoice date is set automatically to now date

Scenario: Invoice product price is set automatically if the product has selling price

Meta:
@smoke
@id_s39u102s9

GivenStories: precondition/sprint-39/us-102/aPreconditionToUserCreation.story,
              precondition/sprint-39/us-102/aPreconditionToTestDataCreation.story

Given the user opens the stock movement page
And the user logs in using 's39u102@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the accept products button
And the user inputs values on the create new invoice modal window
| elementName | value |
| product.name | s39u102-product1 |

Then the user checks the element with name 'priceEntered' has value equals to '100,00' in the create new invoice modal window

Scenario: Invoice paid status changes

Meta:
@id_s39u102s10
@smoke

GivenStories: precondition/sprint-39/us-102/aPreconditionToUserCreation.story,
              precondition/sprint-39/us-102/aPreconditionToTestDataCreation.story,
              precondition/sprint-39/us-102/aPreconditionToTestInvoiceCreation.story

Given the user opens the stock movement page
And the user logs in using 's39u102@lighthouse.pro' userName and 'lighthouse' password

Then the user asserts stock movement operations on the stock movement page
| date | type | status | store | sumTotal |
| 28.07.2014 | Приёмка | / не оплачена | В s39u102-store | 750,00 |

When the user clicks on the last created invoice from builder steps on the stock movement page
And the user clicks on the paid check box in the edit invoice modal window
And the user clicks on the invoice save button in the edit invoice modal window

Then the user waits for modal window closing

Then the user asserts stock movement operations on the stock movement page
| date | type | status | store | sumTotal |
| 28.07.2014 | Приёмка | / оплачена | В s39u102-store | 750,00 |

Scenario: Invoice product deletion

Meta:
@smoke
@id_s39u102s12

GivenStories: precondition/sprint-39/us-102/aPreconditionToUserCreation.story,
              precondition/sprint-39/us-102/aPreconditionToTestDataCreation.story,
              precondition/sprint-39/us-102/aPreconditionToTestInvoiceCreation.story,
              precondition/sprint-39/us-102/aPreconditionForInvoiceEditionScenario.story

Given the user opens the stock movement page
And the user logs in using 's39u102@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the last created invoice from builder steps on the stock movement page
And the user inputs values on the edit invoice modal window
| elementName | value |
| date | 08.11.2014 |
| store | s39u102-store1 |
| supplier | s39u102-supplier1 |
| product.name | s39u102-product2 |
And the user clicks on the add new invoice product button in the edit invoice modal window

Then the user asserts the invoice product list contain product with values
| name | priceEntered | quantity | totalPrice |
| s39u102-product1 | 150,00  | 5,0 шт. | 750,00 |
| s39u102-product2 | 125,50  | 1,0 Пятюня | 125,50 |
And the user asserts invoice total sum is '875,50' in edit invoice modal window

When the user deletes the product with name 's39u102-product1' in the edit invoice modal window

Then the user asserts the invoice product list contain product with values
| name | priceEntered | quantity | totalPrice |
| s39u102-product2 | 125,50  | 1,0 Пятюня | 125,50 |

When the user clicks on the invoice save button in the edit invoice modal window

Then the user waits for modal window closing

Then the user asserts stock movement operations on the stock movement page
| date | type | status | store | sumTotal |
| 08.11.2014 | Приёмка | / не оплачена | В s39u102-store1 | 125,50 |

When the user clicks on the last created invoice from builder steps on the stock movement page

Then the user asserts the invoice product list contain product with values
| name | priceEntered | quantity | totalPrice |
| s39u102-product2 | 125,50  | 1,0 Пятюня | 125,50 |

Scenario: Invoice creation with supplier create

Meta:
@id
@smoke
@test

Scenario: Invoice creation with product create

Meta:
@id
@smoke
@test

Scenario: Invoice creation with supplier and product create

Meta:
@id
@smoke
@test

Scenario: Invoice edition with supplier create

Meta:
@id
@smoke
@test

Scenario: Invoice edition with product create

Meta:
@id
@smoke
@test

Scenario: Invoice edition with supplier and product create

Meta:
@id
@smoke
@test
