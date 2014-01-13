Meta:
@sprint 22
@smoke
@us 40.3
@id s22u40.3s1

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

Given there is the subCategory with name 'ProductsExportSubCategory' related to group named 'ProductsExportGroup' and category named 'ProductsExportCategory'
And the user sets subCategory 'ProductsExportSubCategory' mark up with max '30' and min '0' values

And there is the product with 'Профитроли Коппенрат&Вайс Бэйлис 280г' name, '1008577061437' sku, '1008577061437' barcode, 'unit' units, '20,80' purchasePrice of group named 'ProductsExportGroup', category named 'ProductsExportCategory', subcategory named 'ProductsExportSubCategory'
And there is the user with name 'ReturnDepartmentManager', position 'ReturnDepartmentManager', username 'ReturnDepartmentManager', password 'lighthouse', role 'departmentManager'
And there is the user with name 'ReturnDepartmentManager2', position 'ReturnDepartmentManager2', username 'ReturnDepartmentManager2', password 'lighthouse', role 'departmentManager'

And there is the store with number '6666' managed by department manager named 'ReturnDepartmentManager'
And there is the store with number '7777' managed by department manager named 'ReturnDepartmentManager2'

