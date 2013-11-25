8.4 Валидация вводимых данных при добавлении товарных позиций в накладную

Narrative:
Правила валидации:

1. Все поля в секции "Добавление товаров в накладную" обязательные
2. В накладную может быть добавлен только существующий в системе
товар
3. Количество - положительное, целое число
4. Цена приёмки - положительное, больше нуля.

Meta:
@sprint 5
@us 8.4

Scenario: Invoice product name is required

Given there is the invoice with 'InvoiceProduct-IPNIR' sku
And the user navigates to the invoice page with name 'InvoiceProduct-IPNIR'
And the user logs in as 'departmentManager'
When the user clicks the add more product button
Then the user sees error messages
| error message |
| Такого товара не существует |

Scenario: Invoice product amount is required

Given there is the product with 'Name-IPAIR' name, 'SKU-IPAIR' sku, 'BARCode-IPAIR' barcode
And there is the invoice with 'InvoiceProduct-IPAIR' sku
And the user navigates to the invoice page with name 'InvoiceProduct-IPAIR'
And the user logs in as 'departmentManager'
When the user inputs 'SKU-IPAIR' in the invoice product 'productSku' field
And the user inputs '12' in the invoice product 'invoiceCost' field
When the user clicks the add more product button
Then the user sees error messages
| error message |
| Заполните это поле |

Scenario: Invoice product price is required

Given there is the product with 'Name-IPPIR' name, 'SKU-IPPIR' sku, 'BARCode-IPPIR' barcode
And there is the invoice with 'InvoiceProduct-IPPIR' sku
And the user navigates to the invoice page with name 'InvoiceProduct-IPPIR'
And the user logs in as 'departmentManager'
When the user inputs 'SKU-IPPIR' in the invoice product 'productSku' field
And the user inputs '1' in the invoice product 'productAmount' field
And the user clicks the add more product button
Then the user sees error messages
| error message |
| Заполните это поле |

Scenario: Invoice no product name validation

Given there is the invoice with 'InvoiceProduct-INPNV-1' sku
And the user navigates to the invoice page with name 'InvoiceProduct-INPNV-1'
And the user logs in as 'departmentManager'
When the user inputs '!Лвражрварврадв-45-345' in the invoice product 'productName' field
And the user clicks the add more product button
Then the user sees error messages
| error message |
| Такого товара не существует |

Scenario: Invoice no product barcode validation

Given there is the invoice with 'InvoiceProduct-INPNV-2' sku
And the user navigates to the invoice page with name 'InvoiceProduct-INPNV-2'
And the user logs in as 'departmentManager'
When the user inputs '!Лвражрварврадв-45-345' in the invoice product 'productBarCode' field
And the user clicks the add more product button
Then the user sees error messages
| error message |
| Такого товара не существует |

Scenario: Invoice no product sku validation

Given there is the invoice with 'InvoiceProduct-INPNV-INPSV' sku
And the user navigates to the invoice page with name 'InvoiceProduct-INPNV-INPSV'
And the user logs in as 'departmentManager'
When the user inputs '!Лвражрварврадв-45-345' in the invoice product 'productSku' field
And the user clicks the add more product button
Then the user sees error messages
| error message |
| Такого товара не существует |

Scenario: Invoice product amount validation sub zero

Given there is the product with 'Name-IPAVSZ' name, 'SKU-IPAVSZ' sku, 'BARCode-IPAVSZ' barcode
And there is the invoice with 'InvoiceProduct-IPAVSZ' sku
And the user navigates to the invoice page with name 'InvoiceProduct-IPAVSZ'
And the user logs in as 'departmentManager'
When the user inputs 'SKU-IPAVSZ' in the invoice product 'productSku' field
And the user inputs '12' in the invoice product 'invoiceCost' field
When the user inputs '-10' in the invoice product 'productAmount' field
And the user clicks the add more product button
Then the user sees error messages
| error message |
| Значение должно быть больше 0 |

