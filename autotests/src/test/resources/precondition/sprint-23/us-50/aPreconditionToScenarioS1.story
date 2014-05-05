Meta:
@sprint_23
@us_50
@id_s23u50s1
@smoke

Scenario: A scenario that prepares data

Given there is the product with 'Балык свиной в/с в/об Матера' name, '2800465' barcode, 'unit' type, '312,80' purchasePrice of group named 'defaultGroup-s23u50', category named 'defaultCategory-s23u50', subcategory named 'defaultSubCategory-s23u50'
And there is the product with 'Балык Ломберный с/к в/с ТД Рублевский' name, '2805223' barcode, 'unit' type, '678,40' purchasePrice of group named 'defaultGroup-s23u50', category named 'defaultCategory-s23u50', subcategory named 'defaultSubCategory-s23u50'
And there is the product with 'Ассорти Читтерио мясное нар.140г' name, '80469353' barcode, 'unit' type, '449,60' purchasePrice of group named 'defaultGroup-s23u50', category named 'defaultCategory-s23u50', subcategory named 'defaultSubCategory-s23u50'

Given the user creates invoice api object with values
| elementName | value |
| acceptanceDate | today-10days |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | 2800465 |
| quantity | 34 |
| price | 312,80 |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | 2805223 |
| quantity | 28 |
| price | 678,40 |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | 80469353 |
| quantity | 45 |
| price | 449,60 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-s23u50'

Given the user creates invoice api object with values
| elementName | value |
| acceptanceDate | today-15days |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | 2800465 |
| quantity | 10 |
| price | 250,50 |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | 2805223 |
| quantity | 15 |
| price | 690,40 |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | 80469353 |
| quantity | 22 |
| price | 480,70 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-s23u50'

Given the user runs the prepare fixture data cap command for inventory testing
And the user runs the recalculate_metrics cap command