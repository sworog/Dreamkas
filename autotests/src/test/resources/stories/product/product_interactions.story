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
| vat | Не облагается |
| purchasePrice | 123,56 |
| sellingPrice | 123,56 |

And the user confirms OK in create new product modal window

Then the user waits for modal window closing

Then the user asserts the product list contain products with values
| name | sellingPrice | barcode |
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
| vat | Не облагается |
| purchasePrice | 123,56 |
| sellingPrice | 123,56 |

And the user clicks on close icon in create new product modal window

Then the user waits for modal window closing

Then the user asserts the groups list not contain product with name 'Продукт2'

Scenario: Product deletion

Meta:
@smoke
@id_s38u101s3

GivenStories: precondition/sprint-38/us-101/aPreconditionToUserCreation.story

Given the user with email 's28u101@lighthouse.pro' creates group with name 'groups30u101'
And the user with email 's28u101@lighthouse.pro' creates the product with name 'Продукт3', units 'шт.', barcode '12345', vat '0', purchasePrice '100', sellingPrice '110' in the group with name 'groups30u101'
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
And the user with email 's28u101@lighthouse.pro' creates the product with name 'Продукт4', units 'шт.', barcode '12345', vat '0', purchasePrice '100', sellingPrice '110' in the group with name 'groups30u101'

And the user navigates to the group with name 'groups30u101'
And the user logs in using 's28u101@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the edit group icon
And the user clicks on delete group button in edit group modal window

Then the user asserts pop over content is 'Чтобы удалить группу, нужно сначала удалить все товары в ней.'

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

Scenario: Product edit modal title contains sku

Meta:
@smoke
@regression
@sprint_41
@tech_41

GivenStories:
        precondition/customPrecondition/symfonyEnvInitPrecondition.story,
        precondition/sprint-38/us-101/aPreconditionToUserCreation.story

Given the user with email 's28u101@lighthouse.pro' creates group with name 'groups30u101'
And the user with email 's28u101@lighthouse.pro' creates the product with name 'Продукт3', units 'шт.', barcode '12345', vat '0', purchasePrice '100', sellingPrice '110' in the group with name 'groups30u101'
And the user navigates to the group with name 'groups30u101'
And the user logs in using 's28u101@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the product with name 'Продукт3'
When пользователь* находится в модальном окне 'редактирования товара'
Then пользователь* в модальном окне проверяет, что заголовок равен 'Редактирование товара #10001'

Scenario: Product delete button label

Meta:
@regression
@sprint_41
@tech_41

GivenStories: precondition/sprint-38/us-101/aPreconditionToUserCreation.story

Given the user with email 's28u101@lighthouse.pro' creates group with name 'groups30u101'
And the user with email 's28u101@lighthouse.pro' creates the product with name 'Продукт3', units 'шт.', barcode '12345', vat '0', purchasePrice '100', sellingPrice '110' in the group with name 'groups30u101'
And the user navigates to the group with name 'groups30u101'
And the user logs in using 's28u101@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the product with name 'Продукт3'
Then user checks delete button label 'Удалить товар'

Scenario: Group contains no products message

Meta:
@smoke
@id_s38u101s7

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/sprint-38/us-101/aPreconditionToUserCreation.story

Given the user with email 's28u101@lighthouse.pro' creates group with name 'groups30u1011'
And the user navigates to the group with name 'groups30u1011'
And the user logs in using 's28u101@lighthouse.pro' userName and 'lighthouse' password

Then the user checks page contains text 'В этой группе пока нет ни одного товара.'

Scenario: Product edition confirmation ok

Meta:
@smoke
@id_s38u101s8

GivenStories: precondition/sprint-38/us-101/aPreconditionToUserCreation.story

Given the user with email 's28u101@lighthouse.pro' creates group with name 'groups30u101'
And the user with email 's28u101@lighthouse.pro' creates the product with name 'Продукт41', units 'шт.', barcode '123451', vat '0', purchasePrice '100', sellingPrice '110' in the group with name 'groups30u101'

And the user navigates to the group with name 'groups30u101'
And the user logs in using 's28u101@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the product with name 'Продукт41'

