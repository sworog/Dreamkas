Meta:
@sprint_22
@us_50.1

Narrative:
As a заведующтй отделом
I want to просматривать остатки товаров подкатегории
In order to понять когда и сколько едениц товара мне требуется заказать у поставщика

Scenario: Subcategory product balance checking

Meta:
@id_s22u50.1s1
@description subcategory page have balance tab, balance table have all required data
@smoke

GivenStories: precondition/sprint-22/us-50_1/aPreconditionToStoryUs50_1.story,
              precondition/sprint-22/us-50_1/aPreconditionToScenarioS1.story

Given the user navigates to the subCategory 'SCPBC-defaultSubCategory', category 'SCPBC-defaultCategory', group 'SCPBC-defaultGroup' product list page
When the user logs in using 'departmentManager-SCPBC' userName and 'lighthouse' password
And the user opens product balance tab
Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| SCPBC-name | SCPBC-sku | SCPBC-barcode | 0,0 | 0,0 | 0,0 | 12,34 р. | — |

Scenario: Subcategory product balance not required fields checking

Meta:
@id_s22u50.1s2
@description balance data table render not required produt fields correctly

GivenStories: precondition/sprint-22/us-50_1/aPreconditionToStoryUs50_1.story,
              precondition/sprint-22/us-50_1/aPreconditionToScenarioS2.story

Given the user navigates to the subCategory 'SCPBC-defaultSubCategory', category 'SCPBC-defaultCategory', group 'SCPBC-defaultGroup' product list page
When the user logs in using 'departmentManager-SCPBC' userName and 'lighthouse' password
And the user opens product balance tab
Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| SCPBC-name-1 | SCPBC-sku-1 | | 0,0 | 0,0 | 0,0 | — | — |

Scenario: Subcategory product balance after writeOff

Meta:
@id_s22u50.1s3
@description balance deacrese after writeOff with product is created
@smoke

GivenStories: precondition/sprint-22/us-50_1/aPreconditionToStoryUs50_1.story,
              precondition/sprint-22/us-50_1/aPreconditionToScenarioS3.story

Given the user navigates to the subCategory 'SCPBC-defaultSubCategory', category 'SCPBC-defaultCategory', group 'SCPBC-defaultGroup' product list page
When the user logs in using 'departmentManager-SCPBC' userName and 'lighthouse' password
And the user opens product balance tab
Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| SCPBC-name-2 | SCPBC-sku-2 | SCPBC-barcode-2 | 1,0 | 0,0 | 0,0 | 100,00 р. | — |

Scenario: Subcategory product balance after invoice

Meta:
@id_s22u50.1s4
@description balance increase after invoice with product is created
@smoke

GivenStories: precondition/sprint-22/us-50_1/aPreconditionToStoryUs50_1.story,
              precondition/sprint-22/us-50_1/aPreconditionToScenarioS4.story

Given the user navigates to the subCategory 'SCPBC-defaultSubCategory', category 'SCPBC-defaultCategory', group 'SCPBC-defaultGroup' product list page
When the user logs in using 'departmentManager-SCPBC' userName and 'lighthouse' password
And the user opens product balance tab
Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| SCPBC-name-3 | SCPBC-sku-3 | SCPBC-barcode-3 | -1,0 | 0,0 | 0,0 | 12,34 р. | — |

Scenario: Subcategory product balance with average price checking

Meta:
@id_s22u50.1s5
@description balance table average price column contains product correct data

GivenStories: precondition/sprint-22/us-50_1/aPreconditionToStoryUs50_1.story,
              precondition/sprint-22/us-50_1/aPreconditionToScenarioS5.story

Given the user navigates to the subCategory 'SCPBC-defaultSubCategory', category 'SCPBC-defaultCategory', group 'SCPBC-defaultGroup' product list page
When the user logs in using 'departmentManager-SCPBC' userName and 'lighthouse' password
And the user opens product balance tab
Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| SCPBC-name-4 | SCPBC-sku-4 | SCPBC-barcode-4 | 3,0 | 0,0 | 0,0 | 123,00 р. | 130,33 р. |

Scenario: No product balance tab for commercialManager

Meta:
@id_s22u50.1s6
@description no product balance tab availabile for commercial manager

GivenStories: precondition/sprint-22/us-50_1/aPreconditionToStoryUs50_1.story

Given the user navigates to the subCategory 'SCPBC-defaultSubCategory', category 'SCPBC-defaultCategory', group 'SCPBC-defaultGroup' product list page
And the user logs in as 'commercialManager'
Then the user checks product balance tab is not visible