Scenario: Invoice product amount validation zero

Given there is the product with 'Name-IPAVZ' name, 'SKU-IPAVZ' sku, 'BARCode-IPAVZ' barcode
And there is the invoice with 'InvoiceProduct-IPAVZ' sku
And the user navigates to the invoice page with name 'InvoiceProduct-IPAVZ'
And the user logs in as 'departmentManager'
When the user inputs 'SKU-IPAVZ' in the invoice product 'productSku' field
And the user inputs '12' in the invoice product 'invoiceCost' field
When the user inputs '0' in the invoice product 'productAmount' field
And the user clicks the add more product button
Then the user sees error messages
| error message |
| Значение должно быть больше 0 |

Scenario: Invoice product amount validation String en small register

Given there is the product with 'Name-IPAVSESR' name, 'SKU-IPAVSESR' sku, 'BARCode-IPAVSESR' barcode
And there is the invoice with 'InvoiceProduct-IPAVSESR' sku
And the user navigates to the invoice page with name 'InvoiceProduct-IPAVSESR'
And the user logs in as 'departmentManager'
When the user inputs 'SKU-IPAVSESR' in the invoice product 'productSku' field
And the user inputs '12' in the invoice product 'invoiceCost' field
When the user inputs 'asdd' in the invoice product 'productAmount' field
And the user clicks the add more product button
Then the user sees error messages
| error message |
| Значение должно быть целым числом |

Scenario: Invoice product amount validation String en big register

Given there is the product with 'Name-IPAVSEBR' name, 'SKU-IPAVSEBR' sku, 'BARCode-IPAVSEBR' barcode
And there is the invoice with 'InvoiceProduct-IPAVSEBR' sku
And the user navigates to the invoice page with name 'InvoiceProduct-IPAVSEBR'
And the user logs in as 'departmentManager'
When the user inputs 'SKU-IPAVSEBR' in the invoice product 'productSku' field
And the user inputs '12' in the invoice product 'invoiceCost' field
When the user inputs 'ADHF' in the invoice product 'productAmount' field
And the user clicks the add more product button
Then the user sees error messages
| error message |
| Значение должно быть целым числом |

Scenario: Invoice product amount validation String rus small register

Given there is the product with 'Name-IPAVSRSR' name, 'SKU-IPAVSRSR' sku, 'BARCode-IPAVSRSR' barcode
And there is the invoice with 'InvoiceProduct-IPAVSRSR' sku
And the user navigates to the invoice page with name 'InvoiceProduct-IPAVSRSR'
And the user logs in as 'departmentManager'
When the user inputs 'SKU-IPAVSRSR' in the invoice product 'productSku' field
And the user inputs '12' in the invoice product 'invoiceCost' field
When the user inputs 'домик' in the invoice product 'productAmount' field
And the user clicks the add more product button
Then the user sees error messages
| error message |
| Значение должно быть целым числом |

Scenario: Invoice product amount validation String rus big register\

Given there is the product with 'Name-IPAVSRBR' name, 'SKU-IPAVSRBR' sku, 'BARCode-IPAVSRBR' barcode
And there is the invoice with 'InvoiceProduct-IPAVSRBR' sku
And the user navigates to the invoice page with name 'InvoiceProduct-IPAVSRBR'
And the user logs in as 'departmentManager'
When the user inputs 'SKU-IPAVSRBR' in the invoice product 'productSku' field
And the user inputs '12' in the invoice product 'invoiceCost' field
When the user inputs 'Домище' in the invoice product 'productAmount' field
And the user clicks the add more product button
Then the user sees error messages
| error message |
| Значение должно быть целым числом |

Scenario: Invoice product amount validation symbols

