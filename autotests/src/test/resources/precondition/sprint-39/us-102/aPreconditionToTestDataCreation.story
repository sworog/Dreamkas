Meta:
@smoke
@sprint_39
@us_102
@id_s39u102s1
@id_s39u102s2
@id_s39u102s3
@id_s39u102s6
@id_s39u102s9
@id_s39u102s10
@id_s39u102s12

Scenario: A scenario that prepares test data

Given the user with email 's39u102@lighthouse.pro' creates the store with name 's39u102-store' and address 's39u102-store'
And the user with email 's39u102@lighthouse.pro' creates supplier with name 's39u102-supplier', address 'address', phone 'phone', email 'email', contactPerson 'contactPerson'
And the user with email 's39u102@lighthouse.pro' creates group with name 's39u102-group'
And the user with email 's39u102@lighthouse.pro' creates the product with name 's39u102-product1', units 'шт.', barcode 's39u102barcode1', vat '0', purchasePrice '100', sellingPrice '110' in the group with name 's39u102-group'