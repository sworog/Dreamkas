Meta:
@sprint_30
@us_63
@order

Narrative:
As a заведующий отделом
I want to зафиксировать в системе заказ поставщику
In order to чтобы потом принимать поставку на основании заказа

Scenario: Order create

Meta:
@id_s30u63s1
@order
@smoke
@description simple order create with one product

GivenStories: precondition/sprint-30/us-63/aPreconditionToStoryUs63.story

Given the user opens the authorization page
And the user logs in using 'departmentManager-s30u63' userName and 'lighthouse' password

When the user clicks the menu orders item
And the user clicks the create order link on order page menu navigation

When the user inputs values on order page
| elementName | value |
| supplier | supplier-s30u63s1 |
And the user inputs values in addition new product form on the order page
| elementName | value |
| name | name-3063 |
| quantity | 5 |
And the user clicks the add order product button

Then the user checks the order products list contains entry
| name | units |quantity | retailPrice | totalSum |
| name-3063 | шт. | 5 | 100,00 | 500,00 |

And the user checks the order total sum is 'Итого: 500,00 руб'

When the user clicks the save order button

Then the user sees no error messages

Scenario: Order create with three products

Meta:
@id_s30u63s2
@order
@smoke
@description simple order create with three products

GivenStories: precondition/sprint-30/us-63/aPreconditionToStoryUs63.story

Given there is the product with 'name-306301' name, '306301' sku, '3063' barcode, 'liter' units, '78.90' purchasePrice of group named 'defaultGroup-s30u63', category named 'defaultCategory-s30u63', subcategory named 'defaultSubCategory-s30u63'
And there is the product with 'name-306302' name, '306302' sku, '3063' barcode, 'kg' units, '56.78' purchasePrice of group named 'defaultGroup-s30u63', category named 'defaultCategory-s30u63', subcategory named 'defaultSubCategory-s30u63'

Given the user opens the authorization page
And the user logs in using 'departmentManager-s30u63' userName and 'lighthouse' password

When the user clicks the menu orders item
And the user clicks the create order link on order page menu navigation

When the user inputs values on order page
| elementName | value |
| supplier | supplier-s30u63s1 |
And the user inputs values in addition new product form on the order page
| elementName | value |
| name | name-3063 |
| quantity | 5,7 |
And the user clicks the add order product button

Then the user checks the order products list contains entry
| name | units |quantity | retailPrice | totalSum |
| name-3063 | шт. | 5,7 | 100,00 | 570,00 |

When the user inputs values on order page
| elementName | value |
| supplier | supplier-s30u63s1 |
And the user inputs values in addition new product form on the order page
| elementName | value |
| name | name-306301 |
| quantity | 5,67 |
And the user clicks the add order product button

Then the user checks the order products list contains entry
| name | units |quantity | retailPrice | totalSum |
| name-306301 | литр | 5,67 | 78,90 | 447,36 |

When the user inputs values on order page
| elementName | value |
| supplier | supplier-s30u63s1 |
And the user inputs values in addition new product form on the order page
| elementName | value |
| name | name-306302 |
| quantity | 45,789 |
And the user clicks the add order product button

Then the user checks the order products list contains entry
| name | units |quantity | retailPrice | totalSum |
| name-306302 | кг | 45,789 | 56,78 | 2 599,89 |

And the user checks the order total sum is 'Итого: 3 617,35 руб'

When the user clicks the save order button

Then the user sees no error messages

Scenario: Verify product all data found by autocomplete in addittion form

Meta:
@id_s30u63s3
@smoke
@description verifying product data when the product is choosen by autocomplete

GivenStories: precondition/sprint-30/us-63/aPreconditionToStoryUs63.story

Given the user opens order create page
And the user logs in using 'departmentManager-s30u63' userName and 'lighthouse' password

When the user inputs values in addition new product form on the order page
| elementName | value |
| name | name-3063 |
| quantity | 50 |

Then the user checks the filled autocomplete values in product addition form
| elementName | value |
| name | name-3063 |
| quantity | 5 |
| retailPrice | 100 |
| totalSum | 5 000 |
| inventory | 0 |

Scenario: Verify product inventory found by autocomplete in addittion form if there is invoice already

Meta:
@id_s30u63s4
@description verifying product inventory if there is invoice already when the product is choosen by autocomplete

GivenStories: precondition/sprint-30/us-63/aPreconditionToStoryUs63.story,
              precondition/sprint-30/us-63/aPreconditionToScenario3.story

Given the user opens order create page
And the user logs in using 'departmentManager-s30u63' userName and 'lighthouse' password

When the user inputs values in addition new product form on the order page
| elementName | value |
| name | name-3063-1 |

Then the user checks the filled autocomplete values in product addition form
| elementName | value |
| inventory | 12,456 |

