Meta:
@sprint_32
@us_68.1
@invoice

Scenario: Invoice edit accepter is required

Meta:
@id

GivenStories: precondition/sprint-32/us-68_1/aUsersPreconditionToStory.story

Given there is the invoice in the store by 'departmentManager-s32u681'

Given the user opens last created invoice page
And the user logs in using 'departmentManager-s32u681' userName and 'lighthouse' password

When the user inputs values on invoice page
| elementName | value |
| accepter |  |

When the user accepts products and saves the invoice

Then the user sees exact error messages
| error message |
| Заполните это поле |

Scenario: Invoice edit accepter range negative validation

Meta:
@id

GivenStories: precondition/sprint-32/us-68_1/aUsersPreconditionToStory.story

Given there is the invoice in the store by 'departmentManager-s32u681'

Given the user opens last created invoice page
And the user logs in using 'departmentManager-s32u681' userName and 'lighthouse' password

When the user generates symbol data with '101' number in the 'accepter' invoice field

Then the user asserts 'accepter' invoice field data has '101' symbols length

When the user accepts products and saves the invoice

Then the user sees exact error messages
| error message |
| Не более 100 символов |

Scenario: Invoice edit accepter range positive validation

Meta:
@id

GivenStories: precondition/sprint-32/us-68_1/aUsersPreconditionToStory.story

Given there is the invoice in the store by 'departmentManager-s32u681'

Given the user opens last created invoice page
And the user logs in using 'departmentManager-s32u681' userName and 'lighthouse' password

When the user generates symbol data with '100' number in the 'accepter' invoice field

Then the user asserts 'accepter' invoice field data has '100' symbols length

When the user accepts products and saves the invoice

Then the user sees no error messages

Scenario: Invoice edit supplierInvoiceNumber is required

Meta:
@id

GivenStories: precondition/sprint-32/us-68_1/aUsersPreconditionToStory.story

Given there is the invoice in the store by 'departmentManager-s32u681'

Given the user opens last created invoice page
And the user logs in using 'departmentManager-s32u681' userName and 'lighthouse' password

When the user inputs values on invoice page
| elementName | value |
| supplierInvoiceNumber |  |

When the user accepts products and saves the invoice

Then the user sees exact error messages
| error message |
| Заполните это поле |

Scenario: Invoice edit supplierInvoiceNumber date range negative validation

Meta:
@id

GivenStories: precondition/sprint-32/us-68_1/aUsersPreconditionToStory.story

Given there is the invoice in the store by 'departmentManager-s32u681'

Given the user opens last created invoice page
And the user logs in using 'departmentManager-s32u681' userName and 'lighthouse' password

When the user generates symbol data with '101' number in the 'supplierInvoiceNumber' invoice field

Then the user asserts 'supplierInvoiceNumber' invoice field data has '101' symbols length

When the user accepts products and saves the invoice

Then the user sees exact error messages
| error message |
| Не более 100 символов |

Scenario: Invoice edit supplierInvoiceNumber date range positive validation

Meta:
@id

GivenStories: precondition/sprint-32/us-68_1/aUsersPreconditionToStory.story

Given there is the invoice in the store by 'departmentManager-s32u681'

Given the user opens last created invoice page
And the user logs in using 'departmentManager-s32u681' userName and 'lighthouse' password

When the user generates symbol data with '100' number in the 'supplierInvoiceNumber' invoice field

Then the user asserts 'supplierInvoiceNumber' invoice field data has '100' symbols length

When the user accepts products and saves the invoice

Then the user sees no error messages

Scenario: Invoice edit legalEntity is required

Meta:
@id

GivenStories: precondition/sprint-32/us-68_1/aUsersPreconditionToStory.story

Given there is the invoice in the store by 'departmentManager-s32u681'

Given the user opens last created invoice page
And the user logs in using 'departmentManager-s32u681' userName and 'lighthouse' password

When the user inputs values on invoice page
| elementName | value |
| legalEntity |  |

When the user accepts products and saves the invoice

Then the user sees exact error messages
| error message |
| Заполните это поле |

Scenario: Invoice edit legalEntity date range negative validation

Meta:
@id

GivenStories: precondition/sprint-32/us-68_1/aUsersPreconditionToStory.story

Given there is the invoice in the store by 'departmentManager-s32u681'

Given the user opens last created invoice page
And the user logs in using 'departmentManager-s32u681' userName and 'lighthouse' password

When the user generates symbol data with '301' number in the 'legalEntity' invoice field

Then the user asserts 'legalEntity' invoice field data has '301' symbols length

When the user accepts products and saves the invoice

Then the user sees exact error messages
| error message |
| Не более 300 символов |

Scenario: Invoice edit legalEntity date range positive validation

Meta:
@id

GivenStories: precondition/sprint-32/us-68_1/aUsersPreconditionToStory.story

Given there is the invoice in the store by 'departmentManager-s32u681'

Given the user opens last created invoice page
And the user logs in using 'departmentManager-s32u681' userName and 'lighthouse' password

When the user generates symbol data with '300' number in the 'legalEntity' invoice field

Then the user asserts 'legalEntity' invoice field data has '300' symbols length

When the user accepts products and saves the invoice

Then the user sees no error messages

Scenario: Cannot create invoice without product while editing

Meta:
@id_

GivenStories: precondition/sprint-32/us-68_1/aUsersPreconditionToStory.story

Given there is the invoice in the store by 'departmentManager-s32u681'

Given the user opens last created invoice page
And the user logs in using 'departmentManager-s32u681' userName and 'lighthouse' password

When the user clicks on invoice product in last created invoice

When the user clicks on delete icon and deletes last added invoice product

Then the user waits for the invoice product edition preloader finish

When the user accepts products and saves the invoice

