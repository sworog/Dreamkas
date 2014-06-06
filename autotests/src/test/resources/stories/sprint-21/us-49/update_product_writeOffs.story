Meta:
@sprint_21
@us_49

Narrative:
As a user
I want to perform an action
So that I can achieve a business goal

Scenario: No product writeOffs

Given there is the user with name 'departmentManager-UIBS-FF', position 'departmentManager-UIBS-FF', username 'departmentManager-UIBS-FF', password 'lighthouse', role 'departmentManager'
And there is the store with number 'UIBS-FF' managed by department manager named 'departmentManager-UIBS-FF'
Given there is the subCategory with name 'ProductsUpdateWriteOffSubCategory' related to group named 'ProductsUpdateWriteOffGroup' and category named 'ProductsUpdateWriteOffCategory'
And there is the product with '7300330094021' name, '7300330094025' barcode, 'unit' type, '97,60' purchasePrice of group named 'ProductsUpdateWriteOffGroup', category named 'ProductsUpdateWriteOffCategory', subcategory named 'ProductsUpdateWriteOffSubCategory'
And the user navigates to the product with name '7300330094021'
When the user logs in using 'departmentManager-UIBS-FF' userName and 'lighthouse' password
And the user clicks the product local navigation writeoffs link
Then the user checks page contains text 'Списаний товара еще не было'

Scenario: Product writeOff list found

Meta:
@smoke

Given there is the user with name 'departmentManager-UIBS-FF', position 'departmentManager-UIBS-FF', username 'departmentManager-UIBS-FF', password 'lighthouse', role 'departmentManager'
And there is the store with number 'UIBS-FF' managed by department manager named 'departmentManager-UIBS-FF'
Given there is the subCategory with name 'ProductsUpdateWriteOffSubCategory' related to group named 'ProductsUpdateWriteOffGroup' and category named 'ProductsUpdateWriteOffCategory'
And there is the product with '7300330094026' name, '7300330094026' barcode, 'unit' type, '97,60' purchasePrice of group named 'ProductsUpdateWriteOffGroup', category named 'ProductsUpdateWriteOffCategory', subcategory named 'ProductsUpdateWriteOffSubCategory'
And there is the writeOff in the store with number 'UIBS-FF' ruled by department manager with name 'departmentManager-UIBS-FF' with values
| elementName | elementValue |
| number | WOUIBS-FF-01 |
| date | 02.04.2013 |
And the user adds the product to the write off with number 'WOUIBS-FF-01' with name '7300330094026', quantity '1', price '12,34', cause 'Плохо продавался' in the store ruled by 'departmentManager-UIBS-FF'
And the user navigates to the product with name '7300330094026'
When the user logs in using 'departmentManager-UIBS-FF' userName and 'lighthouse' password
And the user clicks the product local navigation writeoffs link
Then the user checks the product writeOff list contains entry
| createdDateFormatted | quantity | priceFormatted | totalPriceFormatted |
| 02.04.2013 | 1,0 | 12,34 | 12,34 |

Scenario: Two products from one writeOffs

Meta:
@smoke

Given there is the user with name 'departmentManager-UIBS-FF', position 'departmentManager-UIBS-FF', username 'departmentManager-UIBS-FF', password 'lighthouse', role 'departmentManager'
And there is the store with number 'UIBS-FF' managed by department manager named 'departmentManager-UIBS-FF'
Given there is the subCategory with name 'ProductsUpdateWriteOffSubCategory' related to group named 'ProductsUpdateWriteOffGroup' and category named 'ProductsUpdateWriteOffCategory'
And there is the product with '7300330094027' name, '7300330094027' barcode, 'unit' type, '97,60' purchasePrice of group named 'ProductsUpdateWriteOffGroup', category named 'ProductsUpdateWriteOffCategory', subcategory named 'ProductsUpdateWriteOffSubCategory'
And there is the writeOff in the store with number 'UIBS-FF' ruled by department manager with name 'departmentManager-UIBS-FF' with values
| elementName | elementValue |
| number | WOUIBS-FF-02 |
| date | 02.04.2013 |
And the user adds the product to the write off with number 'WOUIBS-FF-02' with name '7300330094027', quantity '10', price '1', cause 'Плохо продавался' in the store ruled by 'departmentManager-UIBS-FF'
And the user adds the product to the write off with number 'WOUIBS-FF-02' with name '7300330094027', quantity '25', price '1', cause 'Плохо продавался' in the store ruled by 'departmentManager-UIBS-FF'
And the user navigates to the product with name '7300330094027'
When the user logs in using 'departmentManager-UIBS-FF' userName and 'lighthouse' password
And the user clicks the product local navigation writeoffs link
Then the user checks the product writeOff list contains entry
| createdDateFormatted | quantity | priceFormatted | totalPriceFormatted |
| 02.04.2013 | 10,0 | 1,00 | 10,00 |
| 02.04.2013 | 25,0 | 1,00 | 25,00 |

