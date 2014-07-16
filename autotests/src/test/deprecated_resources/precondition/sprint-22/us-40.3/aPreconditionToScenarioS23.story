Meta:
@sprint_22
@us_40.3
@id_s22u40.3s2
@id_s22u40.3s3

Scenario: A scenario that prepares data

Given there is the user with name 'NRTF-1', position 'NRTF-1', username 'NRTF-1', password 'lighthouse', role 'storeManager'
And there is the store with number 'NRTF' managed by 'NRTF-1'
Given there is the subCategory with name 'SCPBC-defaultSubCategory' related to group named 'SCPBC-defaultGroup' and category named 'SCPBC-defaultCategory'
And there is the product with 'SCPBC-name-1' name, 'SCPBC-barcode-1' barcode, 'unit' type, '12,34' purchasePrice of group named 'SCPBC-defaultGroup', category named 'SCPBC-defaultCategory', subcategory named 'SCPBC-defaultSubCategory'