Then the user sees exact error messages
| error message |
| Нужно добавить минимум один товар |

Scenario: Invoice edit product quantity positive validation

Meta:
@id_

Given there is the user with name 'departmentManager-s32u681', position 'departmentManager-s32u681', username 'departmentManager-s32u681', password 'lighthouse', role 'departmentManager'
And there is the store with number 'store-s32u681' managed by department manager named 'departmentManager-s32u681'

Given there is the invoice in the store by 'departmentManager-s32u681'

Given the user opens last created invoice page
And the user logs in using 'departmentManager-s32u681' userName and 'lighthouse' password

When the user clicks on invoice product in last created invoice

When the user inputs quantity value on the invoice product in last created invoice
And the user presses 'ENTER' key button

Then the user waits for the invoice product edition preloader finish

Then the user checks the last created invoice product found has quantity equals to expectedValue
And the user sees no error messages

Examples:
| value | expectedValue |
| 1 | 1,0 |
| 1.1 | 1,1 |
| 1,1 | 1,1 |
| 1,12 | 1,12 |
| 1.12 | 1,12 |
| 1.123 | 1,123 |
| 1,123 | 1,123 |
| 1000 | 1 000,0 |
| 1 000 | 1 000,0 |
| 123123,123 | 123 123,123 |
| 123 123,123 | 123 123,123 |

Scenario: Invoice edit product quantity negative validation

Meta:
@id_

Given there is the user with name 'departmentManager-s32u681', position 'departmentManager-s32u681', username 'departmentManager-s32u681', password 'lighthouse', role 'departmentManager'
And there is the store with number 'store-s32u681' managed by department manager named 'departmentManager-s32u681'

Given there is the invoice in the store by 'departmentManager-s32u681'

Given the user opens last created invoice page
And the user logs in using 'departmentManager-s32u681' userName and 'lighthouse' password

When the user clicks on invoice product in last created invoice

When the user inputs quantity value on the invoice product in last created invoice
And the user presses 'ENTER' key button

Then the user waits for the invoice product edition preloader finish

Then the user user sees errorMessage

Examples:
| value | errorMessage |
|  | Заполните это поле |
| -10 | Значение должно быть больше 0 |
| -1 | Значение должно быть больше 0 |
| -1,12 | Значение должно быть больше 0 |
| -1.12 | Значение должно быть больше 0 |
| -1.123 | Значение должно быть больше 0 |
| -1,1234 | Значение не должно содержать больше 3 цифр после запятой |
| -1,123 | Значение должно быть больше 0 |
| 1,1234 | Значение не должно содержать больше 3 цифр после запятой |
| 1.1234 | Значение не должно содержать больше 3 цифр после запятой |
| 0 | Значение должно быть больше 0 |
| asdd | Значение должно быть числом |
| ADHF | Значение должно быть числом |
| домик | Значение должно быть числом |
| ДОМИЩЕ | Значение должно быть числом |
| ^%#$)& | Значение должно быть числом |

Scenario: Invoice edit product price positive validation

Meta:
@id_

Given there is the user with name 'departmentManager-s32u681', position 'departmentManager-s32u681', username 'departmentManager-s32u681', password 'lighthouse', role 'departmentManager'
And there is the store with number 'store-s32u681' managed by department manager named 'departmentManager-s32u681'

Given there is the invoice in the store by 'departmentManager-s32u681'

Given the user opens last created invoice page
And the user logs in using 'departmentManager-s32u681' userName and 'lighthouse' password

When the user clicks on invoice product in last created invoice

When the user presses 'TAB' key button

Then the user waits for the invoice product edition preloader finish

When the user inputs price value on the invoice product in last created invoice
And the user presses 'ENTER' key button

Then the user waits for the invoice product edition preloader finish

Then the user sees no error messages
And the user checks the last created invoice product has price equals to expectedValue

Examples:
| value | expectedValue |
| ,78 | 0,78 |
| .78 | 0,78 |
| 123.25 | 123,25 |
| 12.56 | 12,56 |
| 1,2 | 1,20 |
| 1,99 | 1,99 |
| 10000000 | 10 000 000,00 |
| 1000 | 1 000,00 |
| 1 000 | 1 000,00 |
| 123123,12 | 123 123,12 |
| 123 123,12 | 123 123,12 |

Scenario: Invoice edit price product negative validation

Meta:
@id_

Given there is the user with name 'departmentManager-s32u681', position 'departmentManager-s32u681', username 'departmentManager-s32u681', password 'lighthouse', role 'departmentManager'
And there is the store with number 'store-s32u681' managed by department manager named 'departmentManager-s32u681'

Given there is the invoice in the store by 'departmentManager-s32u681'

Given the user opens last created invoice page
And the user logs in using 'departmentManager-s32u681' userName and 'lighthouse' password

When the user clicks on invoice product in last created invoice

When the user presses 'TAB' key button

Then the user waits for the invoice product edition preloader finish

When the user inputs price value on the invoice product in last created invoice
And the user presses 'ENTER' key button

Then the user waits for the invoice product edition preloader finish

Then the user user sees errorMessage

Examples:
| value | errorMessage |
|  | Заполните это поле |
| 12,123 | Цена не должна содержать больше 2 цифр после запятой |
| -1 | Цена не должна быть меньше или равна нулю |
| -1,123 | Цена не должна быть меньше или равна нулю, Цена не должна содержать больше 2 цифр после запятой |
| 0 | Цена не должна быть меньше или равна нулю |
| harry | Значение должно быть числом |
| HARRY | Значение должно быть числом |
| цена | Значение должно быть числом |
| ЦЕНА | Значение должно быть числом |
| @#$#$# | Значение должно быть числом |
| 10000001 | Цена не должна быть больше 10000000 |