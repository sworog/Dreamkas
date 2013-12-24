Meta:
@sprint 26
@us 40.4

Narrative:
As a user
I want to perform an action
So that I can achieve a business goal

Scenario: Sale purchase duplication import

Meta:
@id s26u40.4.s1
@description sale purchase duplication import
@smoke

GivenStories: precondition/sprint-26/us-40_4/aPreconditionToScenarioS1.story

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

Meta:
@id s26u40.4.s2
@description return purchase duplication import
@smoke

GivenStories: precondition/sprint-26/us-40_4/aPreconditionToScenarioS2.story

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
When the user opens product balance tab
Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| name-26404 | 26404 | 26404 | 1,0 | 0,0 | 0,0 | 124,50 р. | — |

Given the user navigates to the product with sku '26404'
When the user clicks the product local navigation returns link
Then the user checks the product return list contains entry
| date | quantity | price | totalPrice |
| 03.12.2013 | 1,0 | 150,00 | 150,00 |