Scenario: Verify product inventory found by autocomplete in addittion form if there is writeOff already

Meta:
@id_s30u63s5
@description verifying product inventory if there is writeOff already when the product is choosen by autocomplete

GivenStories: precondition/sprint-30/us-63/aPreconditionToStoryUs63.story,
              precondition/sprint-30/us-63/aPreconditionToScenario4.story

Given the user opens order create page
And the user logs in using 'departmentManager-s30u63' userName and 'lighthouse' password

When the user inputs values in addition new product form on the order page
| elementName | value |
| name | name-3063-2 |

Then the user checks the filled autocomplete values in product addition form
| elementName | value |
| inventory | -43 |

Scenario: Verify product sum calculation with dot

Meta:
@id_s30u63s6
@smoke
@description verifying product sum calculation

GivenStories: precondition/sprint-30/us-63/aPreconditionToStoryUs63.story

Given the user opens order create page
And the user logs in using 'departmentManager-s30u63' userName and 'lighthouse' password

When the user inputs values in addition new product form on the order page
| elementName | value |
| name | name-3063 |
| quantity | 0.456 |

Then the user checks the filled autocomplete values in product addition form
| elementName | value |
| totalSum | 45,60 |

Scenario: Verify product sum calculation with comma

Meta:
@id_s30u63s7
@smoke
@description verifying product sum calculation

GivenStories: precondition/sprint-30/us-63/aPreconditionToStoryUs63.story

Given the user opens order create page
And the user logs in using 'departmentManager-s30u63' userName and 'lighthouse' password

When the user inputs values in addition new product form on the order page
| elementName | value |
| name | name-3063 |
| quantity | 0,456 |

Then the user checks the filled autocomplete values in product addition form
| elementName | value |
| totalSum | 45,60 |

Scenario: Verify product sum calculation with big amount

Meta:
@id_s30u63s8
@smoke
@description verifying product sum calculation

GivenStories: precondition/sprint-30/us-63/aPreconditionToStoryUs63.story

Given the user opens order create page
And the user logs in using 'departmentManager-s30u63' userName and 'lighthouse' password

When the user inputs values in addition new product form on the order page
| elementName | value |
| name | name-3063 |
| quantity | 56745 |

Then the user checks the filled autocomplete values in product addition form
| elementName | value |
| totalSum | 5 674 500,00 |

Scenario: Verify autocomplete product with no price is choosen

Meta:
@id_s30u63s9

Given there is the user with name 'departmentManager-s30u63', position 'departmentManager-s30u63', username 'departmentManager-s30u63', password 'lighthouse', role 'departmentManager'
And there is the store with number 'store-s30u63' managed by department manager named 'departmentManager-s30u63'
Given there is the supplier with name 'supplier-s30u63s1'
And there is the subCategory with name 'defaultSubCategory-s30u631' related to group named 'defaultGroup-s30u631' and category named 'defaultCategory-s30u631'
Given there is the product with 'name-30631' name, '30631' sku, '30631' barcode, 'unit' units, '' purchasePrice of group named 'defaultGroup-s30u631', category named 'defaultCategory-s30u631', subcategory named 'defaultSubCategory-s30u631'

Given the user opens order create page
And the user logs in using 'departmentManager-s30u63' userName and 'lighthouse' password

When the user inputs values in addition new product form on the order page
| elementName | value |
| name | name-30631 |
| quantity | 5 |

When the user clicks the add order product button

Then the user checks the order products list contains entry
| name | units | quantity | retailPrice | totalSum |
| name-30631 | шт. | 5 | 0,00 | 0,00 |

Scenario: Verify autocomplete product with price with no mark up is choosen

Meta:
@id_s30u63s10

Given there is the user with name 'departmentManager-s30u63', position 'departmentManager-s30u63', username 'departmentManager-s30u63', password 'lighthouse', role 'departmentManager'
And there is the store with number 'store-s30u63' managed by department manager named 'departmentManager-s30u63'
Given there is the supplier with name 'supplier-s30u63s1'
And there is the subCategory with name 'defaultSubCategory-s30u632' related to group named 'defaultGroup-s30u632' and category named 'defaultCategory-s30u632'
Given there is the product with 'name-30632' name, '30632' sku, '30632' barcode, 'unit' units, '100' purchasePrice of group named 'defaultGroup-s30u632', category named 'defaultCategory-s30u632', subcategory named 'defaultSubCategory-s30u632'

Given the user opens order create page
And the user logs in using 'departmentManager-s30u63' userName and 'lighthouse' password

When the user inputs values in addition new product form on the order page
| elementName | value |
| name | name-30632 |
| quantity | 5 |

When the user clicks the add order product button

Then the user checks the order products list contains entry
| name | units | quantity | retailPrice | totalSum |
| name-30632 | шт. | 5 | 100,00 | 500,00 |
