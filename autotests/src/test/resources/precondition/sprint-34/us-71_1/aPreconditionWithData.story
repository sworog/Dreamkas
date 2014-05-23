Meta:
@smoke
@sprint_34
@us_71.1
@id_s34u71.1s1

Scenario: A scenario that prepares data

Given there is the product with 'name-34711999' name, '34711999' barcode, 'unit' type, '100' purchasePrice, markup min '10' max '30' of group named 'defaultGroup-s34u70.1', category named 'defaultCategory-s34u70.1', subcategory named 'defaultSubCategory-s34u70.1'
And the user adds the extra barcode with barcode '347119991', quantity '10' and price '100' to the barcode array
And the user adds the extra barcode with barcode '347119992', quantity '6' and price '45' to the barcode array
And the user adds the stored barcodes to the product with name 'name-34711999'
And there is created store with number '34711', address 'store-34711', contacts 'store-34711'