Scenario: Two products from two writeOffs

Given there is the user with name 'departmentManager-UIBS-FF', position 'departmentManager-UIBS-FF', username 'departmentManager-UIBS-FF', password 'lighthouse', role 'departmentManager'
And there is the store with number 'UIBS-FF' managed by department manager named 'departmentManager-UIBS-FF'
Given there is the subCategory with name 'ProductsUpdateWriteOffSubCategory' related to group named 'ProductsUpdateWriteOffGroup' and category named 'ProductsUpdateWriteOffCategory'
And there is the product with '7300330094028' name, '7300330094028' barcode, 'unit' type, '97,60' purchasePrice of group named 'ProductsUpdateWriteOffGroup', category named 'ProductsUpdateWriteOffCategory', subcategory named 'ProductsUpdateWriteOffSubCategory'
And there is the writeOff in the store with number 'UIBS-FF' ruled by department manager with name 'departmentManager-UIBS-FF' with values
| elementName | elementValue |
| number | WOUIBS-FF-03 |
| date | 05.04.2013 |
And the user adds the product to the write off with number 'WOUIBS-FF-03' with name '7300330094028', quantity '3', price '1', cause 'Плохо продавался' in the store ruled by 'departmentManager-UIBS-FF'
And there is the writeOff in the store with number 'UIBS-FF' ruled by department manager with name 'departmentManager-UIBS-FF' with values
| elementName | elementValue |
| number | WOUIBS-FF-04 |
| date | 02.04.2013 |
And the user adds the product to the write off with number 'WOUIBS-FF-04' with name '7300330094028', quantity '2', price '1', cause 'Плохо продавался' in the store ruled by 'departmentManager-UIBS-FF'
And the user navigates to the product with name '7300330094028'
When the user logs in using 'departmentManager-UIBS-FF' userName and 'lighthouse' password
And the user clicks the product local navigation writeoffs link
Then the user checks the product writeOff list contains entry
| createdDateFormatted | quantity | priceFormatted | totalPriceFormatted |
| 05.04.2013 | 3,0 | 1,00 | 3,00 |
| 02.04.2013 | 2,0 | 1,00 | 2,00 |

Scenario: No the product local navigation writeoffs link for commercialManager

Given there is the user with name 'departmentManager-UIBS-FF', position 'departmentManager-UIBS-FF', username 'departmentManager-UIBS-FF', password 'lighthouse', role 'departmentManager'
And there is the store with number 'UIBS-FF' managed by department manager named 'departmentManager-UIBS-FF'
Given there is the subCategory with name 'ProductsUpdateWriteOffSubCategory' related to group named 'ProductsUpdateWriteOffGroup' and category named 'ProductsUpdateWriteOffCategory'
And there is the product with '7300330094028' name, '7300330094029' barcode, 'unit' type, '97,60' purchasePrice of group named 'ProductsUpdateWriteOffGroup', category named 'ProductsUpdateWriteOffCategory', subcategory named 'ProductsUpdateWriteOffSubCategory'
And the user navigates to the product with name '7300330094028'
Given the user logs in as 'commercialManager'
Then the user checks the product local navigation writeoffs link is not present

Scenario: 403 for product writeOffs list for commercialManager

Given there is the user with name 'departmentManager-UIBS-FF', position 'departmentManager-UIBS-FF', username 'departmentManager-UIBS-FF', password 'lighthouse', role 'departmentManager'
And there is the store with number 'UIBS-FF' managed by department manager named 'departmentManager-UIBS-FF'
Given there is the subCategory with name 'ProductsUpdateWriteOffSubCategory' related to group named 'ProductsUpdateWriteOffGroup' and category named 'ProductsUpdateWriteOffCategory'
And there is the product with '7300330094021' name, '7300330094030' barcode, 'unit' type, '97,60' purchasePrice of group named 'ProductsUpdateWriteOffGroup', category named 'ProductsUpdateWriteOffCategory', subcategory named 'ProductsUpdateWriteOffSubCategory'
And the user navigates to the product with name '7300330094021'
When the user logs in using 'departmentManager-UIBS-FF' userName and 'lighthouse' password
And the user clicks the product local navigation writeoffs link
Given the user stores the current url
When the user logs out
Given the user navigates to the stored url
And the user logs in as 'commercialManager'
Then the user sees the 403 error

