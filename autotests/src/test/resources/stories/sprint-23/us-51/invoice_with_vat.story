Meta:
@sprint_23
@us_51

Narrative:
As a заведующий отделом,
I want to при приемке товара видеть ставку НДС и сумму НДС каждого принимаего товара, сумму НДС регистрируемой накладной, а также содержит ли цена приемки НДС,
In order to не допустить ошибок в учете налогов при приемке

Scenario: The checkbox is active by default

Meta:
@id_s23u51s1
@description_@smoke

GivenStories: precondition/sprint-23/us-51/aPreconditionToStoryUs51.story

Given the user is on the invoice list page
When the user logs in using 'departmentManager-s23u51' userName and 'lighthouse' password
And the user clicks the create button on the invoice list page
And the user inputs data to the invoice
| elementName  | value |
| sku | invoice-2351-0 |
| acceptanceDate | 02.04.2013 16:23 |
| supplier | Поставщик |
| accepter | Иван Петрович Петрович |
| legalEntity | Компания |
| supplierInvoiceSku | 123456 |
| supplierInvoiceDate | 01.04.2013 |
And the user navigates to invoice product addition
Then the user checks the checkbox 'includesVAT' is 'checked'

Scenario: Checkbox text

Meta:
@id_s23u51s2
@description

GivenStories: precondition/sprint-23/us-51/aPreconditionToStoryUs51.story,
              precondition/sprint-23/us-51/aPreconditionToScenarioS2.story

Given the user navigates to the invoice page with name 'invoice-2351'
When the user logs in using 'departmentManager-s23u51' userName and 'lighthouse' password
Then the user checks the checkbox 'includesVAT' is 'checked'
And the user checks the checkbox 'includesVAT' text is 'Цена включает НДС'
When the user clicks finish edit link and ends the invoice edition
Then the user checks the checkbox 'includesVAT' is 'checked'
And the user checks the checkbox 'includesVAT' text is 'Цена включает НДС'

Scenario: The invoice with/without vat 10%

Meta:
@id_s23u51s3
@description_@smoke

GivenStories: precondition/sprint-23/us-51/aPreconditionToStoryUs51.story,
              precondition/sprint-23/us-51/aPreconditionToScenarioS3.story

Given the user navigates to the invoice page with name 'invoice-2351-1'
When the user logs in using 'departmentManager-s23u51' userName and 'lighthouse' password
Then the user checks the invoice products list contains entry
| productName | productSku | productBarcode | productAmount | productUnits | productPrice | productSum | vatSum | vat |
| name-2351 | sku-2351 | barcode-2351 | 7,0 | шт. | 100,00 | 700,00 | 63,63 р. | 10 |
| name-2351-01 | sku-2351-01 | barcode-2351-01 | 15,0 | шт. | 120,00 | 1 800,00 | 274,65 р. | 18 |
And the user checks invoice elements values
| elementName | value |
| totalProducts | 2 |
| totalSum | 2 500,00 |
| totalVat | 338,28 |
Then the user checks the checkbox 'includesVAT' is 'checked'
When the user clicks on item named 'includesVAT'
Then the user waits for checkBoxPreloader
Then the user checks the checkbox 'includesVAT' is 'unChecked'
Then the user checks the invoice products list contains entry
| productName | productSku | productBarcode | productAmount | productUnits | productPrice | productSum | vatSum | vat |
| name-2351 | sku-2351 | barcode-2351 | 7,0 | шт. | 100,00 | 770,00 | 70,00 р. | 10 |
| name-2351-01 | sku-2351-01 | barcode-2351-01 | 15,0 | шт. | 120,00 | 2 124,00 | 324,00 р. | 18 |
And the user checks invoice elements values
| elementName | value |
| totalProducts | 2 |
| totalSum | 2 894,00 |
| totalVat | 394,00 |

Scenario: The invoice with/without vat 0%

Meta:
@id_s23u51s4
@description

GivenStories: precondition/sprint-23/us-51/aPreconditionToStoryUs51.story,
              precondition/sprint-23/us-51/aPreconditionToScenarioS4.story

