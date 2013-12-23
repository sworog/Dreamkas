Meta:
@sprint 26
@us 40.4

Narrative:
As a user
I want to perform an action
So that I can achieve a business goal

Scenario: Sale purchase duplication import

Given the user runs the symfony:env:init command

Given there is the user with name 'storeManager-s26u404', position 'storeManager-s26u404', username 'storeManager-s26u404', password 'lighthouse', role 'storeManager'

Given there is the store with number '26404' managed by 'storeManager-s26u404'
And there is the subCategory with name 'defaultSubCategory-s26u404' related to group named 'defaultGroup-s26u404' and category named 'defaultCategory-s26u404'
And the user sets subCategory 'defaultSubCategory-s26u404' mark up with max '10' and min '0' values

Given there is the product with 'name-26404' name, '26404' sku, '26404' barcode, 'unit' units, '124,5' purchasePrice of group named 'defaultGroup-s26u404', category named 'defaultCategory-s26u404', subcategory named 'defaultSubCategory-s26u404'

Given the user prepares sale purchase for us 40.4 story

Given the user navigates to the subCategory 'defaultSubCategory-s26u404', category 'defaultCategory-s26u404', group 'defaultGroup-s26u404' product list page
When the user logs in using 'storeManager-s26u404' userName and 'lighthouse' password
And the user opens product balance tab
Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| name-26404 | 26404 | 26404 | -5,0 | 0,0 | 0,0 | 124,50 р. | — |

Given the user prepares sale purchase return for us 40.4 story

When the user refreshes the current page
Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| name-26404 | 26404 | 26404 | -1,0 | 0,0 | 0,0 | 124,50 р. | — |

Scenario: Return purchase duplication import

Given the user runs the symfony:env:init command

Given there is the user with name 'departmentManager-s26u404', position 'departmentManager-s26u404', username 'departmentManager-s26u404', password 'lighthouse', role 'departmentManager'

Given there is the store with number '26404' managed by department manager named 'departmentManager-s26u404'
And there is the subCategory with name 'defaultSubCategory-s26u404' related to group named 'defaultGroup-s26u404' and category named 'defaultCategory-s26u404'
And the user sets subCategory 'defaultSubCategory-s26u404' mark up with max '10' and min '0' values

Given there is the product with 'name-26404' name, '26404' sku, '26404' barcode, 'unit' units, '124,5' purchasePrice of group named 'defaultGroup-s26u404', category named 'defaultCategory-s26u404', subcategory named 'defaultSubCategory-s26u404'

Given the user prepares sale return 1 for us 40.4 story

Given the user navigates to the subCategory 'defaultSubCategory-s26u404', category 'defaultCategory-s26u404', group 'defaultGroup-s26u404' product list page
When the user logs in using 'departmentManager-s26u404' userName and 'lighthouse' password

And the user opens product balance tab
Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| name-26404 | 26404 | 26404 | 5,0 | 0,0 | 0,0 | 124,50 р. | — |

Given the user navigates to the product with sku '26404'
When the user clicks the product local navigation returns link
Then the user checks the product return list contains entry
| date | quantity | price | totalPrice |
| 03.12.2013 | 5,0 | 150,00 | 750,00 |

Given the user prepares sale return 2 for us 40.4 story

Given the user navigates to the subCategory 'defaultSubCategory-s26u404', category 'defaultCategory-s26u404', group 'defaultGroup-s26u404' product list page

Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| name-26404 | 26404 | 26404 | 1,0 | 0,0 | 0,0 | 124,50 р. | — |

Given the user navigates to the product with sku '26404'
When the user clicks the product local navigation returns link
Then the user checks the product return list contains entry
| date | quantity | price | totalPrice |
| 03.12.2013 | 1,0 | 150,00 | 150,00 |



