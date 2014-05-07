Meta:
@sprint_22
@us_40.3

Narrative:
As a user
I want to perform an action
So that I can achieve a business goal

Scenario: Returns checking

Meta:
@id_s22u40.3s1
@description the balance of two products increase after product return is processed by system (subCategory product balance page, product returns page).
@smoke

GivenStories: precondition/sprint-22/us-40.3/aPreconditionToScenarioS1.story

Given the user navigates to the subCategory 'ProductsExportSubCategory', category 'ProductsExportCategory', group 'ProductsExportGroup' product list page
When the user logs in using 'ReturnDepartmentManager' userName and 'lighthouse' password
And the user opens product balance tab
Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| Профитроли Коппенрат&Вайс Бэйлис 280г | #sku:Профитроли Коппенрат&Вайс Бэйлис 280г | 1008577061437 | 0,0 | 0,0 | 0,0 | 20,80 р. | — |
When the user logs out
Given the user navigates to the subCategory 'ProductsExportSubCategory', category 'ProductsExportCategory', group 'ProductsExportGroup' product list page
When the user logs in using 'ReturnDepartmentManager2' userName and 'lighthouse' password
And the user opens product balance tab
Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| Профитроли Коппенрат&Вайс Бэйлис 280г | #sku:Профитроли Коппенрат&Вайс Бэйлис 280г | 1008577061437 | 0,0 | 0,0 | 0,0 | 20,80 р. | — |
When the user logs out
Given the user prepares import return data for us 40.3 story for product with 'Профитроли Коппенрат&Вайс Бэйлис 280г' name
Given the user navigates to the subCategory 'ProductsExportSubCategory', category 'ProductsExportCategory', group 'ProductsExportGroup' product list page
When the user logs in using 'ReturnDepartmentManager' userName and 'lighthouse' password
When the user opens product balance tab
Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| Профитроли Коппенрат&Вайс Бэйлис 280г | #sku:Профитроли Коппенрат&Вайс Бэйлис 280г | 1008577061437 | 2,0 | 0,0 | 0,0 | 20,80 р. | — |
Given the user navigates to the product with name 'Профитроли Коппенрат&Вайс Бэйлис 280г'
When the user clicks the product local navigation returns link
Then the user checks the product return list contains entry
| date | quantity | price | totalPrice |
| 03.10.2013 | 2,0 | 26,99 | 53,98 |
When the user logs out
Given the user navigates to the subCategory 'ProductsExportSubCategory', category 'ProductsExportCategory', group 'ProductsExportGroup' product list page
When the user logs in using 'ReturnDepartmentManager2' userName and 'lighthouse' password
When the user opens product balance tab
Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| Профитроли Коппенрат&Вайс Бэйлис 280г | #sku:Профитроли Коппенрат&Вайс Бэйлис 280г | 1008577061437 | 3,0 | 0,0 | 0,0 | 20,80 р. | — |
Given the user navigates to the product with name 'Профитроли Коппенрат&Вайс Бэйлис 280г'
When the user clicks the product local navigation returns link
Then the user checks the product return list contains entry
| date | quantity | price | totalPrice |
| 03.10.2013 | 3,0 | 25,50 | 76,50 |

Scenario: No returns tab for storeManager

Meta:
@id_s22u40.3s2
@description no product balance tab availabile for store manager

GivenStories: precondition/sprint-22/us-40.3/aPreconditionToScenarioS23.story

Given the user navigates to the product with name 'SCPBC-name-1'
When the user logs in using 'NRTF-1' userName and 'lighthouse' password
Then the user checks the local navigation return link is not visible

Scenario: No returns tab for commercialManager

Meta:
@id_s22u40.3s3
@description no product balance tab availabile for commercial manager

GivenStories: precondition/sprint-22/us-40.3/aPreconditionToScenarioS23.story

Given the user navigates to the product with name 'SCPBC-name-1'
And the user logs in as 'commercialManager'
Then the user checks the local navigation return link is not visible