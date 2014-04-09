Meta:
@regression

Scenario: When editing order product quantity if pressing escape key button causes dynamic price sum calculation to null (0,00)

Meta:
@regression
@order

GivenStories: precondition/sprint-31/us-67/aPreconditionToStoryUs67.story

Given there is the product with 'name-order-regression' name, 'sku-order-regression' sku, 'barCode-order-regression' barcode

Given the user opens order create page
And the user logs in using 'departmentManager-s30u67' userName and 'lighthouse' password

When the user inputs values on order page
| elementName | value |
| order product autocomplete | name-order-regression |
And the user presses 'ESCAPE' key button

Then the user checks the order product found by name 'name-order-regression' has sum equals to '123,00'

Scenario: When edition order product quantity with values with comma causes dynamic price sum recalculation to null (0,00)

Meta:
@regression
@order

GivenStories: precondition/sprint-31/us-67/aPreconditionToStoryUs67.story

Given there is the product with 'name-order-regression' name, 'sku-order-regression' sku, 'barCode-order-regression' barcode

Given the user opens order create page
And the user logs in using 'departmentManager-s30u67' userName and 'lighthouse' password

When the user inputs values on order page
| elementName | value |
| order product autocomplete | name-order-regression |
And the user inputs quantity '10,456' on the order product with name 'name-order-regression'

Then the user checks the order product found by name 'name-order-regression' has sum equals to '1 286,09'

Scenario: Downdload agreement button is not visible on order edit page if supplier doesnt have an agreement

Meta:
@regression
@order

GivenStories: precondition/sprint-31/us-67/aPreconditionToStoryUs67.story

Given there is the supplier with name 'supplier-s30u67s1'
And there is the order in the store by 'departmentManager-s30u67'

Given the user opens last created order page
And the user logs in using 'departmentManager-s30u67' userName and 'lighthouse' password

When the user inputs values on order page
| elementName | value |
| supplier | supplier-s30u67s1 |

Then the user checks the download agreement button should be not visible on the order page

Scenario: Downdload agreement button is not visible on order create page if supplier doesnt have an agreement

Meta:
@regression
@order

GivenStories: precondition/sprint-31/us-67/aPreconditionToStoryUs67.story

Given there is the supplier with name 'supplier-s30u67s1'

Given the user opens order create page
And the user logs in using 'departmentManager-s30u67' userName and 'lighthouse' password

When the user inputs values on order page
| elementName | value |
| supplier | supplier-s30u67s1 |

Then the user checks the download agreement button should be not visible on the order page

Scenario: Downdload agreement button is visible on order create page if supplier has an agreement

Meta:
@regression
@order

GivenStories: precondition/sprint-31/us-67/aPreconditionToStoryUs67.story

Given the user opens supplier create page
And the user logs in as 'commercialManager'

When the user inputs values on supplier page
| elementName | value |
| supplierName | supplier-s30u67s1-with-file |

When the user uploads file with name 'uploadFile123.docx' and with size of '300' kilobytes

Then the user waits for upload complete
And the user sees success message 'Файл успешно загружен'

Then the user asserts uploaded file name is expected

When the user clicks on the supplier create button

When the user logs out

Given the user opens order create page
And the user logs in using 'departmentManager-s30u67' userName and 'lighthouse' password

When the user inputs values on order page
| elementName | value |
| supplier | supplier-s30u67s1-with-file |

Then the user checks the download agreement button should be visible on the order page

Scenario: Downdload agreement button is visible on order edit page if supplier has an agreement

Meta:
@regression
@order

GivenStories: precondition/sprint-31/us-67/aPreconditionToStoryUs67.story

Given there is the order in the store by 'departmentManager-s30u67'

Given the user opens supplier create page
And the user logs in as 'commercialManager'

When the user inputs values on supplier page
| elementName | value |
| supplierName | supplier-s30u67s1-with-file-2 |

When the user uploads file with name 'uploadFile123.docx' and with size of '300' kilobytes

Then the user waits for upload complete
And the user sees success message 'Файл успешно загружен'

Then the user asserts uploaded file name is expected

When the user clicks on the supplier create button

When the user logs out

Given the user opens last created order page
And the user logs in using 'departmentManager-s30u67' userName and 'lighthouse' password

When the user inputs values on order page
| elementName | value |
| supplier | supplier-s30u67s1-with-file-2 |

Then the user checks the download agreement button should be visible on the order page

Scenario: Cannot create product with empty price if subcategory has mark up set

Meta:
@regression
@product

Given there is the subCategory with name 'RegressionSubCategory' related to group named 'RegressionGroup' and category named 'RegressionCategory'
And the user sets subCategory 'RegressionSubCategory' mark up with max '30' and min '0' values

Given the user navigates to the subCategory 'RegressionSubCategory', category 'RegressionCategory', group 'RegressionGroup' product list page
And the user logs in as 'commercialManager'

When the user clicks on start edition link and starts the edition
And the user creates new product from product list page

When the user inputs values in element fields
| elementName | value |
| sku | regressionSubCategoryProductSku |
| name | regressionSubCategoryProductName |
| unit | unit |
| vat | 0 |
And the user clicks the create button

Then the user checks the product with 'regressionSubCategoryProductSku' sku is present