And the user inputs values in edit product modal window
| elementName | value |
| name | Продукт101 |
| unit | шт |
| barcode | 123456789101 |
| vat | Не облагается |
| purchasePrice | 123,56 |
| sellingPrice | 123,56 |

And the user confirms OK in edit product modal window

Then the user waits for modal window closing

Then the user asserts the product list contain products with values
| name | sellingPrice | barcode |
| Продукт101 | 123,56 | 123456789101 |

When the user clicks on the product with name 'Продукт101'

Then the user checks stored values in edit product modal window

Scenario: Product edition confirmation cancel

Meta:
@smoke
@id_s38u101s9

GivenStories: precondition/sprint-38/us-101/aPreconditionToUserCreation.story

Given the user with email 's28u101@lighthouse.pro' creates group with name 'groups30u101'
And the user with email 's28u101@lighthouse.pro' creates the product with name 'Продукт411', units 'шт.', barcode '123452', vat '0', purchasePrice '100', sellingPrice '110' in the group with name 'groups30u101'

And the user navigates to the group with name 'groups30u101'
And the user logs in using 's28u101@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the product with name 'Продукт411'

And the user inputs values in edit product modal window
| elementName | value |
| name | Продукт101 |
| unit | шт |
| barcode | 123456789101 |
| vat | Не облагается |
| purchasePrice | 123,56 |
| sellingPrice | 123,56 |

And the user clicks on close icon in edit product modal window

Then the user waits for modal window closing

Then the user asserts the product list contain products with values
| name | sellingPrice | barcode |
| Продукт411 | 110,00 | 123452 |

Scenario: Product markUp assert

Meta:
@smoke
@id_s38u101s10

GivenStories: precondition/sprint-38/us-101/aPreconditionToUserCreation.story

Given the user with email 's28u101@lighthouse.pro' creates group with name 'groups30u101'
And the user navigates to the group with name 'groups30u101'
And the user logs in using 's28u101@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on create product button on group page
And the user inputs values in create new product modal window
| elementName | value |
| name | s28u101name1 |
| purchasePrice | 123,56 |
| sellingPrice | 123,56 |

Then the user asserts markUp value is 'Наценка: 0 %' in create new product window

When the user confirms OK in create new product modal window

Then the user waits for modal window closing

When the user clicks on the product with name 's28u101name1'

Then the user asserts markUp value is 'Наценка: 0 %' in edit product window

Scenario: Product markUp assert no purhasePrice

Meta:
@smoke
@id_s38u101s11

GivenStories: precondition/sprint-38/us-101/aPreconditionToUserCreation.story

Given the user with email 's28u101@lighthouse.pro' creates group with name 'groups30u101'
And the user navigates to the group with name 'groups30u101'
And the user logs in using 's28u101@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on create product button on group page
And the user inputs values in create new product modal window
| elementName | value |
| name | s28u101name2 |
| sellingPrice | 123,56 |

Then the user asserts markUp value is not visible in create new product window

When the user confirms OK in create new product modal window

Then the user waits for modal window closing

When the user clicks on the product with name 's28u101name2'

Then the user asserts markUp value is not visible in edit product window

Scenario: Product markUp assert no sellingPrice

Meta:
@smoke
@id_s38u101s12

GivenStories: precondition/sprint-38/us-101/aPreconditionToUserCreation.story

Given the user with email 's28u101@lighthouse.pro' creates group with name 'groups30u101'
And the user navigates to the group with name 'groups30u101'
And the user logs in using 's28u101@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on create product button on group page
And the user inputs values in create new product modal window
| elementName | value |
| name | s28u101name3 |
| purchasePrice | 123,56 |

Then the user asserts markUp value is not visible in create new product window

When the user confirms OK in create new product modal window

Then the user waits for modal window closing

When the user clicks on the product with name 's28u101name3'

Then the user asserts markUp value is not visible in edit product window

Scenario: Product markUp assert no price values are entered

Meta:
@smoke
@id_s38u101s13

GivenStories: precondition/sprint-38/us-101/aPreconditionToUserCreation.story