Given there is the product with 'Name-IPAVS' name, 'SKU-IPAVS' sku, 'BARCode-IPAVS' barcode
And there is the invoice with 'InvoiceProduct-IPAVS' sku
And the user navigates to the invoice page with name 'InvoiceProduct-IPAVS'
And the user logs in as 'departmentManager'
When the user inputs 'SKU-IPAVS' in the invoice product 'productSku' field
And the user inputs '12' in the invoice product 'invoiceCost' field
When the user inputs '^%#$)&' in the invoice product 'productAmount' field
And the user clicks the add more product button
Then the user sees error messages
| error message |
| Значение должно быть целым числом |

Scenario: Invoice product Amount positive validation

Given there is the product with 'Name-IPAPV' name, 'SKU-IPAPV' sku, 'BARCode-IPAPV' barcode
And there is the invoice with 'InvoiceProduct-IPAPV' sku
And the user navigates to the invoice page with name 'InvoiceProduct-IPAPV'
And the user logs in as 'departmentManager'
When the user inputs 'SKU-IPAPV' in the invoice product 'productSku' field
And the user inputs '12' in the invoice product 'invoiceCost' field
When the user inputs '1' in the invoice product 'productAmount' field
And the user clicks the add more product button
Then the user sees no error messages

Scenario: Invoice product price validation commma

Given there is the product with 'Name-IPPVC' name, 'SKU-IPPVC' sku, 'BARCode-IPPVC' barcode
And there is the invoice with 'InvoiceProduct-IPPVC' sku
And the user navigates to the invoice page with name 'InvoiceProduct-IPPVC'
And the user logs in as 'departmentManager'
When the user inputs 'SKU-IPPVC' in the invoice product 'productSku' field
And the user inputs '1' in the invoice product 'productAmount' field
And the user inputs ',78' in the invoice product 'invoiceCost' field
And the user clicks the add more product button
Then the user sees no error messages

Scenario: Invoice product price validation dott

Given there is the product with 'Name-IPPVD' name, 'SKU-IPPVD' sku, 'BARCode-IPPVD' barcode
And there is the invoice with 'InvoiceProduct-IPPVD' sku
And the user navigates to the invoice page with name 'InvoiceProduct-IPPVD'
And the user logs in as 'departmentManager'
When the user inputs 'SKU-IPPVD' in the invoice product 'productSku' field
And the user inputs '1' in the invoice product 'productAmount' field
And the user inputs '.78' in the invoice product 'invoiceCost' field
And the user clicks the add more product button
Then the user sees no error messages

Scenario: Invoice product price validation comma

Given there is the product with 'Name-IPPVC1' name, 'SKU-IPPVC1' sku, 'BARCode-IPPVC1' barcode
And there is the invoice with 'InvoiceProduct-IPPVC1' sku
And the user navigates to the invoice page with name 'InvoiceProduct-IPPVC1'
And the user logs in as 'departmentManager'
When the user inputs 'SKU-IPPVC1' in the invoice product 'productSku' field
And the user inputs '1' in the invoice product 'productAmount' field
And the user inputs '123.25' in the invoice product 'invoiceCost' field
And the user clicks the add more product button
Then the user sees no error messages

Scenario: Invoice product price validation dot

Given there is the product with 'Name-IPPVD1' name, 'SKU-IPPVD1' sku, 'BARCode-IPPVD1' barcode
And there is the invoice with 'InvoiceProduct-IPPVD1' sku
And the user navigates to the invoice page with name 'InvoiceProduct-IPPVD1'
And the user logs in as 'departmentManager'
When the user inputs 'SKU-IPPVD1' in the invoice product 'productSku' field
And the user inputs '1' in the invoice product 'productAmount' field
And the user inputs '12.56' in the invoice product 'invoiceCost' field
And the user clicks the add more product button
Then the user sees no error messages

Scenario: Invoice product price validation one digit

