Meta:
@sprint 23
@us 52
@id s23u52s3
@smoke

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

Given there is the product with 'Черемша' name, '235212345' sku, '235212345' barcode, 'unit' units, '252,99' purchasePrice of group named 'defaultGroup-s23u52', category named 'defaultCategory-s23u52', subcategory named 'defaultSubCategory-s23u52'
And the robot prepares import sales data for story 52
And the robot waits the import folder become empty