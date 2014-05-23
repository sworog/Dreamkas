Meta:
@us_71
@sprint_34

Narrative:
Как комерческий директор
Я хочу добавить к товару дополнительные ШК с указанием кол-ва и цены по этому ШК
Чтобы иметь возможность продавать товар по дополнительным ШК

Scenario: Adding the extra barcode to the product, which already had the same primary one

Meta:
@id_s32u71s14

Given there is the product with 'name-347101' name, '347101' barcode, 'unit' type, '100' purchasePrice
And the user navigates to the product with name 'name-347101'
And the user logs in as 'commercialManager'

When the user clicks the product local navigation barcodes link
And the user inputs values on product extra barcode page
| elementName | value |
| barcode | 347101 |
| quantity | 2 |
| price | 200 |
And the user clicks on add extra barcode button

Then the user sees 'Штрихкод уже используется в этом продукте. '

Scenario: Editing the primary barcode to the same as extra

Meta:
@id_s32u71s15

Given there is the product with 'name-347102' name, '347102' barcode, 'unit' type, '100' purchasePrice

Given the user adds the extra barcode with barcode '3471021', quantity '5' and price '1' to the barcode array
And the user adds the stored barcodes to the product with name 'name-347102'

Given the user navigates to the product with name 'name-347102'
And the user logs in as 'commercialManager'

When the user clicks the edit button on product card view page
And the user inputs values in element fields
| elementName | value |
| barcode | 3471021 |
And the user clicks the create button

Then the user sees 'Штрихкод уже используется в этом продукте'

Scenario: Adding the product with barcode, which already had the another product in primary

Meta:
@id_s32u71s16

Given the user runs the symfony:env:init command

Given there is the product with 'name-347103' name, '347103' barcode, 'unit' type, '100' purchasePrice

Given the user is on the product create page
And the user logs in as 'commercialManager'

When the user inputs values in element fields
| elementName | value |
| name | name-347104 |
| vat | 0 |
| barcode | 347103 |
And the user clicks the create button

Then the user sees exact error messages
| error message |
| Штрихкод уже используется в продукте [10001] "name-347103" |

Scenario: Adding the product with barcode, which already had the another product in extra codes

Meta:
@id_s32u71s17

Given the user runs the symfony:env:init command

Given there is the product with 'name-347104' name, '347104' barcode, 'unit' type, '100' purchasePrice

Given the user adds the extra barcode with barcode '3471041', quantity '5' and price '1' to the barcode array
And the user adds the stored barcodes to the product with name 'name-347104'

Given the user is on the product create page
And the user logs in as 'commercialManager'

When the user inputs values in element fields
| elementName | value |
| name | name-347105 |
| vat | 0 |
| barcode | 3471041 |
And the user clicks the create button

Then the user sees exact error messages
| error message |
| Штрихкод уже используется в продукте [10001] "name-347104" |

Scenario: Adding extra barcode - barcode is required

Meta:
@id_s32u71s18

Given there is the product with 'name-347105' name, '347105' barcode, 'unit' type, '100' purchasePrice
And the user navigates to the product with name 'name-347105'
And the user logs in as 'commercialManager'

When the user clicks the product local navigation barcodes link
And the user inputs values on product extra barcode page
| elementName | value |
| barcode |  |
| quantity | 2 |
| price | 200 |
And the user clicks on add extra barcode button

Then the user sees 'Заполните это поле. '

Scenario: Adding extra barcode - quantity is required

Meta:
@id_s32u71s19

Given there is the product with 'name-347106' name, '347106' barcode, 'unit' type, '100' purchasePrice
And the user navigates to the product with name 'name-347106'
And the user logs in as 'commercialManager'

When the user clicks the product local navigation barcodes link
And the user inputs values on product extra barcode page
| elementName | value |
| barcode | 3471061 |
| quantity |  |
| price | 200 |
And the user clicks on add extra barcode button

Then the user sees 'Заполните это поле. '

Scenario: Adding extra barcode - price is not required

Meta:
@id_s32u71s20

Given there is the product with 'name-347107' name, '347107' barcode, 'unit' type, '100' purchasePrice
And the user navigates to the product with name 'name-347107'
And the user logs in as 'commercialManager'

When the user clicks the product local navigation barcodes link
And the user inputs values on product extra barcode page
| elementName | value |
| barcode | 3471071 |
| quantity | 2 |
And the user clicks on add extra barcode button

Then the user checks the extra barcodes list contains exact entries
| barcode | quantity | price |
| 3471071 | 2,0 | |

When the user clicks on save extra barcode button

When the user clicks the product local navigation barcodes link

Then the user checks the extra barcodes list contains exact entries
| barcode | quantity | price |
| 3471071 | 2,0 |  |

Scenario: Adding extra barcode - quantity is set by 1 by default

Meta:
@id_s32u71s21

Given there is the product with 'name-347108' name, '347108' barcode, 'unit' type, '100' purchasePrice
And the user navigates to the product with name 'name-347108'
And the user logs in as 'commercialManager'

When the user clicks the product local navigation barcodes link
And the user inputs values on product extra barcode page
| elementName | value |
| barcode | 3471081 |
| price | 200 |
And the user clicks on add extra barcode button

Then the user checks the extra barcodes list contains exact entries
| barcode | quantity | price |
| 3471081 | 1,0 | 200,00 |

When the user clicks on save extra barcode button

When the user clicks the product local navigation barcodes link

Then the user checks the extra barcodes list contains exact entries
| barcode | quantity | price |
| 3471081 | 1,0 | 200,00 |

Scenario: Editing extra barcode - barcode is required

Meta:
@id_s32u71s22

Given there is the product with 'name-347109' name, '347109' barcode, 'unit' type, '100' purchasePrice

Given the user adds the extra barcode with barcode '3471091', quantity '5' and price '1' to the barcode array
And the user adds the stored barcodes to the product with name 'name-347109'

Given the user navigates to the product with name 'name-347109'
And the user logs in as 'commercialManager'

When the user clicks the product local navigation barcodes link
And the user types '' in barcode element with barcode '3471091'
And the user clicks on save extra barcode button

Then the user sees 'Заполните это поле. '

Scenario: Editing extra barcode - quantity is required

Meta:
@id_s32u71s23

Given there is the product with 'name-3471010' name, '3471010' barcode, 'unit' type, '100' purchasePrice

Given the user adds the extra barcode with barcode '34710101', quantity '5' and price '1' to the barcode array
And the user adds the stored barcodes to the product with name 'name-3471010'

Given the user navigates to the product with name 'name-3471010'
And the user logs in as 'commercialManager'

When the user clicks the product local navigation barcodes link
And the user types '' in quantity element with barcode '34710101'
And the user clicks on save extra barcode button

Then the user sees 'Заполните это поле. '

Scenario: Editing extra barcode - price is not required

Meta:
@id_s32u71s24

Given there is the product with 'name-3471011' name, '3471011' barcode, 'unit' type, '100' purchasePrice

Given the user adds the extra barcode with barcode '34710111', quantity '5' and price '1' to the barcode array
And the user adds the stored barcodes to the product with name 'name-3471011'

Given the user navigates to the product with name 'name-3471011'
And the user logs in as 'commercialManager'

When the user clicks the product local navigation barcodes link
And the user types '' in price element with barcode '34710111'

Then the user checks the extra barcodes list contains exact entries
| barcode | quantity | price |
| 34710111 | 5,0 |  |

When the user clicks on save extra barcode button

When the user clicks the product local navigation barcodes link

Then the user checks the extra barcodes list contains exact entries
| barcode | quantity | price |
| 34710111 | 5,0 |  |