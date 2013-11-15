Meta:
@sprint 23
@us 50

Narrative:
As a user
I want to perform an action
So that I can achieve a business goal

GivenStories: precondition/aPreconditionToStoryUs50.story

Scenario: Primary Inventory Analysis

Meta:
@id s23u50s1
@description primary inventory analysis (all inventory items)
@smoke

Given the user runs the prepare fixture data cap command for inventory testing
And the user runs the recalculate_metrics cap command
Given the user navigates to the subCategory 'defaultSubCategory-s23u50', category 'defaultCategory-s23u50', group 'defaultGroup-s23u50' product list page
When the user logs in using 'departmentManager-s23u50' userName and 'lighthouse' password
And the user opens product balance tab
Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| Балык свиной в/с в/об Матера | 2800465 | 2800465 | 39,00 | 0,17 | 234,0 | 312,80 р. | 298,64 р. |
| Балык Ломберный с/к в/с ТД Рублевский | 2805223 | 2805223 | 32,00 | 0,37 | 87,3 | 678,40 р. | 682,59 р. |
| Ассорти Читтерио мясное нар.140г | 80469353 | 80469353 | 33,00 | 1,13 | 29,1 | 449,60 р. | 459,81 р. |

Scenario: Inventory item productName is a link and its clickable

Meta:
@id s23u50s2
@description the inventory item product name is clickable and opens the product card
@smoke

Given the user navigates to the subCategory 'defaultSubCategory-s23u50', category 'defaultCategory-s23u50', group 'defaultGroup-s23u50' product list page
When the user logs in using 'departmentManager-s23u50' userName and 'lighthouse' password
And the user opens product balance tab
And the user clicks on the inventory table item by 'sku-s23u50'
Then the user checks the elements values
| elementName | value |
| name | name-s23u50 |
| vendor | Тестовый производитель |
| vendorCountry | Тестовая страна |
| purchasePrice | 12,34 |
| barcode | barcode-s23u50 |
| sku | sku-s23u50 |
| info | |
| unit | штука |
| vat | 0 |
| retailMarkupRange | 0,00 - 10,00 |
| retailPriceRange | 12,34 - 13,57 |

Scenario: Inventory table has got the product with no price

Meta:
@id s23u50s3
@description inventory table has got the product with no price

Given the user navigates to the subCategory 'defaultSubCategory-s23u50-1', category 'defaultCategory-s23u50-1', group 'defaultGroup-s23u50-1' product list page
When the user logs in using 'departmentManager-s23u50' userName and 'lighthouse' password
And the user opens product balance tab
Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| name-s23u50-1 | sku-s23u50-1 | barcode-s23u50-1 | 0,00 | 0,00 | 0,0 | — | — |

Scenario: Inventory table has got the product with no sales (0)

Meta:
@id s23u50s4
@description inventory table has got the product with no sales (0)

Given the user navigates to the subCategory 'defaultSubCategory-s23u50-1', category 'defaultCategory-s23u50-1', group 'defaultGroup-s23u50-1' product list page
When the user logs in using 'departmentManager-s23u50' userName and 'lighthouse' password
And the user opens product balance tab
Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| name-s23u50-1 | sku-s23u50-1 | barcode-s23u50-1 | 0,00 | 0,00 | 0,0 | — | — |

Scenario: Inventory table has got the product with no inventory (0)

Meta:
@id s23u50s5
@description inventory table has got the product with no inventory (0)

Given the user navigates to the subCategory 'defaultSubCategory-s23u50-1', category 'defaultCategory-s23u50-1', group 'defaultGroup-s23u50-1' product list page
When the user logs in using 'departmentManager-s23u50' userName and 'lighthouse' password
And the user opens product balance tab
Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| name-s23u50-1 | sku-s23u50-1 | barcode-s23u50-1 | 0,00 | 0,00 | 0,0 | — | — |

Scenario: Inventory ratio dosnt evaluate for today transactions

Meta:
@id s23u50s6
@description inventory ratio dosnt evaluate for today transactions

Given the user runs the prepare fixture data cap command for negative inventory testing - '0' days
And the user runs the recalculate_metrics cap command
Given the user navigates to the subCategory 'defaultSubCategory-s23u50', category 'defaultCategory-s23u50', group 'defaultGroup-s23u50' product list page
When the user logs in using 'departmentManager-s23u50' userName and 'lighthouse' password
And the user opens product balance tab
Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| name-s23u50-2 | 280046544 | 280046544 | 90,00 | 0,00 | 0,0 | 120,00 р. | 120,00 р. |

Scenario: Inventory ratio dosnt evaluate for older than 30 days transactions

Meta:
@id s23u50s7
@description inventory ratio dosnt evaluate for older than 31 days transactions

Given the user runs the prepare fixture data cap command for negative inventory testing - '31' days
And the user runs the recalculate_metrics cap command
Given the user navigates to the subCategory 'defaultSubCategory-s23u50', category 'defaultCategory-s23u50', group 'defaultGroup-s23u50' product list page
When the user logs in using 'departmentManager-s23u50' userName and 'lighthouse' password
And the user opens product balance tab
Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| name-s23u50-2 | 280046544 | 280046544 | 80,00 | 0,00 | 0,0 | 120,00 р. | 120,00 р. |

Scenario: Mouse hover over inventory items

Meta:
@id s23u50s8
@description while hovering the inventory item there will be some details

Given the user navigates to the subCategory 'defaultSubCategory-s23u50-1', category 'defaultCategory-s23u50-1', group 'defaultGroup-s23u50-1' product list page
When the user logs in using 'departmentManager-s23u50' userName and 'lighthouse' password
And the user opens product balance tab
Then the user checks the balance list item by sku 'sku-s23u50-1' has items not visible
| items |
| Остаток, шт. |
| ССО, шт. |
| УСЗ, дн. |
| Последняя |
| Средняя |
Then the user checks the balance list item by sku 'sku-s23u50-1' has items become visible while hovering