Given there is the product with 'Name-IPPVOD' name, 'SKU-IPPVOD' sku, 'BARCode-IPPVOD' barcode
And there is the invoice with 'InvoiceProduct-IPPVOD' sku
And the user navigates to the invoice page with name 'InvoiceProduct-IPPVOD'
And the user logs in as 'departmentManager'
When the user inputs 'SKU-IPPVOD' in the invoice product 'productSku' field
And the user inputs '1' in the invoice product 'productAmount' field
And the user inputs '2' in the invoice product 'invoiceCost' field
And the user clicks the add more product button
Then the user sees no error messages

Scenario: Invoice product price validation two digits

Given there is the product with 'Name-IPPVTWOD' name, 'SKU-IPPVTWOD' sku, 'BARCode-IPPVTWOD' barcode
And there is the invoice with 'InvoiceProduct-IPPVTWOD' sku
And the user navigates to the invoice page with name 'InvoiceProduct-IPPVTWOD'
And the user logs in as 'departmentManager'
When the user inputs 'SKU-IPPVTWOD' in the invoice product 'productSku' field
And the user inputs '1' in the invoice product 'productAmount' field
And the user inputs '99' in the invoice product 'invoiceCost' field
And the user clicks the add more product button
Then the user sees no error messages

Scenario: Invoice product price validation three digits

Given there is the product with 'Name-IPPVTD' name, 'SKU-IPPVTD' sku, 'BARCode-IPPVTD' barcode
And there is the invoice with 'InvoiceProduct-IPPVTD' sku
And the user navigates to the invoice page with name 'InvoiceProduct-IPPVTD'
And the user logs in as 'departmentManager'
When the user inputs 'SKU-IPPVTD' in the invoice product 'productSku' field
And the user inputs '1' in the invoice product 'productAmount' field
And the user inputs '12,123' in the invoice product 'invoiceCost' field
And the user clicks the add more product button
Then the user sees error messages
| error message |
| Цена не должна содержать больше 2 цифр после запятой |

Scenario: Invoice product price validation sub zero

Given there is the product with 'Name-IPPVSZ' name, 'SKU-IPPVSZ' sku, 'BARCode-IPPVSZ' barcode
And there is the invoice with 'InvoiceProduct-IPPVSZ' sku
And the user navigates to the invoice page with name 'InvoiceProduct-IPPVSZ'
And the user logs in as 'departmentManager'
When the user inputs 'SKU-IPPVSZ' in the invoice product 'productSku' field
And the user inputs '1' in the invoice product 'productAmount' field
And the user inputs '-1' in the invoice product 'invoiceCost' field
And the user clicks the add more product button
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю |

Scenario: Invoice product price validation zero

Given there is the product with 'Name-IPPVZ' name, 'SKU-IPPVZ' sku, 'BARCode-IPPVZ' barcode
And there is the invoice with 'InvoiceProduct-IPPVZ' sku
And the user navigates to the invoice page with name 'InvoiceProduct-IPPVZ'
And the user logs in as 'departmentManager'
When the user inputs 'SKU-IPPVZ' in the invoice product 'productSku' field
And the user inputs '1' in the invoice product 'productAmount' field
And the user inputs '0' in the invoice product 'invoiceCost' field
And the user clicks the add more product button
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю |

Scenario: Invoice product price validation String en small register

Given there is the product with 'Name-IPPVSESR' name, 'SKU-IPPVSESR' sku, 'BARCode-IPPVSESR' barcode
And there is the invoice with 'InvoiceProduct-IPPVSESR' sku
And the user navigates to the invoice page with name 'InvoiceProduct-IPPVSESR'
And the user logs in as 'departmentManager'
When the user inputs 'SKU-IPPVSESR' in the invoice product 'productSku' field
And the user inputs '1' in the invoice product 'productAmount' field
And the user inputs 'harry' in the invoice product 'invoiceCost' field
And the user clicks the add more product button
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю |

Scenario: Invoice product price validation String en big register

