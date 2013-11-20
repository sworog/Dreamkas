Meta:
@sprint 23
@us 51

Narrative:
As a user
I want to perform an action
So that I can achieve a business goal

Scenario: The checkbox is active by default

Given there is the user with name 'departmentManager-s23u51', position 'departmentManager-s23u51', username 'departmentManager-s23u51', password 'lighthouse', role 'departmentManager'
And there is the store with number '2351' managed by department manager named 'departmentManager-s23u51'
And there is the subCategory with name 'defaultSubCategory-s23u51' related to group named 'defaultGroup-s23u51' and category named 'defaultCategory-s23u51'
And the user sets subCategory 'defaultSubCategory-s23u51' mark up with max '10' and min '0' values

And the user is on the invoice create page
And there is the invoice in the store with number '2351' ruled by department manager with name 'departmentManager-s23u51' with values
| elementName | elementValue |
| sku | invoice-2351 |
| acceptanceDate | 19.11.2013 16:23 |
| supplier | supplier |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceSku | supplierInvoiceSku |
| supplierInvoiceDate | 19.11.2013 |

Given the user navigates to the invoice page with name 'invoice-2351'
When the user logs in using 'departmentManager-s23u51' userName and 'lighthouse' password
Then the user checks the checkbox 'includesVAT' is 'checked'
And the user checks page contains text 'Цены включают НДС'
When the user clicks edit button and starts write off edition
Then the user checks the checkbox 'includesVAT' is 'checked'
And the user checks page contains text 'Цены включают НДС'

Scenario: The invoice with/without vat 10%

Given there is the user with name 'departmentManager-s23u51', position 'departmentManager-s23u51', username 'departmentManager-s23u51', password 'lighthouse', role 'departmentManager'
And there is the store with number '2351' managed by department manager named 'departmentManager-s23u51'
And there is the subCategory with name 'defaultSubCategory-s23u51' related to group named 'defaultGroup-s23u51' and category named 'defaultCategory-s23u51'
And the user sets subCategory 'defaultSubCategory-s23u51' mark up with max '10' and min '0' values

Given there is the product in subCategory with name 'defaultSubCategory-s23u51' with data
| name | name-2351 |
| units | unit |
| vat | 10 |
| purchasePrice | 134,80 |
| barcode | barcode-2351 |
| sku | sku-2351 |
| vendorCountry |  |
| vendor |  |
| info |  |
| retailMarkupMax |  |
| retailMarkupMin |  |
| rounding |  |

And there is the invoice in the store with number '2351' ruled by department manager with name 'departmentManager-s23u51' with values
| elementName | elementValue |
| sku | invoice-2351-1 |
| acceptanceDate | 19.11.2013 16:23 |
| supplier | supplier |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceSku | supplierInvoiceSku |
| supplierInvoiceDate | 19.11.2013 |
And the user adds the product to the invoice with name 'invoice-2351-1' with sku 'sku-2351', quantity '4,500', price '126,99' in the store ruled by 'departmentManager-s23u51'

Given the user navigates to the invoice page with name 'invoice-2351-1'
When the user logs in using 'departmentManager-s23u51' userName and 'lighthouse' password
Then the user checks the invoice products list contains entry
| productName | productSku | productBarcode | productAmount | productUnits | productPrice | productSum | vatSum | vat |
| name-2351 | sku-2351 | barcode-2351 | 4,500 | кг | 126,99 | 126,99 | 126,99 | 10 |

Then the user checks the checkbox 'includesVAT' is 'checked'
When the user clicks on item named 'includesVAT'
Then the user waits for checkBoxPreloader
Then the user checks the checkbox 'includesVAT' is 'unChecked'
Then the user checks the invoice products list contains entry
| productName | productSku | productBarcode | productAmount | productUnits | productPrice | productSum | vatSum | vat |
| name-2351 | sku-2351 | barcode-2351 | 4,500 | кг | 126,99 | 126,99 | 126,99 | 10 |

Scenario: The invoice with/without vat 0%

Given there is the user with name 'departmentManager-s23u51', position 'departmentManager-s23u51', username 'departmentManager-s23u51', password 'lighthouse', role 'departmentManager'
And there is the store with number '2351' managed by department manager named 'departmentManager-s23u51'
And there is the subCategory with name 'defaultSubCategory-s23u51' related to group named 'defaultGroup-s23u51' and category named 'defaultCategory-s23u51'
And the user sets subCategory 'defaultSubCategory-s23u51' mark up with max '10' and min '0' values

Given there is the product in subCategory with name 'defaultSubCategory-s23u51' with data
| name | name-2351-1 |
| units | unit |
| vat | 0 |
| purchasePrice | 134,80 |
| barcode | barcode-2351-1 |
| sku | sku-2351-1 |
| vendorCountry |  |
| vendor |  |
| info |  |
| retailMarkupMax |  |
| retailMarkupMin |  |
| rounding |  |

And there is the invoice in the store with number '2351' ruled by department manager with name 'departmentManager-s23u51' with values
| elementName | elementValue |
| sku | invoice-2351-1 |
| acceptanceDate | 19.11.2013 16:23 |
| supplier | supplier |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceSku | supplierInvoiceSku |
| supplierInvoiceDate | 19.11.2013 |
And the user adds the product to the invoice with name 'invoice-2351-1' with sku 'sku-2351-1', quantity '4,500', price '126,99' in the store ruled by 'departmentManager-s23u51'

Given the user navigates to the invoice page with name 'invoice-2351-1'
When the user logs in using 'departmentManager-s23u51' userName and 'lighthouse' password
Then the user checks the invoice products list contains entry
| productName | productSku | productBarcode | productAmount | productUnits | productPrice | productSum | vatSum | vat |
| name-2351 | sku-2351 | barcode-2351 | 4,500 | кг | 126,99 | 126,99 | 126,99 | 0 |

Then the user checks the checkbox 'includesVAT' is 'checked'
When the user clicks on item named 'includesVAT'
Then the user waits for checkBoxPreloader
Then the user checks the checkbox 'includesVAT' is 'unChecked'
Then the user checks the invoice products list contains entry
| productName | productSku | productBarcode | productAmount | productUnits | productPrice | productSum | vatSum | vat |
| name-2351 | sku-2351 | barcode-2351 | 4,500 | кг | 126,99 | 126,99 | 126,99 | 0 |

Scenario: The checkbox is not clickable in view mode

Scenario: Average and last price are not changed if the price with/without vat

