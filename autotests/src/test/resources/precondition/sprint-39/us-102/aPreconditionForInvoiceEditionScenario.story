Meta:
@smoke
@sprint_39
@us_102
@id_s39u102s2
@id_s39u102s12

Scenario: A scenario that prepares test data for invoice edition scenario

Given the user with email 's39u102@lighthouse.pro' creates the store with name 's39u102-store1' and address 's39u102-store1'
And the user with email 's39u102@lighthouse.pro' creates supplier with name 's39u102-supplier1', address 'address', phone 'phone', email 'email', contactPerson 'contactPerson'
And the user with email 's39u102@lighthouse.pro' creates group with name 's39u102-group1'
And the user with email 's39u102@lighthouse.pro' creates the product with name 's39u102-product2', units 'Пятюня', barcode 's39u102barcode2', vat '0', purchasePrice '125,5', sellingPrice '110' in the group with name 's39u102-group1'