Given the user with email 's28u101@lighthouse.pro' creates group with name 'groups30u101'
And the user navigates to the group with name 'groups30u101'
And the user logs in using 's28u101@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on create product button on group page
And the user inputs values in create new product modal window
| elementName | value |
| name | s28u101name4 |

Then the user asserts markUp value is not visible in create new product window

When the user confirms OK in create new product modal window

Then the user waits for modal window closing

When the user clicks on the product with name 's28u101name4'

Then the user asserts markUp value is not visible in edit product window

Scenario: Product vat 0 % field assert value

Meta:
@smoke
@id_s38u101s14

GivenStories: precondition/sprint-38/us-101/aPreconditionToUserCreation.story

Given the user with email 's28u101@lighthouse.pro' creates group with name 'groups30u101'
And the user navigates to the group with name 'groups30u101'
And the user logs in using 's28u101@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on create product button on group page
And the user inputs values in create new product modal window
| elementName | value |
| name | Продукт0% |
| vat | Не облагается |

And the user confirms OK in create new product modal window

Then the user waits for modal window closing

When the user clicks on the product with name 'Продукт0%'

Then the user checks stored values in edit product modal window


Scenario: Product vat 10 % field assert value

Meta:
@smoke
@id_s38u101s15

GivenStories: precondition/sprint-38/us-101/aPreconditionToUserCreation.story

Given the user with email 's28u101@lighthouse.pro' creates group with name 'groups30u101'
And the user navigates to the group with name 'groups30u101'
And the user logs in using 's28u101@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on create product button on group page
And the user inputs values in create new product modal window
| elementName | value |
| name | Продукт10% |
| vat | 10% |

And the user confirms OK in create new product modal window

Then the user waits for modal window closing

When the user clicks on the product with name 'Продукт10%'

Then the user checks stored values in edit product modal window

Scenario: Product vat 18 % field assert value

Meta:
@smoke
@id_s38u101s16

GivenStories: precondition/sprint-38/us-101/aPreconditionToUserCreation.story

Given the user with email 's28u101@lighthouse.pro' creates group with name 'groups30u101'
And the user navigates to the group with name 'groups30u101'
And the user logs in using 's28u101@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on create product button on group page
And the user inputs values in create new product modal window
| elementName | value |
| name | Продукт18% |
| vat | 18% |

And the user confirms OK in create new product modal window

Then the user waits for modal window closing

When the user clicks on the product with name 'Продукт18%'

Then the user checks stored values in edit product modal window

Scenario: Product with no selling price has empty selling price value on product list

Meta:
@smoke
@id_s38u101s17

GivenStories: precondition/sprint-38/us-101/aPreconditionToUserCreation.story

Given the user with email 's28u101@lighthouse.pro' creates group with name 'groups30u101'
And the user navigates to the group with name 'groups30u101'
And the user logs in using 's28u101@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on create product button on group page
And the user inputs values in create new product modal window
| elementName | value |
| name | Продукт11 |
| sellingPrice | |
| barcode | 11 |

And the user confirms OK in create new product modal window

Then the user waits for modal window closing

Then the user asserts the product list contain products with values
| name | sellingPrice | barcode |
| Продукт11 | | 11 |

When the user clicks on the product with name 'Продукт11'

Then the user checks stored values in edit product modal window

Scenario: Product with no barcode has empty barcode value on product list

Meta:
@smoke
@id_s38u101s18

GivenStories: precondition/sprint-38/us-101/aPreconditionToUserCreation.story

Given the user with email 's28u101@lighthouse.pro' creates group with name 'groups30u101'
And the user navigates to the group with name 'groups30u101'
And the user logs in using 's28u101@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on create product button on group page
And the user inputs values in create new product modal window
| elementName | value |
| name | Продукт12 |
| barcode | |
| sellingPrice | 123,45 |

And the user confirms OK in create new product modal window

Then the user waits for modal window closing

Then the user asserts the product list contain products with values
| name | sellingPrice | barcode |
| Продукт12 | 123,45 | |

When the user clicks on the product with name 'Продукт12'

Then the user checks stored values in edit product modal window