Given the user navigates to the invoice page with name 'invoice-2351-2'
When the user logs in using 'departmentManager-s23u51' userName and 'lighthouse' password
Then the user checks the invoice products list contains entry
| productName | productSku | productBarcode | productAmount | productUnits | productPrice | productSum | vatSum | vat |
| name-2351-1 | sku-2351-1 | barcode-2351-1 | 4,555 | шт. | 145,50 | 662,75 | 0,00 р. | 0 |
Then the user checks the checkbox 'includesVAT' is 'checked'
When the user clicks on item named 'includesVAT'
Then the user waits for checkBoxPreloader
Then the user checks the checkbox 'includesVAT' is 'unChecked'
Then the user checks the invoice products list contains entry
| productName | productSku | productBarcode | productAmount | productUnits | productPrice | productSum | vatSum | vat |
| name-2351-1 | sku-2351-1 | barcode-2351-1 | 4,555 | шт. | 145,50 | 662,75 | 0,00 р. | 0 |

Scenario: The checkbox is not clickable in view mode

Meta:
@id_s23u51s5
@description_@smoke

GivenStories: precondition/sprint-23/us-51/aPreconditionToStoryUs51.story,
              precondition/sprint-23/us-51/aPreconditionToScenarioS5.story

Given the user navigates to the invoice page with name 'invoice-2351-3'
When the user logs in using 'departmentManager-s23u51' userName and 'lighthouse' password
When the user clicks finish edit link and ends the invoice edition
Then the user checks the invoice products list contains entry
| productName | productSku | productBarcode | productAmount | productUnits | productPrice | productSum | vatSum | vat |
| name-2351-2 | sku-2351-2 | barcode-2351-2 | 15,0 | шт. | 120,00 | 1 800,00 | 274,65 р. | 18 |
Then the user checks the checkbox 'includesVAT' is 'checked'
When the user clicks on item named 'includesVAT'
Then the user waits for checkBoxPreloader
Then the user checks the checkbox 'includesVAT' is 'checked'
Then the user checks the invoice products list contains entry
| productName | productSku | productBarcode | productAmount | productUnits | productPrice | productSum | vatSum | vat |
| name-2351-2 | sku-2351-2 | barcode-2351-2 | 15,0 | шт. | 120,00 | 1 800,00 | 274,65 р. | 18 |

Scenario: Average and last price are not changed if the price with/without vat

Meta:
@id_s23u51s6
@description_@smoke

GivenStories: precondition/sprint-23/us-51/aPreconditionToStoryUs51.story,
              precondition/sprint-23/us-51/aPreconditionToScenarioS6.story

Given the user navigates to the subCategory 'defaultSubCategory-s23u51', category 'defaultCategory-s23u51', group 'defaultGroup-s23u51' product list page
When the user logs in using 'departmentManager-s23u51' userName and 'lighthouse' password
When the user opens product balance tab
Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| name-2351-3 | sku-2351-3 | barcode-2351-3 | 10,0 | 0,0 | 0,0 | 100,00 р. | 100,00 р. |
| name-2351-4 | sku-2351-4 | barcode-2351-4 | 10,0 | 0,0 | 0,0 | 110,00 р. | 110,00 р. |

Scenario: Vat is not changed in already invoiceProduct if product have new vat

Meta:
@id_s23u51s7
@description

GivenStories: precondition/sprint-23/us-51/aPreconditionToStoryUs51.story,
              precondition/sprint-23/us-51/aPreconditionToScenarioS7.story

Given the user navigates to the invoice page with name 'invoice-2351-6'
When the user logs in using 'departmentManager-s23u51' userName and 'lighthouse' password
Then the user checks the invoice products list contains entry
| productName | productSku | productBarcode | productAmount | productUnits | productPrice | productSum | vatSum | vat |
| name-2351-6 | sku-2351-6 | barcode-2351-6 | 10,0 | шт. | 100,00 | 1 000,00 | 90,90 р. | 10 |
Given the user navigates to the invoice page with name 'invoice-2351-7'
Then the user checks the invoice products list contains entry
| name-2351-6 | sku-2351-6 | barcode-2351-6 | 10,0 | шт. | 100,00 | 1 000,00 | 0,00 р. | 0 |





