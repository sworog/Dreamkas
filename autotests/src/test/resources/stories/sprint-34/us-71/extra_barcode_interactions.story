Meta:
@us_71
@sprint_34

Narrative:
Как комерческий директор
Я хочу добавить к товару дополнительные ШК с указанием кол-ва и цены по этому ШК
Чтобы иметь возможность продавать товар по дополнительным ШК

Scenario: Product extra barcode create

Meta:
@id_s32u71s1
@smoke

Given there is the product with 'name-3471' name, '3471' barcode, 'unit' type, '100' purchasePrice
And the user navigates to the product with name 'name-3471'
And the user logs in as 'commercialManager'

When the user clicks the product local navigation barcodes link
And the user inputs values on product extra barcode page
| elementName | value |
| barcode | 7134 |
| quantity | 2 |
| price | 200 |
And the user clicks on add extra barcode button

Then the user checks the extra barcodes list contains exact entries
| barcode | quantity | price |
| 7134 | 2,0 | 200,00 |

When the user clicks on save extra barcode button

When the user clicks the product local navigation barcodes link

Then the user checks the extra barcodes list contains exact entries
| barcode | quantity | price |
| 7134 | 2,0 | 200,00 |

Scenario: Product extra barcode edit

Meta:
@id_s32u71s2
@smoke

Given there is the product with 'name-34711' name, '34711' barcode, 'unit' type, '100' purchasePrice
And the user navigates to the product with name 'name-34711'
And the user logs in as 'commercialManager'

When the user clicks the product local navigation barcodes link
And the user inputs values on product extra barcode page
| elementName | value |
| barcode | 71341 |
| quantity | 2 |
| price | 200 |
And the user clicks on add extra barcode button

Then the user checks the extra barcodes list contains exact entries
| barcode | quantity | price |
| 71341 | 2,0 | 200,00 |

When the user clicks on save extra barcode button

When the user clicks the product local navigation barcodes link

Then the user checks the extra barcodes list contains exact entries
| barcode | quantity | price |
| 71341 | 2,0 | 200,00 |

When the user types '72341' in barcode element with barcode '71341'
And the user types '4,456' in quantity element with barcode '72341'
And the user types '56,67' in price element with barcode '72341'

Then the user checks the extra barcodes list contains exact entries
| barcode | quantity | price |
| 72341 | 4,456 | 56,67 |

When the user clicks on save extra barcode button

When the user clicks the product local navigation barcodes link

Then the user checks the extra barcodes list contains exact entries
| barcode | quantity | price |
| 72341 | 4,456 | 56,67 |

Scenario: Product extra barcode edit cancel

Meta:
@id_s32u71s3
@smoke

Given there is the product with 'name-347111' name, '347111' barcode, 'unit' type, '100' purchasePrice
And the user navigates to the product with name 'name-347111'
And the user logs in as 'commercialManager'

When the user clicks the product local navigation barcodes link
And the user inputs values on product extra barcode page
| elementName | value |
| barcode | 713411 |
| quantity | 2 |
| price | 200 |
And the user clicks on add extra barcode button

Then the user checks the extra barcodes list contains exact entries
| barcode | quantity | price |
| 713411 | 2,0 | 200,00 |

When the user clicks on save extra barcode button

When the user clicks the product local navigation barcodes link

Then the user checks the extra barcodes list contains exact entries
| barcode | quantity | price |
| 713411 | 2,0 | 200,00 |

When the user types '723411' in barcode element with barcode '713411'
And the user types '4,456' in quantity element with barcode '723411'
And the user types '56,67' in price element with barcode '723411'

Then the user checks the extra barcodes list contains exact entries
| barcode | quantity | price |
| 723411 | 4,456 | 56,67 |

When the user clicks on cancel save extra barcode button

When the user clicks the product local navigation barcodes link

Then the user checks the extra barcodes list contains exact entries
| barcode | quantity | price |
| 713411 | 2,0 | 200,00 |

Scenario: Product extra barcode delete

Meta:
@id_s32u71s4
@smoke

Given there is the product with 'name-34712' name, '347112' barcode, 'unit' type, '100' purchasePrice
And the user navigates to the product with name 'name-34712'
And the user logs in as 'commercialManager'

When the user clicks the product local navigation barcodes link
And the user inputs values on product extra barcode page
| elementName | value |
| barcode | 713412 |
| quantity | 2 |
| price | 200 |
And the user clicks on add extra barcode button

Then the user checks the extra barcodes list contains exact entries
| barcode | quantity | price |
| 713412 | 2,0 | 200,00 |

When the user inputs values on product extra barcode page
| elementName | value |
| barcode | 713413 |
| quantity | 3 |
| price | 300 |
And the user clicks on add extra barcode button

Then the user checks the extra barcodes list contains exact entries
| barcode | quantity | price |
| 713412 | 2,0 | 200,00 |
| 713413 | 3,0 | 300,00 |

When the user clicks on save extra barcode button

When the user clicks the product local navigation barcodes link

Then the user checks the extra barcodes list contains exact entries
| barcode | quantity | price |
| 713412 | 2,0 | 200,00 |
| 713413 | 3,0 | 300,00 |

When the user deletes barcode with barcode '713412'

Then the user checks the extra barcodes list contains exact entries
| barcode | quantity | price |
| 713413 | 3,0 | 300,00 |

When the user clicks on save extra barcode button

When the user clicks the product local navigation barcodes link

Then the user checks the extra barcodes list contains exact entries
| barcode | quantity | price |
| 713413 | 3,0 | 300,00 |

Scenario: Product extra barcode delete cancel

Meta:
@id_s32u71s5
@smoke

Given there is the product with 'name-347122' name, '3471122' barcode, 'unit' type, '100' purchasePrice
And the user navigates to the product with name 'name-347122'
And the user logs in as 'commercialManager'

When the user clicks the product local navigation barcodes link
And the user inputs values on product extra barcode page
| elementName | value |
| barcode | 7134122 |
| quantity | 2 |
| price | 200 |
And the user clicks on add extra barcode button

Then the user checks the extra barcodes list contains exact entries
| barcode | quantity | price |
| 7134122 | 2,0 | 200,00 |

When the user inputs values on product extra barcode page
| elementName | value |
| barcode | 7134132 |
| quantity | 3 |
| price | 300 |
And the user clicks on add extra barcode button

Then the user checks the extra barcodes list contains exact entries
| barcode | quantity | price |
| 7134122 | 2,0 | 200,00 |
| 7134132 | 3,0 | 300,00 |

When the user clicks on save extra barcode button

When the user clicks the product local navigation barcodes link

Then the user checks the extra barcodes list contains exact entries
| barcode | quantity | price |
| 7134122 | 2,0 | 200,00 |
| 7134132 | 3,0 | 300,00 |

When the user deletes barcode with barcode '7134122'

Then the user checks the extra barcodes list contains exact entries
| barcode | quantity | price |
| 7134132 | 3,0 | 300,00 |

When the user clicks on cancel save extra barcode button

When the user clicks the product local navigation barcodes link

Then the user checks the extra barcodes list contains exact entries
| barcode | quantity | price |
| 7134122 | 2,0 | 200,00 |
| 7134132 | 3,0 | 300,00 |