Meta:
@smoke
@sprint_39
@us_102
@id_s39u102s2
@id_s39u102s3
@id_s39u102s6
@id_s39u102s9
@id_s39u102s10
@id_s39u102s12

Scenario: A scenario that prepares invoice

Given the user creates invoice api object with date '28.07.2014', paid status 'false', store with name 's39u102-store', supplier with name 's39u102-supplier'
And the user adds the product with name 's39u102-product1' with price '150' and quantity '5 'to invoice api object
And the user with email 's39u102@lighthouse.pro 'creates invoice with builders steps