Meta:
@us 50.1
@id s23u50.1s3
@smoke

Scenario: A scenario that prepares data

Given there is the product with 'SCPBC-name-2' name, 'SCPBC-sku-2' sku, 'SCPBC-barcode-2' barcode, 'unit' units, '12,34' purchasePrice of group named 'SCPBC-defaultGroup', category named 'SCPBC-defaultCategory', subcategory named 'SCPBC-defaultSubCategory'

Given there is the invoice in the store with number 'SCPBC' ruled by department manager with name 'departmentManager-SCPBC' with values
| elementName | elementValue |
| sku | SCPBC-01 |
| acceptanceDate | 02.04.2013 16:23 |
| supplier | supplier |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceSku | supplierInvoiceSku |
| supplierInvoiceDate | 02.04.2013 |
And the user adds the product to the invoice with name 'SCPBC-01' with sku 'SCPBC-sku-2', quantity '1', price '100' in the store ruled by 'departmentManager-SCPBC'