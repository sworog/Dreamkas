Meta:
@sprint_22_@smoke
@smoke
@us_40.3
@id_s22u40.3s1

Scenario: A scenario that prepares data

Given there is the subCategory with name 'ProductsExportSubCategory' related to group named 'ProductsExportGroup' and category named 'ProductsExportCategory'
And the user sets subCategory 'ProductsExportSubCategory' mark up with max '30' and min '0' values

And there is the product with 'Профитроли Коппенрат&Вайс Бэйлис 280г' name, '1008577061437' sku, '1008577061437' barcode, 'unit' units, '20,80' purchasePrice of group named 'ProductsExportGroup', category named 'ProductsExportCategory', subcategory named 'ProductsExportSubCategory'
And there is the user with name 'ReturnDepartmentManager', position 'ReturnDepartmentManager', username 'ReturnDepartmentManager', password 'lighthouse', role 'departmentManager'
And there is the user with name 'ReturnDepartmentManager2', position 'ReturnDepartmentManager2', username 'ReturnDepartmentManager2', password 'lighthouse', role 'departmentManager'

And there is the store with number '6666' managed by department manager named 'ReturnDepartmentManager'
And there is the store with number '7777' managed by department manager named 'ReturnDepartmentManager2'

