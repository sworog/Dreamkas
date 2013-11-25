Meta:
@smoke
@us 50.1
@id s22u50.1s1
@id s22u50.1s2
@id s22u50.1s3
@id s22u50.1s4
@id s22u50.1s5
@id s22u50.1s6

Scenario: Precondition scenario
Given there is the user with name 'departmentManager-SCPBC', position 'departmentManager-SCPBC', username 'departmentManager-SCPBC', password 'lighthouse', role 'departmentManager'
And there is the store with number 'SCPBC' managed by department manager named 'departmentManager-SCPBC'
Given there is the subCategory with name 'SCPBC-defaultSubCategory' related to group named 'SCPBC-defaultGroup' and category named 'SCPBC-defaultCategory'
And there is the product with 'SCPBC-name' name, 'SCPBC-sku' sku, 'SCPBC-barcode' barcode, 'unit' units, '12,34' purchasePrice of group named 'SCPBC-defaultGroup', category named 'SCPBC-defaultCategory', subcategory named 'SCPBC-defaultSubCategory'
And there is the product with 'SCPBC-name-1' name, 'SCPBC-sku-1' sku, '' barcode, 'unit' units, '' purchasePrice of group named 'SCPBC-defaultGroup', category named 'SCPBC-defaultCategory', subcategory named 'SCPBC-defaultSubCategory'
And there is the product with 'SCPBC-name-2' name, 'SCPBC-sku-2' sku, 'SCPBC-barcode-2' barcode, 'unit' units, '12,34' purchasePrice of group named 'SCPBC-defaultGroup', category named 'SCPBC-defaultCategory', subcategory named 'SCPBC-defaultSubCategory'
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
And there is the product with 'SCPBC-name-3' name, 'SCPBC-sku-3' sku, 'SCPBC-barcode-3' barcode, 'unit' units, '12,34' purchasePrice of group named 'SCPBC-defaultGroup', category named 'SCPBC-defaultCategory', subcategory named 'SCPBC-defaultSubCategory'
And there is the writeOff in the store with number 'SCPBC' ruled by department manager with name 'departmentManager-SCPBC' with values
| elementName | elementValue |
| number | SCPBC-01 |
| date | 02.04.2013 |
And the user adds the product to the write off with number 'SCPBC-01' with sku 'SCPBC-sku-3', quantity '1', price '12,34, cause 'Плохо продавался' in the store ruled by 'departmentManager-SCPBC'
And there is the product with 'SCPBC-name-4' name, 'SCPBC-sku-4' sku, 'SCPBC-barcode-4' barcode, 'unit' units, '12,34' purchasePrice of group named 'SCPBC-defaultGroup', category named 'SCPBC-defaultCategory', subcategory named 'SCPBC-defaultSubCategory'
Given there is the user with name 'NPBTFST-1', position 'NPBTFST-1', username 'NPBTFST-1', password 'lighthouse', role 'storeManager'
And there is the store with number 'NPBTFST' managed by 'NPBTFST-1'
And there is the product with 'NPBTF-name-1' name, 'SCPBC-sku-1' sku, 'SCPBC-barcode-1' barcode, 'unit' units, '12,34' purchasePrice of group named 'SCPBC-defaultGroup', category named 'SCPBC-defaultCategory', subcategory named 'SCPBC-defaultSubCategory'
And there is the product with 'NPBTF-name-1' name, 'SCPBC-sku-1' sku, 'SCPBC-barcode-1' barcode, 'unit' units, '12,34' purchasePrice of group named 'SCPBC-defaultGroup', category named 'SCPBC-defaultCategory', subcategory named 'SCPBC-defaultSubCategory'
Given there is the date invoice with sku 'SCPBC-02' and date 'today-15days' in the store with number 'SCPBC' ruled by department manager with name 'departmentManager-SCPBC'
And the user adds the product to the invoice with name 'SCPBC-02' with sku 'SCPBC-sku-4', quantity '1', price '145' in the store ruled by 'departmentManager-SCPBC'
Given there is the date invoice with sku 'SCPBC-03' and date 'today-14days' in the store with number 'SCPBC' ruled by department manager with name 'departmentManager-SCPBC'
And the user adds the product to the invoice with name 'SCPBC-03' with sku 'SCPBC-sku-4', quantity '2', price '123' in the store ruled by 'departmentManager-SCPBC'