Scenario: 403 for product writeOffs list for storeManager

Given there is the user with name 'departmentManager-DICK-123', position 'departmentManager-DICK-123', username 'departmentManager-DICK-123', password 'lighthouse', role 'departmentManager'
And there is the user with name 'storemanager-DICK-123', position 'storemanager-DICK-123', username 'storemanager-DICK-123', password 'lighthouse', role 'storeManager'
And there is the store with number 'DICK-123' managed by department manager named 'departmentManager-DICK-123'
And there is the store with number 'DICK-123' managed by 'storemanager-DICK-123'
Given there is the subCategory with name 'ProductsUpdateWriteOffSubCategory' related to group named 'ProductsUpdateWriteOffGroup' and category named 'ProductsUpdateWriteOffCategory'
And there is the product with '7300330094021' name, '7300330094031' barcode, 'unit' type, '97,60' purchasePrice of group named 'ProductsUpdateWriteOffGroup', category named 'ProductsUpdateWriteOffCategory', subcategory named 'ProductsUpdateWriteOffSubCategory'
And the user navigates to the product with name '7300330094021'
When the user logs in using 'departmentManager-DICK-123' userName and 'lighthouse' password
And the user clicks the product local navigation writeoffs link
Given the user stores the current url
When the user logs out
Given the user navigates to the stored url
When the user logs in using 'storemanager-DICK-123' userName and 'lighthouse' password
Then the user sees the 403 error

Scenario: No the product local navigation writeoffs link for storeManager

Given there is the user with name 'storemanager-DICK-123', position 'storemanager-DICK-123', username 'storemanager-DICK-123', password 'lighthouse', role 'storeManager'
And there is the store with number 'DICK-123' managed by 'storemanager-DICK-123'
Given there is the subCategory with name 'ProductsUpdateWriteOffSubCategory' related to group named 'ProductsUpdateWriteOffGroup' and category named 'ProductsUpdateWriteOffCategory'
And there is the product with '7300330094021' name, '7300330094032' barcode, 'unit' type, '97,60' purchasePrice of group named 'ProductsUpdateWriteOffGroup', category named 'ProductsUpdateWriteOffCategory', subcategory named 'ProductsUpdateWriteOffSubCategory'
And the user navigates to the product with name '7300330094021'
When the user logs in using 'storemanager-DICK-123' userName and 'lighthouse' password
Then the user checks the product local navigation writeoffs link is not present

Scenario: Product writeoff list item click

Given there is the user with name 'departmentManager-UIBS-FF', position 'departmentManager-UIBS-FF', username 'departmentManager-UIBS-FF', password 'lighthouse', role 'departmentManager'
And there is the store with number 'UIBS-FF' managed by department manager named 'departmentManager-UIBS-FF'
Given there is the subCategory with name 'ProductsUpdateWriteOffSubCategory' related to group named 'ProductsUpdateWriteOffGroup' and category named 'ProductsUpdateWriteOffCategory'
And there is the product with '7300330094020' name, '7300330094033' barcode, 'unit' type, '97,60' purchasePrice of group named 'ProductsUpdateWriteOffGroup', category named 'ProductsUpdateWriteOffCategory', subcategory named 'ProductsUpdateWriteOffSubCategory'
And there is the writeOff in the store with number 'UIBS-FF' ruled by department manager with name 'departmentManager-UIBS-FF' with values
| elementName | elementValue |
| number | WOUIBS-FF-05 |
| date | 02.04.2013 |
And the user adds the product to the write off with number 'WOUIBS-FF-05' with name '7300330094020', quantity '1', price '12,34', cause 'Плохо продавался' in the store ruled by 'departmentManager-UIBS-FF'
And the user navigates to the product with name '7300330094020'
When the user logs in using 'departmentManager-UIBS-FF' userName and 'lighthouse' password
And the user clicks the product local navigation writeoffs link
Then the user checks the product writeOff list contains entry
| createdDateFormatted | quantity | priceFormatted | totalPriceFormatted |
| 02.04.2013 | 1,0 | 12,34 | 12,34 |
When the user clicks on the product writeOff with 'WOUIBS-FF-05' number
Then the user checks write off elements values
| elementName | value |
| writeOff number review | WOUIBS-FF-05 |
| writeOff date review | 02.04.2013 |
And the user checks the write off product list contains entries
| productName | productSku | productBarcode | productAmount | productUnits |  productPrice | productCause |
| 7300330094020 | #sku:7300330094020 | 7300330094033 | 1,0 | шт. | 12,34 | Плохо продавался |
Then the user checks write off elements values
| elementName | value |
| totalProducts | 1 |
| totalSum | 12,34 |
