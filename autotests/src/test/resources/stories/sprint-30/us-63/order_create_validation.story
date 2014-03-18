Meta:
@sprint_30
@us_63
@order

Narrative:
Валидация при создании

Scenario: Supplier option is required

Meta:
@id_s30u63s11

GivenStories: precondition/sprint-30/us-63/aPreconditionToStoryUs63.story

Given the user opens order create page
And the user logs in using 'departmentManager-s30u63' userName and 'lighthouse' password

When the user clicks the save order button

Then the user sees error messages
|error message |
| Выберите поставщика |

Scenario: Cannot create order without product

Meta:
@id_s30u63s12

GivenStories: precondition/sprint-30/us-63/aPreconditionToStoryUs63.story

Given the user opens order create page
And the user logs in using 'departmentManager-s30u63' userName and 'lighthouse' password

When the user inputs values on order page
| elementName | value |
| supplier | supplier-s30u63s1 |

When the user clicks the save order button

Then the user sees error messages
|error message |
| Нужно добавить минимум один товар |

Scenario: Addition product form - autocomplete is required

Meta:
@id_s30u63s13

GivenStories: precondition/sprint-30/us-63/aPreconditionToStoryUs63.story

Given the user opens order create page
And the user logs in using 'departmentManager-s30u63' userName and 'lighthouse' password

When the user inputs values on order page
| elementName | value |
| supplier | supplier-s30u63s1 |
When the user inputs values in addition new product form on the order page
| elementName | value |
| quantity | 5 |

When the user clicks the add order product button

Then the user sees error messages
| error message |
| Заполните это поле |

Scenario: Addition product form - quantity is required

Meta:
@id_s30u63s14

GivenStories: precondition/sprint-30/us-63/aPreconditionToStoryUs63.story

Given the user opens order create page
And the user logs in using 'departmentManager-s30u63' userName and 'lighthouse' password

When the user inputs values on order page
| elementName | value |
| supplier | supplier-s30u63s1 |
When the user inputs values in addition new product form on the order page
| elementName | value |
| name | name-3063 |

When the user clicks the add order product button

Then the user sees error messages
| error message |
| Заполните это поле |

Scenario: Addition product form - autocomplete validation

Meta:
@id_s30u63s15

GivenStories: precondition/sprint-30/us-63/aPreconditionToStoryUs63.story

Given the user opens order create page
And the user logs in using 'departmentManager-s30u63' userName and 'lighthouse' password

When the user inputs values on order page
| elementName | value |
| supplier | supplier-s30u63s1 |
When the user inputs values in addition new product form on the order page
| elementName | value |
| name | !dfdfdfdfdfdfdf |
| quantity | 5 |

When the user clicks the add order product button

Then the user sees error messages
| error message |
| Такого товара не существует |

Scenario: Addition product form - quantity positive validation

Meta:
@id

Given there is the supplier with name 'supplier-s30u63s1'
And there is the subCategory with name 'defaultSubCategory-s30u63' related to group named 'defaultGroup-s30u63' and category named 'defaultCategory-s30u63'
And the user sets subCategory 'defaultSubCategory-s30u63' mark up with max '10' and min '0' values
Given there is the product with 'name-3063' name, '3063' sku, '3063' barcode, 'unit' units, '100' purchasePrice of group named 'defaultGroup-s30u63', category named 'defaultCategory-s30u63', subcategory named 'defaultSubCategory-s30u63'
Given there is the user with name 'departmentManager-s30u63', position 'departmentManager-s30u63', username 'departmentManager-s30u63', password 'lighthouse', role 'departmentManager'
And there is the store with number 'store-s30u63' managed by department manager named 'departmentManager-s30u63'

Given the user opens order create page
And the user logs in using 'departmentManager-s30u63' userName and 'lighthouse' password

