Meta:
@sprint 22
@us 50.1
@id s23u50.1s5

Scenario: A scenario that prepares data

Given there is the product with 'SCPBC-name-4' name, 'SCPBC-sku-4' sku, 'SCPBC-barcode-4' barcode, 'unit' units, '12,34' purchasePrice of group named 'SCPBC-defaultGroup', category named 'SCPBC-defaultCategory', subcategory named 'SCPBC-defaultSubCategory'

Given there is the date invoice with sku 'SCPBC-02' and date 'today-15days' in the store with number 'SCPBC' ruled by department manager with name 'departmentManager-SCPBC'
And the user adds the product to the invoice with name 'SCPBC-02' with sku 'SCPBC-sku-4', quantity '1', price '145' in the store ruled by 'departmentManager-SCPBC'

Given there is the date invoice with sku 'SCPBC-03' and date 'today-14days' in the store with number 'SCPBC' ruled by department manager with name 'departmentManager-SCPBC'
And the user adds the product to the invoice with name 'SCPBC-03' with sku 'SCPBC-sku-4', quantity '2', price '123' in the store ruled by 'departmentManager-SCPBC'

Given the user runs the recalculate_metrics cap command