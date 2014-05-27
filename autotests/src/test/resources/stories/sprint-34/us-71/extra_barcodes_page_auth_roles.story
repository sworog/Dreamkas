Meta:
@us_71
@sprint_34

Narrative:
Как комерческий директор
Я хочу добавить к товару дополнительные ШК с указанием кол-ва и цены по этому ШК
Чтобы иметь возможность продавать товар по дополнительным ШК

Scenario: No add extra barcode button for departmentManager

Meta:
@id_s32u71s6

Given there is the product with 'name-34714' name, '34714' barcode, 'unit' type, '100' purchasePrice

Given the user adds the extra barcode with barcode '41743', quantity '5' and price '1' to the barcode array
And the user adds the stored barcodes to the product with name 'name-34714'

Given there is the user with name 'departmentManager-3471', position 'departmentManager-3471', username 'departmentManager-3471', password 'lighthouse', role 'departmentManager'
And there is the store with number 'store-3471' managed by department manager named 'departmentManager-3471'

Given the user navigates to the product with name 'name-34714'
And the user logs in using 'departmentManager-3471' userName and 'lighthouse' password

When the user clicks the product local navigation barcodes link

Then the user checks the add extra barcode button should be not visible

Scenario: No add extra barcode button for storeManager

Meta:
@id_s32u71s7

Given there is the product with 'name-347141' name, '347141' barcode, 'unit' type, '100' purchasePrice

Given the user adds the extra barcode with barcode '417431', quantity '5' and price '1' to the barcode array
And the user adds the stored barcodes to the product with name 'name-347141'

Given there is the user with name 'storeManager-3471', position 'storeManager-3471', username 'storeManager-3471', password 'lighthouse', role 'storeManager'
And there is the store with number 'store-3471' managed by 'storeManager-3471'

Given the user navigates to the product with name 'name-347141'
And the user logs in using 'storeManager-3471' userName and 'lighthouse' password

When the user clicks the product local navigation barcodes link

Then the user checks the add extra barcode button should be not visible

Scenario: No save extra barcode button for departmentManager

Meta:
@id_s32u71s8

Given there is the product with 'name-347142' name, '347142' barcode, 'unit' type, '100' purchasePrice

Given the user adds the extra barcode with barcode '417432', quantity '5' and price '1' to the barcode array
And the user adds the stored barcodes to the product with name 'name-347142'

Given there is the user with name 'departmentManager-3471', position 'departmentManager-3471', username 'departmentManager-3471', password 'lighthouse', role 'departmentManager'
And there is the store with number 'store-3471' managed by department manager named 'departmentManager-3471'

Given the user navigates to the product with name 'name-347142'
And the user logs in using 'departmentManager-3471' userName and 'lighthouse' password

When the user clicks the product local navigation barcodes link

Then the user checks the save extra barcode button should be not visible

Scenario: No save extra barcode button for storeManager

Meta:
@id_s32u71s9

Given there is the product with 'name-347143' name, '347143' barcode, 'unit' type, '100' purchasePrice

Given the user adds the extra barcode with barcode '417433', quantity '5' and price '1' to the barcode array
And the user adds the stored barcodes to the product with name 'name-347143'

Given there is the user with name 'storeManager-3471', position 'storeManager-3471', username 'storeManager-3471', password 'lighthouse', role 'storeManager'
And there is the store with number 'store-3471' managed by 'storeManager-3471'

Given the user navigates to the product with name 'name-347143'
And the user logs in using 'storeManager-3471' userName and 'lighthouse' password

When the user clicks the product local navigation barcodes link

Then the user checks the save extra barcode button should be not visible

Scenario: No cancel save extra barcode link for departmentManager

Meta:
@id_s32u71s10

Given there is the product with 'name-347144' name, '347144' barcode, 'unit' type, '100' purchasePrice

Given the user adds the extra barcode with barcode '417434', quantity '5' and price '1' to the barcode array
And the user adds the stored barcodes to the product with name 'name-347144'

Given there is the user with name 'departmentManager-3471', position 'departmentManager-3471', username 'departmentManager-3471', password 'lighthouse', role 'departmentManager'
And there is the store with number 'store-3471' managed by department manager named 'departmentManager-3471'

Given the user navigates to the product with name 'name-347144'
And the user logs in using 'departmentManager-3471' userName and 'lighthouse' password

When the user clicks the product local navigation barcodes link

Then the user checks the cancel save extra barcode link should be not visible

Scenario: No cancel save extra barcode link for storeManager

Meta:
@id_s32u71s11

Given there is the product with 'name-347145' name, '347145' barcode, 'unit' type, '100' purchasePrice

Given the user adds the extra barcode with barcode '417435', quantity '5' and price '1' to the barcode array
And the user adds the stored barcodes to the product with name 'name-347145'

Given there is the user with name 'storeManager-3471', position 'storeManager-3471', username 'storeManager-3471', password 'lighthouse', role 'storeManager'
And there is the store with number 'store-3471' managed by 'storeManager-3471'

Given the user navigates to the product with name 'name-347145'
And the user logs in using 'storeManager-3471' userName and 'lighthouse' password

When the user clicks the product local navigation barcodes link

Then the user checks the cancel save extra barcode link should be not visible

Scenario: No extra barcode input fields for departmentManager

Meta:
@id_s32u71s12

Given there is the product with 'name-347146' name, '347146' barcode, 'unit' type, '100' purchasePrice

Given the user adds the extra barcode with barcode '417436', quantity '5' and price '1' to the barcode array
And the user adds the stored barcodes to the product with name 'name-347146'

Given there is the user with name 'departmentManager-3471', position 'departmentManager-3471', username 'departmentManager-3471', password 'lighthouse', role 'departmentManager'
And there is the store with number 'store-3471' managed by department manager named 'departmentManager-3471'

Given the user navigates to the product with name 'name-347146'
And the user logs in using 'departmentManager-3471' userName and 'lighthouse' password

When the user clicks the product local navigation barcodes link

Then the user checks the cancel save extra barcode link should be not visible

Then the user checks the element with name 'barcode' should be not visible
And the user checks the element with name 'quantity' should be not visible
And the user checks the element with name 'price' should be not visible

Scenario: No extra barcode input fields for storeManager

Meta:
@id_s32u71s13

Given there is the product with 'name-347147' name, '347147' barcode, 'unit' type, '100' purchasePrice

Given the user adds the extra barcode with barcode '417437', quantity '5' and price '1' to the barcode array
And the user adds the stored barcodes to the product with name 'name-347147'

Given there is the user with name 'storeManager-3471', position 'storeManager-3471', username 'storeManager-3471', password 'lighthouse', role 'storeManager'
And there is the store with number 'store-3471' managed by 'storeManager-3471'

Given the user navigates to the product with name 'name-347147'
And the user logs in using 'storeManager-3471' userName and 'lighthouse' password

When the user clicks the product local navigation barcodes link

Then the user checks the cancel save extra barcode link should be not visible

Then the user checks the element with name 'barcode' should be not visible
And the user checks the element with name 'quantity' should be not visible
And the user checks the element with name 'price' should be not visible