Given there is the product with 'Name-IPPVSEBR' name, 'SKU-IPPVSEBR' sku, 'BARCode-IPPVSEBR' barcode
And there is the invoice with 'InvoiceProduct-IPPVSEBR' sku
And the user navigates to the invoice page with name 'InvoiceProduct-IPPVSEBR'
And the user logs in as 'departmentManager'
When the user inputs 'SKU-IPPVSEBR' in the invoice product 'productSku' field
And the user inputs '1' in the invoice product 'productAmount' field
And the user inputs 'HARRY' in the invoice product 'invoiceCost' field
And the user clicks the add more product button
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю |

Scenario: Invoice product price validation String rus small register

Given there is the product with 'Name-IPPVSRSR' name, 'SKU-IPPVSRSR' sku, 'BARCode-IPPVSRSR' barcode
And there is the invoice with 'InvoiceProduct-IPPVSRSR' sku
And the user navigates to the invoice page with name 'InvoiceProduct-IPPVSRSR'
And the user logs in as 'departmentManager'
When the user inputs 'SKU-IPPVSRSR' in the invoice product 'productSku' field
And the user inputs '1' in the invoice product 'productAmount' field
And the user inputs 'цена' in the invoice product 'invoiceCost' field
And the user clicks the add more product button
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю |

Scenario: Invoice product price validation String rus big register

Given there is the product with 'Name-IPPVSRBR' name, 'SKU-IPPVSRBR' sku, 'BARCode-IPPVSRBR' barcode
And there is the invoice with 'InvoiceProduct-IPPVSRBR' sku
And the user navigates to the invoice page with name 'InvoiceProduct-IPPVSRBR'
And the user logs in as 'departmentManager'
When the user inputs 'SKU-IPPVSRBR' in the invoice product 'productSku' field
And the user inputs '1' in the invoice product 'productAmount' field
And the user inputs 'Цена' in the invoice product 'invoiceCost' field
And the user clicks the add more product button
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю |

Scenario: Invoice product price validation symbols

Given there is the product with 'Name-IPPVS' name, 'SKU-IPPVS' sku, 'BARCode-IPPVS' barcode
And there is the invoice with 'InvoiceProduct-IPPVS' sku
And the user navigates to the invoice page with name 'InvoiceProduct-IPPVS'
And the user logs in as 'departmentManager'
When the user inputs 'SKU-IPPVS' in the invoice product 'productSku' field
And the user inputs '1' in the invoice product 'productAmount' field
And the user inputs '@#$#$#' in the invoice product 'invoiceCost' field
And the user clicks the add more product button
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю |

Scenario: Invoice product price validation length good

Given there is the product with 'Name-IPPVLG' name, 'SKU-IPPVLG' sku, 'BARCode-IPPVLG' barcode
And there is the invoice with 'InvoiceProduct-IPPVLG' sku
And the user navigates to the invoice page with name 'InvoiceProduct-IPPVLG'
And the user logs in as 'departmentManager'
When the user inputs 'SKU-IPPVLG' in the invoice product 'productSku' field
And the user inputs '1' in the invoice product 'productAmount' field
And the user inputs '10000000' in the invoice product 'invoiceCost' field
And the user clicks the add more product button
Then the user sees no error messages
| error message |
| Цена не должна быть меньше или равна нулю |

Scenario: Invoice product price validation length negative

Given there is the product with 'Name-IPPVLN' name, 'SKU-IPPVLN' sku, 'BARCode-IPPVLN' barcode
And there is the invoice with 'InvoiceProduct-IPPVLN' sku
And the user navigates to the invoice page with name 'InvoiceProduct-IPPVLN'
And the user logs in as 'departmentManager'
When the user inputs 'SKU-IPPVLN' in the invoice product 'productSku' field
And the user inputs '1' in the invoice product 'productAmount' field
And the user inputs '10000001' in the invoice product 'invoiceCost' field
And the user clicks the add more product button
Then the user sees error messages
| error message |
| Цена не должна быть больше 10000000 |


