Meta:
@sprint_30
@us_67
@order

Narrative:
As a заведующий отделом
I want to отредактировать ранее созданный заказ
In order to исправить в нем ошибки

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story

Scenario: Order edit

Meta:
@id_s30u67s1
@smoke

GivenStories: precondition/sprint-30/us-67/aPreconditionToStoryUs67.story

Given there is the supplier with name 'supplier-s30u67s1'
And there is the order in the store by 'departmentManager-s30u67'
And there is the product with 'name-s30u67s1' name, 'sku-s30u67s1' sku, 'barCode-s30u67s1' barcode

Given the user opens last created order page
And the user logs in using 'departmentManager-s30u67' userName and 'lighthouse' password

When the user inputs values on order page
| elementName | value |
| supplier | supplier-s30u67s1 |

When the user clicks on order product in last created order

When the user inputs values in edition new product form on the order page
| elementName | value |
| name | name-s30u67s1 |
| quantity | 10 |

When the user clicks the edit order product button

Then the user checks the order products list contains entry
| name | units |quantity | retailPrice | totalSum |
| name-s30u67s1 | кг | 10,0 | 123,00 | 1 230,00 |

When the user clicks the save order button

Then the user checks the orders list contains entry
| number | supplier | date |
| {lastCreatedOrderNumber} | supplier-s30u67s1 | {todayDate} |

When the user clicks on last created order on the orders list page

Then the user checks the filled order page values
| elementName | value |
| supplier | supplier-s30u67s1 |

Then the user checks the order products list contains entry
| name | units |quantity | retailPrice | totalSum |
| name-s30u67s1 | кг | 10,0 | 123,00 | 1 230,00 |

Scenario: Order supplier edit - check supplier on orders list page

Meta:
@id_s30u67s2
@smoke

GivenStories: precondition/sprint-30/us-67/aPreconditionToStoryUs67.story

Given there is the supplier with name 'supplier-s30u67s1'
And there is the order in the store by 'departmentManager-s30u67'

Given the user opens last created order page
And the user logs in using 'departmentManager-s30u67' userName and 'lighthouse' password

When the user inputs values on order page
| elementName | value |
| supplier | supplier-s30u67s1 |

When the user clicks the save order button

Then the user checks the orders list contains entry
| number | supplier | date |
| {lastCreatedOrderNumber} | supplier-s30u67s1 | {todayDate} |

Scenario: Order supplier edit - check supplier on orders list page cancel

Meta:
@id_s30u67s3

GivenStories: precondition/sprint-30/us-67/aPreconditionToStoryUs67.story

Given there is the supplier with name 'supplier-s30u67s1'
And there is the order in the store by 'departmentManager-s30u67'

Given the user opens last created order page
And the user logs in using 'departmentManager-s30u67' userName and 'lighthouse' password

When the user inputs values on order page
| elementName | value |
| supplier | supplier-s30u67s1 |

When the user clicks the cancel link button

Then the user checks the orders list contains entry
| number | supplier | date |
| {lastCreatedOrderNumber} | {lastSupplierOrder} | {todayDate} |

Scenario: Order product edit - edit product name

Meta:
@id_s30u67s4
@smoke

GivenStories: precondition/sprint-30/us-67/aPreconditionToStoryUs67.story

Given there is the supplier with name 'supplier-s30u67s1'
And there is the order in the store by 'departmentManager-s30u67'
And there is the product with 'name-s30u67s1' name, 'sku-s30u67s1' sku, 'barCode-s30u67s1' barcode

Given the user opens last created order page
And the user logs in using 'departmentManager-s30u67' userName and 'lighthouse' password

When the user clicks on order product in last created order

When the user inputs values in edition new product form on the order page
| elementName | value |
| name | name-s30u67s1 |
| quantity | 10 |

When the user clicks the edit order product button

Then the user checks the order products list contains entry
| name | units |quantity | retailPrice | totalSum |
| name-s30u67s1 | кг | 10,0 | 123,00 | 1 230,00 |

When the user clicks the save order button

When the user clicks on last created order on the orders list page

Then the user checks the order products list contains entry
| name | units |quantity | retailPrice | totalSum |
| name-s30u67s1 | кг | 10,0 | 123,00 | 1 230,00 |

Scenario: Order product edit - add new product

Meta:
@id_s30u67s5
@smoke

GivenStories: precondition/sprint-30/us-67/aPreconditionToStoryUs67.story

Given there is the supplier with name 'supplier-s30u67s1'
And there is the order in the store by 'departmentManager-s30u67'
And there is the product with 'name-s30u67s1' name, 'sku-s30u67s1' sku, 'barCode-s30u67s1' barcode

Given the user opens last created order page
And the user logs in using 'departmentManager-s30u67' userName and 'lighthouse' password

When the user inputs values in addition new product form on the order page
| elementName | value |
| name | name-s30u67s1 |
| quantity | 10 |
And the user clicks the add order product button

Then the user checks the order products list contains entry
| name | units |quantity | retailPrice | totalSum |
| {lastCreatedProductName} | шт. | 1,0 | 100,00 | 100,00 |
| name-s30u67s1 | кг | 10,0 | 123,00 | 1 230,00 |

When the user clicks the save order button

When the user clicks on last created order on the orders list page

Then the user checks the order products list contains entry
| name | units |quantity | retailPrice | totalSum |
| {lastCreatedProductName} | шт. | 1,0 | 100,00 | 100,00 |
| name-s30u67s1 | кг | 10,0 | 123,00 | 1 230,00 |

Scenario: Order product edit cancel

Meta:
@id_s30u67s6

GivenStories: precondition/sprint-30/us-67/aPreconditionToStoryUs67.story

Given there is the supplier with name 'supplier-s30u67s1'
And there is the order in the store by 'departmentManager-s30u67'
And there is the product with 'name-s30u67s1' name, 'sku-s30u67s1' sku, 'barCode-s30u67s1' barcode
And there is the product with 'name-s30u67s2' name, 'sku-s30u67s2' sku, 'barCode-s30u67s2' barcode

Given the user opens last created order page
And the user logs in using 'departmentManager-s30u67' userName and 'lighthouse' password

When the user clicks on order product in last created order

When the user inputs values in edition new product form on the order page
| elementName | value |
| name | name-s30u67s1 |
| quantity | 10 |

When the user clicks the edit order product button

Then the user checks the order products list contains entry
| name | units |quantity | retailPrice | totalSum |
| name-s30u67s1 | кг | 10,0 | 123,00 | 1 230,00 |

When the user clicks on the order product by name 'name-s30u67s1'

When the user inputs values in edition new product form on the order page
| elementName | value |
| name | name-s30u67s2 |
| quantity | 5 |

When the user clicks the cancel order product button

Then the user checks the order products list contains entry
| name | units |quantity | retailPrice | totalSum |
| name-s30u67s1 | кг | 10,0 | 123,00 | 1 230,00 |

Scenario: Assert order number

Meta:
@id_s30u67s7

GivenStories: precondition/sprint-30/us-67/aPreconditionToStoryUs67.story

Given there is the order in the store by 'departmentManager-s30u67'

Given the user opens last created order page
And the user logs in using 'departmentManager-s30u67' userName and 'lighthouse' password

Then the user checks the order number is expected
