Meta:
@sprint_30
@us_67
@order

Narrative:
Валидация при редактировании

Scenario: Order edit - Edition product form - autocomplete is required

Meta:
@id_

GivenStories: precondition/sprint-30/us-67/aPreconditionToStoryUs67.story

Given there is the order in the store by 'departmentManager-s30u67'

Given the user opens last created order page
And the user logs in using 'departmentManager-s30u67' userName and 'lighthouse' password

When the user clicks on order product in last created order

When the user inputs values in edition new product form on the order page
| elementName | value |
| name | ! |

When the user clicks the edit order product button

Then the user sees error messages
| error message |
| Заполните это поле |

Scenario: Order edit - Edition product form - quantity is required

Meta:
@id_

GivenStories: precondition/sprint-30/us-67/aPreconditionToStoryUs67.story

Given there is the order in the store by 'departmentManager-s30u67'

Given the user opens last created order page
And the user logs in using 'departmentManager-s30u67' userName and 'lighthouse' password

When the user clicks on order product in last created order

When the user inputs values in edition new product form on the order page
| elementName | value |
| quantity | |

When the user clicks the edit order product button

Then the user sees error messages
| error message |
| Заполните это поле |

Scenario: Order edit - Edition product form - autocomplete validation

Meta:
@id_


GivenStories: precondition/sprint-30/us-67/aPreconditionToStoryUs67.story

Given there is the order in the store by 'departmentManager-s30u67'

Given the user opens last created order page
And the user logs in using 'departmentManager-s30u67' userName and 'lighthouse' password

When the user clicks on order product in last created order

When the user inputs values in edition new product form on the order page
| elementName | value |
| name | !dfdfdfdfdfdfdf |
| quantity | 5 |

When the user clicks the edit order product button

Then the user sees error messages
| error message |
| Такого товара не существует |

Scenario: Order edit - Edition product form - quantity positive validation

Meta:
@id_

Given there is the user with name 'departmentManager-s30u67', position 'departmentManager-s30u67', username 'departmentManager-s30u67', password 'lighthouse', role 'departmentManager'
And there is the store with number 'store-s30u67' managed by department manager named 'departmentManager-s30u67'

Given there is the order in the store by 'departmentManager-s30u67'

Given the user opens last created order page
And the user logs in using 'departmentManager-s30u67' userName and 'lighthouse' password

When the user clicks on order product in last created order

When the user inputs value in elementName 'quantity' in addition new product form on the order page

When the user clicks the edit order product button

Then the user checks the order product in last created order has quantity equals to expectedValue
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

Scenario: Order edit - Edition product form - quantity negative validation

Meta:
@id_

Given there is the user with name 'departmentManager-s30u67', position 'departmentManager-s30u67', username 'departmentManager-s30u67', password 'lighthouse', role 'departmentManager'
And there is the store with number 'store-s30u67' managed by department manager named 'departmentManager-s30u67'

Given there is the order in the store by 'departmentManager-s30u67'

Given the user opens last created order page
And the user logs in using 'departmentManager-s30u67' userName and 'lighthouse' password

When the user clicks on order product in last created order

When the user inputs value in elementName 'quantity' in addition new product form on the order page

When the user clicks the edit order product button

Then the user user sees errorMessage

Examples:
| value | errorMessage |
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
