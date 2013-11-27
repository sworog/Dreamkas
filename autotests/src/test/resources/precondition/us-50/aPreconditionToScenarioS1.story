Meta:
@us 50
@id s23u50s1
@smoke

Scenario: A scenario that prepares data

Given there is the product with 'Балык свиной в/с в/об Матера' name, '2800465' sku, '2800465' barcode, 'unit' units, '312,80' purchasePrice of group named 'defaultGroup-s23u50', category named 'defaultCategory-s23u50', subcategory named 'defaultSubCategory-s23u50'
And there is the product with 'Балык Ломберный с/к в/с ТД Рублевский' name, '2805223' sku, '2805223' barcode, 'unit' units, '678,40' purchasePrice of group named 'defaultGroup-s23u50', category named 'defaultCategory-s23u50', subcategory named 'defaultSubCategory-s23u50'
And there is the product with 'Ассорти Читтерио мясное нар.140г' name, '80469353' sku, '80469353' barcode, 'unit' units, '449,60' purchasePrice of group named 'defaultGroup-s23u50', category named 'defaultCategory-s23u50', subcategory named 'defaultSubCategory-s23u50'

Given there is the date invoice with sku 'Invoice-s23u50-10days' and date 'today-10days' in the store with number '2350' ruled by department manager with name 'departmentManager-s23u50'
And the user adds the product to the invoice with name 'Invoice-s23u50-10days' with sku '2800465', quantity '34', price '312,80' in the store ruled by 'departmentManager-s23u50'
And the user adds the product to the invoice with name 'Invoice-s23u50-10days' with sku '2805223', quantity '28', price '678,40' in the store ruled by 'departmentManager-s23u50'
And the user adds the product to the invoice with name 'Invoice-s23u50-10days' with sku '80469353', quantity '45', price '449,60' in the store ruled by 'departmentManager-s23u50'

Given there is the date invoice with sku 'Invoice-s23u50-15days' and date 'today-15days' in the store with number '2350' ruled by department manager with name 'departmentManager-s23u50'
And the user adds the product to the invoice with name 'Invoice-s23u50-15days' with sku '2800465', quantity '10', price '250,50' in the store ruled by 'departmentManager-s23u50'
And the user adds the product to the invoice with name 'Invoice-s23u50-15days' with sku '2805223', quantity '15', price '690,40' in the store ruled by 'departmentManager-s23u50'
And the user adds the product to the invoice with name 'Invoice-s23u50-15days' with sku '80469353', quantity '22', price '480,70' in the store ruled by 'departmentManager-s23u50'

Given the user runs the prepare fixture data cap command for inventory testing
And the user runs the recalculate_metrics cap command