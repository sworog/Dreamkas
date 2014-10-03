Meta:
@sprint_38
@us_101

Narrative:
Как владелец,
Я хочу создавать, редактировать и удалять товары,
Чтобы отслеживать их движение и продавать, используя возможности LH

Scenario: Product list sorting by name

Meta:
@id_s38u101s46

GivenStories: precondition/sprint-38/us-101/aPreconditionToUserCreation.story

Given the user with email 's28u101@lighthouse.pro' creates group with name 'groups30u101sort'
And the user navigates to the group with name 'groups30u101sort'
And the user with email 's28u101@lighthouse.pro' creates the product with name 's38u101sortName1', units 'шт.', barcode 'sorBar1', vat '0', purchasePrice '100', sellingPrice '1' in the group with name 'groups30u101sort'
And the user with email 's28u101@lighthouse.pro' creates the product with name 's38u101sortName2', units 'шт.', barcode 'sorBar2', vat '0', purchasePrice '100', sellingPrice '2' in the group with name 'groups30u101sort'
And the user with email 's28u101@lighthouse.pro' creates the product with name 's38u101sortName3', units 'шт.', barcode 'sorBar3', vat '0', purchasePrice '100', sellingPrice '3' in the group with name 'groups30u101sort'
And the user with email 's28u101@lighthouse.pro' creates the product with name 's38u101sortName4', units 'шт.', barcode 'sorBar4', vat '0', purchasePrice '100', sellingPrice '4' in the group with name 'groups30u101sort'
And the user with email 's28u101@lighthouse.pro' creates the product with name 's38u101sortName5', units 'шт.', barcode 'sorBar5', vat '0', purchasePrice '100', sellingPrice '5' in the group with name 'groups30u101sort'
And the user logs in using 's28u101@lighthouse.pro' userName and 'lighthouse' password

Then the user asserts the product list contain exact products with values
| name | sellingPrice | barcode |
| s38u101sortName1 | 1,00 | sorBar1|
| s38u101sortName2 | 2,00 | sorBar2|
| s38u101sortName3 | 3,00 | sorBar3|
| s38u101sortName4 | 4,00 | sorBar4|
| s38u101sortName5 | 5,00 | sorBar5|

When the user sorts the product list by name

Then the user asserts the product list contain exact products with values
| name | sellingPrice | barcode |
| s38u101sortName5 | 5,00 | sorBar5|
| s38u101sortName4 | 4,00 | sorBar4|
| s38u101sortName3 | 3,00 | sorBar3|
| s38u101sortName2 | 2,00 | sorBar2|
| s38u101sortName1 | 1,00 | sorBar1|

Scenario: Product list sorting by sellingPrice

Meta:
@id_s38u101s47

GivenStories: precondition/sprint-38/us-101/aPreconditionToUserCreation.story

Given the user with email 's28u101@lighthouse.pro' creates group with name 'groups30u101sort'
And the user navigates to the group with name 'groups30u101sort'
And the user with email 's28u101@lighthouse.pro' creates the product with name 's38u101sortName1', units 'шт.', barcode 'sorBar1', vat '0', purchasePrice '100', sellingPrice '1' in the group with name 'groups30u101sort'
And the user with email 's28u101@lighthouse.pro' creates the product with name 's38u101sortName2', units 'шт.', barcode 'sorBar2', vat '0', purchasePrice '100', sellingPrice '2' in the group with name 'groups30u101sort'
And the user with email 's28u101@lighthouse.pro' creates the product with name 's38u101sortName3', units 'шт.', barcode 'sorBar3', vat '0', purchasePrice '100', sellingPrice '3' in the group with name 'groups30u101sort'
And the user with email 's28u101@lighthouse.pro' creates the product with name 's38u101sortName4', units 'шт.', barcode 'sorBar4', vat '0', purchasePrice '100', sellingPrice '4' in the group with name 'groups30u101sort'
And the user with email 's28u101@lighthouse.pro' creates the product with name 's38u101sortName5', units 'шт.', barcode 'sorBar5', vat '0', purchasePrice '100', sellingPrice '5' in the group with name 'groups30u101sort'
And the user logs in using 's28u101@lighthouse.pro' userName and 'lighthouse' password

Then the user asserts the product list contain exact products with values
| name | sellingPrice | barcode |
| s38u101sortName1 | 1,00 | sorBar1|
| s38u101sortName2 | 2,00 | sorBar2|
| s38u101sortName3 | 3,00 | sorBar3|
| s38u101sortName4 | 4,00 | sorBar4|
| s38u101sortName5 | 5,00 | sorBar5|

When the user sorts the product list by sellingPrice

Then the user asserts the product list contain exact products with values
| name | sellingPrice | barcode |
| s38u101sortName5 | 5,00 | sorBar5|
| s38u101sortName4 | 4,00 | sorBar4|
| s38u101sortName3 | 3,00 | sorBar3|
| s38u101sortName2 | 2,00 | sorBar2|
| s38u101sortName1 | 1,00 | sorBar1|

Scenario: Product list sorting by barCode

Meta:
@id_s38u101s48

GivenStories: precondition/sprint-38/us-101/aPreconditionToUserCreation.story

Given the user with email 's28u101@lighthouse.pro' creates group with name 'groups30u101sort'
And the user navigates to the group with name 'groups30u101sort'
And the user with email 's28u101@lighthouse.pro' creates the product with name 's38u101sortName1', units 'шт.', barcode 'sorBar1', vat '0', purchasePrice '100', sellingPrice '1' in the group with name 'groups30u101sort'
And the user with email 's28u101@lighthouse.pro' creates the product with name 's38u101sortName2', units 'шт.', barcode 'sorBar2', vat '0', purchasePrice '100', sellingPrice '2' in the group with name 'groups30u101sort'
And the user with email 's28u101@lighthouse.pro' creates the product with name 's38u101sortName3', units 'шт.', barcode 'sorBar3', vat '0', purchasePrice '100', sellingPrice '3' in the group with name 'groups30u101sort'
And the user with email 's28u101@lighthouse.pro' creates the product with name 's38u101sortName4', units 'шт.', barcode 'sorBar4', vat '0', purchasePrice '100', sellingPrice '4' in the group with name 'groups30u101sort'
And the user with email 's28u101@lighthouse.pro' creates the product with name 's38u101sortName5', units 'шт.', barcode 'sorBar5', vat '0', purchasePrice '100', sellingPrice '5' in the group with name 'groups30u101sort'
And the user logs in using 's28u101@lighthouse.pro' userName and 'lighthouse' password

Then the user asserts the product list contain exact products with values
| name | sellingPrice | barcode |
| s38u101sortName1 | 1,00 | sorBar1|
| s38u101sortName2 | 2,00 | sorBar2|
| s38u101sortName3 | 3,00 | sorBar3|
| s38u101sortName4 | 4,00 | sorBar4|
| s38u101sortName5 | 5,00 | sorBar5|

When the user sorts the product list by barcode

Then the user asserts the product list contain exact products with values
| name | sellingPrice | barcode |
| s38u101sortName5 | 5,00 | sorBar5|
| s38u101sortName4 | 4,00 | sorBar4|
| s38u101sortName3 | 3,00 | sorBar3|
| s38u101sortName2 | 2,00 | sorBar2|
| s38u101sortName1 | 1,00 | sorBar1|