When the user inputs values on order page
| elementName | value |
| supplier | supplier-s30u63s1 |
When the user inputs values in addition new product form on the order page
| elementName | value |
| name | name-3063 |
And the user inputs value in elementName 'quantity' in addition new product form on the order page

When the user clicks the add order product button

Then the user checks the order product found by name 'name-3063' has quantity equals to expectedValue
And the user sees no error messages

Examples:
| value | expectedValue |
| 1 | 1 |
| 1.1 | 1,1 |
| 1,1 | 1,1 |
| 1,12 | 1,12 |
| 1.12 | 1,12 |
| 1.123 | 1,123 |
| 1,123 | 1,123 |

Scenario: Addition product form - quantity negative validation

Meta:
@id_s30u63s16

Given there is the supplier with name 'supplier-s30u63s1'
And there is the subCategory with name 'defaultSubCategory-s30u63' related to group named 'defaultGroup-s30u63' and category named 'defaultCategory-s30u63'
And the user sets subCategory 'defaultSubCategory-s30u63' mark up with max '10' and min '0' values
Given there is the product with 'name-3063' name, '3063' sku, '3063' barcode, 'unit' units, '100' purchasePrice of group named 'defaultGroup-s30u63', category named 'defaultCategory-s30u63', subcategory named 'defaultSubCategory-s30u63'
Given there is the user with name 'departmentManager-s30u63', position 'departmentManager-s30u63', username 'departmentManager-s30u63', password 'lighthouse', role 'departmentManager'
And there is the store with number 'store-s30u63' managed by department manager named 'departmentManager-s30u63'

Given the user opens order create page
And the user logs in using 'departmentManager-s30u63' userName and 'lighthouse' password

When the user inputs values on order page
| elementName | value |
| supplier | supplier-s30u63s1 |
And the user inputs values in addition new product form on the order page
| elementName | value |
| name | name-3063 |

When the user inputs value in elementName 'quantity' in addition new product form on the order page

When the user clicks the add order product button

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

Scenario: Cannot create order if departmantManager dont have store

Meta:
@id_s30u63s17

Given there is the user with name 'departmentManager-s30u63-no-store', position 'departmentManager-s30u63-no-store', username 'departmentManager-s30u63-no-store', password 'lighthouse', role 'departmentManager'
And the user opens the authorization page
And the user logs in using 'departmentManager-s30u63-no-store' userName and 'lighthouse' password

Then the user checks the orders navigation menu item is not visible

Scenario: Cannot create order throug link if departmantManager dont have store

Meta:
@id_s30u63s18

Given there is the user with name 'departmentManager-s30u63-no-store', position 'departmentManager-s30u63-no-store', username 'departmentManager-s30u63-no-store', password 'lighthouse', role 'departmentManager'
And the user opens order create page
And the user logs in using 'departmentManager-s30u63-no-store' userName and 'lighthouse' password

Then the user sees the 403 error

Scenario: Cannot view order list throug link if departmantManager dont have store

Meta:
@id_s30u63s19

Given there is the user with name 'departmentManager-s30u63-no-store', position 'departmentManager-s30u63-no-store', username 'departmentManager-s30u63-no-store', password 'lighthouse', role 'departmentManager'
And the user opens orders list page
And the user logs in using 'departmentManager-s30u63-no-store' userName and 'lighthouse' password

Then the user sees the 403 error

Scenario: The order product addition form fields should be cleared after adding order product

Meta:
@id

GivenStories: precondition/sprint-30/us-63/aPreconditionToStoryUs63.story

Given the user opens order create page
And the user logs in using 'departmentManager-s30u63' userName and 'lighthouse' password

When the user inputs values in addition new product form on the order page
| elementName | value |
| name | name-3063 |
| quantity | 5 |

When the user clicks the add order product button

Then the user checks the autocomplete values
| elementName | value |
| name |  |
| quantity |  |
| retailPrice | 0,00 |
| totalSum | 0,00 |
| inventory | — |

