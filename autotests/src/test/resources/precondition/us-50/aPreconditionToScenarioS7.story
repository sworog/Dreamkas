Meta:
@us 50
@id s23u50s7

Scenario: A scenario that prepares data

Given there is the product with 'name-s23u50-3' name, '2800465441' sku, '2800465441' barcode, 'unit' units, '100' purchasePrice of group named 'defaultGroup-s23u50', category named 'defaultCategory-s23u50', subcategory named 'defaultSubCategory-s23u50'

Given there is the date invoice with sku 'Invoice-s23u50-15days' and date 'today-15days' in the store with number '2350' ruled by department manager with name 'departmentManager-s23u50'
And the user adds the product to the invoice with name 'Invoice-s23u50-15days' with sku '2800465441', quantity '100', price '120,00' in the store ruled by 'departmentManager-s23u50'

Given the user runs the prepare fixture data cap command for negative inventory testing 2 - '31' days
And the user runs the recalculate_metrics cap command