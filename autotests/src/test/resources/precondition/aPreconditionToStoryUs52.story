Meta:
@us 52
@id s23u52s1
@id s23u52s2
@id s23u52s3
@id s23u52s4
@id s23u52s5

Scenario: A scenario that prepares data

Given the user opens the settings page
And the user logs in as 'watchman'
When the user input values on the setting page
| elementName | value |
| set10-import-url | smb://faro.lighthouse.cs/centrum/reports |
| set10-import-login | erp |
| set10-import-interval | 60 |
| set10-import-password | erp |
And the user clicks import save button on the setting page
Then the user sees success message 'Настройки успешно сохранены'
When the user logs out
Given there is the user with name 'departmentManager-s23u52', position 'departmentManager-s23u52', username 'departmentManager-s23u52', password 'lighthouse', role 'departmentManager'
And there is the store with number '2352' managed by department manager named 'departmentManager-s23u52'
And there is the subCategory with name 'defaultSubCategory-s23u52' related to group named 'defaultGroup-s23u52' and category named 'defaultCategory-s23u52'
And the user sets subCategory 'defaultSubCategory-s23u52' mark up with max '10' and min '0' values
And there is the product with 'name-2352' name, 'sku-2352' sku, 'barcode-2352' barcode, 'unit' units, '134,80' purchasePrice of group named 'defaultGroup-s23u52', category named 'defaultCategory-s23u52', subcategory named 'defaultSubCategory-s23u52'
And there is the product with 'name-2352-1' name, 'sku-2352-1' sku, 'barcode-2352-1' barcode, 'unit' units, '134,80' purchasePrice of group named 'defaultGroup-s23u52', category named 'defaultCategory-s23u52', subcategory named 'defaultSubCategory-s23u52'
And there is the product with 'name-2352-2' name, 'sku-2352-2' sku, 'barcode-2352-2' barcode, 'unit' units, '134,80' purchasePrice of group named 'defaultGroup-s23u52', category named 'defaultCategory-s23u52', subcategory named 'defaultSubCategory-s23u52'
And there is the product with 'Черемша' name, '235212345' sku, '235212345' barcode, 'unit' units, '252,99' purchasePrice of group named 'defaultGroup-s23u52', category named 'defaultCategory-s23u52', subcategory named 'defaultSubCategory-s23u52'
And there is the invoice in the store with number '2352' ruled by department manager with name 'departmentManager-s23u52' with values
| elementName | elementValue |
| sku | invoice-2352 |
| acceptanceDate | 02.04.2013 16:23 |
| supplier | supplier |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceSku | supplierInvoiceSku |
| supplierInvoiceDate | 02.04.2013 |
And the user adds the product to the invoice with name 'invoice-2352' with sku 'sku-2352', quantity '3,675', price '126,99' in the store ruled by 'departmentManager-s23u52'
And there is the writeOff in the store with number '2352' ruled by department manager with name 'departmentManager-s23u52' with values
| elementName | elementValue |
| number | writeOff-2352-1 |
| date | 02.04.2013 |
And the user adds the product to the write off with number 'writeOff-2352-1' with sku 'sku-2352-1', quantity '4,671', price '121,34, cause 'Плохо продавался' in the store ruled by 'departmentManager-s23u52'
Given there is the invoice in the store with number '2352' ruled by department manager with name 'departmentManager-s23u52' with values
| elementName | elementValue |
| sku | invoice-2352-1 |
| acceptanceDate | 02.04.2013 16:23 |
| supplier | supplier |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceSku | supplierInvoiceSku |
| supplierInvoiceDate | 02.04.2013 |
Given there is the invoice in the store with number '2352' ruled by department manager with name 'departmentManager-s23u52' with values
| elementName | elementValue |
| sku | invoice-2352-2 |
| acceptanceDate | 02.04.2013 16:23 |
| supplier | supplier |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceSku | supplierInvoiceSku |
| supplierInvoiceDate | 02.04.2013 |
And the user adds the product to the invoice with name 'invoice-2352-2' with sku 'sku-2352-2', quantity '3', price '126,99' in the store ruled by 'departmentManager-s23u52'