Валидация вводимых данных при создании товара

Narrative:
Как коммерческий директор,
Я хочу чтобы, при создании нового товара,
Система сообщала мне об ошибках в вводимых данных,
Чтобы исключить возможность создать товар с заведомо некорректными данными.

Scenario: Name field length validation
Given the user is on the product list page
When the user creates new product from product list page
And the user generates charData with '300' number in the 'name' field
And the user inputs 'NFLV-879' in 'sku' field
And the user inputs '58967' in 'purchasePrice' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user clicks the create button
Then the user sees no error messages
And the user checks the product with 'NFLV-879' sku is present
And the user checks that he is on the 'ProductListPage'

Scenario: Name field length validation negative
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'NFLVN-01' in 'sku' field
And the user inputs '58967' in 'purchasePrice' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user generates charData with '301' number in the 'name' field
Then the user checks 'name' field contains only '301' symbols
When the user clicks the create button
Then the user sees error messages
| error message |
| Не более 300 символов |

Scenario: Name field length validation negative 2
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'NFLVN-01' in 'sku' field
And the user inputs '58967' in 'purchasePrice' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user generates charData with '356' number in the 'name' field
Then the user checks 'name' field contains only '356' symbols
When the user clicks the create button
Then the user sees error messages
| error message |
| Не более 300 символов |

Scenario: Name field is required
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'IFV-01' in 'sku' field
And the user inputs '58967' in 'purchasePrice' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user clicks the create button
Then the user sees error messages
| error message |
| Заполните это поле |

Scenario: Unit fiels is required
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'Unit fiels is required' in 'name' field
And the user inputs 'IFV-01' in 'sku' field
And the user inputs '58967' in 'purchasePrice' field
And the user selects '10' in 'vat' dropdown
And the user clicks the create button
Then the user sees error messages
| error message |
| Выберите единицу измерения |

Scenario: Vat field is required
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'test' in 'name' field
And the user inputs 'IFV-01' in 'sku' field
And the user inputs '58967' in 'purchasePrice' field
And the user selects 'unit' in 'unit' dropdown
And the user clicks the create button
Then the user sees error messages
| error message |
| Выберите ставку НДС |

Scenario: Barcode field length validation
Given the user is on the product list page
When the user creates new product from product list page
And the user generates charData with '200' number in the 'barcode' field
And the user inputs 'Barcode field length validation' in 'name' field
And the user inputs 'FTY6456789' in 'sku' field
And the user inputs '58967' in 'purchasePrice' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user clicks the create button
Then the user sees no error messages
And the user checks the product with 'FTY6456789' sku is present
And the user checks that he is on the 'ProductListPage'

Scenario: Barcode field length validation negative
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'Barcode field length validation' in 'name' field
And the user inputs 'FTY6456789123' in 'sku' field
And the user inputs '58967' in 'purchasePrice' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user generates charData with '201' number in the 'barcode' field
Then the user checks 'barcode' field contains only '201' symbols
When the user clicks the create button
Then the user sees error messages
| error message |
| Не более 200 символов |

Scenario: Sku field validation good
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'Sku field validation good' in 'name' field
And the user inputs '1001DS' in 'sku' field
And the user inputs '58967' in 'purchasePrice' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user clicks the create button
When the user creates new product from product list page
And the user inputs 'Sku field validation good' in 'name' field
And the user inputs '1001DS' in 'sku' field
And the user inputs '58967' in 'purchasePrice' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user clicks the create button
Then the user sees error messages
| error message |
| Такой артикул уже есть |

Scenario: Sku field negative
Given the user is on the product list page
When the user creates new product from product list page
And the user generates charData with '101' number in the 'sku' field
Then the user checks 'sku' field contains only '101' symbols
When the user clicks the create button
Then the user sees error messages
| error message |
| Не более 100 символов |

Scenario: Sku field is required
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'Sku field is required' in 'name' field
And the user inputs '58967' in 'purchasePrice' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user clicks the create button
Then the user sees error messages
| error message |
| Заполните это поле |

Scenario: Vendor,Barcode,VendorCountryInfo fields are not required
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'Vendor,Barcode,VendorCountryInfo fields are not required' in 'name' field
And the user inputs 'VBVCF678' in 'sku' field
And the user inputs '58967' in 'purchasePrice' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user clicks the create button
Then the user sees no error messages
And the user checks the product with 'VBVCF678' sku is present
And the user checks that he is on the 'ProductListPage'


Scenario: Vendor field validation
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'Vendor field validation' in 'name' field
And the user inputs 'VFV-01' in 'sku' field
And the user inputs '58967' in 'purchasePrice' field
And the user generates charData with '300' number in the 'vendor' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user clicks the create button
Then the user sees no error messages
And the user checks the product with 'VFV-01' sku is present
And the user checks that he is on the 'ProductListPage'

Scenario: Vendor field validation lenght negative
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'Vendor field validation lenght negative' in 'name' field
And the user inputs 'FTY64567891235' in 'sku' field
And the user inputs '58967' in 'purchasePrice' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user generates charData with '301' number in the 'vendor' field
Then the user checks 'vendor' field contains only '301' symbols
When the user clicks the create button
Then the user sees error messages
| error message |
| Не более 300 символов |


Scenario: VendorCountry field validation
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'VendorCountry field validation' in 'name' field
And the user inputs 'VCFV-01' in 'sku' field
And the user inputs '58967' in 'purchasePrice' field
And the user generates charData with '100' number in the 'vendorCountry' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user clicks the create button
Then the user sees no error messages
And the user checks the product with 'VCFV-01' sku is present
And the user checks that he is on the 'ProductListPage'

Scenario: VendorCountry field validation lenght negative
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'VendorCountry field validation lenght negative' in 'name' field
And the user inputs 'FTY64123' in 'sku' field
And the user inputs '58967' in 'purchasePrice' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user generates charData with '101' number in the 'vendorCountry' field
Then the user checks 'vendorCountry' field contains only '101' symbols
When the user clicks the create button
Then the user sees error messages
| error message |
| Не более 100 символов |

Scenario: Info field validation
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'Info field validation' in 'name' field
And the user inputs 'IFV-01' in 'sku' field
And the user inputs '58967' in 'purchasePrice' field
And the user generates charData with '2000' number in the 'info' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user clicks the create button
Then the user sees no error messages
And the user checks the product with 'IFV-01' sku is present
And the user checks that he is on the 'ProductListPage'

Scenario: Info field validation lenght negative
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'Info field validation lenght negative' in 'name' field
And the user inputs 'FTY64123DS' in 'sku' field
And the user inputs '58967' in 'purchasePrice' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user generates charData with '2001' number in the 'info' field
Then the user checks 'info' field contains only '2001' symbols
When the user clicks the create button
Then the user sees error messages
| error message |
| Не более 2000 символов |

Scenario: Mixing 1
Given the user is on the product list page
When the user creates new product from product list page
And the user clicks the create button
Then the user sees error messages
| error message |
| Заполните это поле |
| Заполните это поле |
| Выберите единицу измерения |
| Заполните это поле |
| Выберите ставку НДС |

Scenario: Mixing 2
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'Mixing 2' in 'name' field
And the user inputs 'JDSID45' in 'sku' field
And the user inputs '-145' in 'purchasePrice' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Выберите единицу измерения |
| Цена не должна быть меньше или равна нулю. |
| Выберите ставку НДС |


Scenario: Purchase price validation String+Symbols+Num
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'PPV-06' in 'name' field
And the user inputs 'PPV-06' in 'sku' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs '%^#$Fgbdf345)' in 'purchasePrice' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю. |

Scenario: Purchase price validation commma
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'PPV-07' in 'name' field
And the user inputs 'PPV-07' in 'sku' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs ',78' in 'purchasePrice' field
And the user clicks the create button
Then the user sees no error messages
And the user checks that he is on the 'ProductListPage'
Then the user checks the product with 'PPV-07' sku has 'purchasePrice' equal to '0,78'

Scenario: Purchase price validation dott
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'PPV-08' in 'name' field
And the user inputs 'PPV-08' in 'sku' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs ',78' in 'purchasePrice' field
And the user clicks the create button
Then the user sees no error messages
And the user checks that he is on the 'ProductListPage'
Then the user checks the product with 'PPV-08' sku has 'purchasePrice' equal to '0,78'

Scenario: Purchase price validation comma
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'purchase price comma' in 'name' field
And the user inputs 'JFGE89075' in 'sku' field
And the user inputs '123.25' in 'purchasePrice' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user clicks the create button
Then the user sees no error messages
And the user checks that he is on the 'ProductListPage'
Then the user checks the product with 'JFGE89075' sku has 'purchasePrice' equal to '123,25'

Scenario: Purchase price validation dot
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'purchase price dot' in 'name' field
And the user inputs 'JFGE89078' in 'sku' field
And the user inputs '125,26' in 'purchasePrice' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user clicks the create button
Then the user sees no error messages
And the user checks that he is on the 'ProductListPage'
Then the user checks the product with 'JFGE89078' sku has 'purchasePrice' equal to '125,26'

Scenario: Purchase price validation one digit
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'purchase price one digit' in 'name' field
And the user inputs 'FTY64' in 'sku' field
And the user inputs '789,6' in 'purchasePrice' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user clicks the create button
Then the user checks that he is on the 'ProductListPage'
And the user checks the product with 'FTY64' sku has 'purchasePrice' equal to '789,6'

Scenario: Purchase price validation two digits
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'purchase price two digits' in 'name' field
And the user inputs 'FTY645' in 'sku' field
And the user inputs '739,67' in 'purchasePrice' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user clicks the create button
Then the user checks that he is on the 'ProductListPage'
And the user checks the product with 'FTY645' sku has 'purchasePrice' equal to '739,67'

Scenario: Purchase price validation three digits
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'purchase price three digits' in 'name' field
And the user inputs 'FTY6456' in 'sku' field
And the user inputs '739,678' in 'purchasePrice' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user clicks the create button
Then the user sees error messages
| error message |
| Цена не должна содержать больше 2 цифр после запятой. |


Scenario: Purchase price field is required
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'Unit fiels is required' in 'name' field
And the user inputs 'IFV-01' in 'sku' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user clicks the create button
Then the user sees error messages
| error message |
| Заполните это поле |

Scenario: Purchase price validation sub zero
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'PPV-01' in 'name' field
And the user inputs 'PPV-01' in 'sku' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs '-152' in 'purchasePrice' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю. |

Scenario: Purhase prise validation zero
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'PPV-02' in 'name' field
And the user inputs 'PPV-02' in 'sku' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs '0' in 'purchasePrice' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю. |

Scenario: Purchase price validation String en
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'PPV-03' in 'name' field
And the user inputs 'PPV-03' in 'sku' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs 'Big price' in 'purchasePrice' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю. |

Scenario: Purchase price validation String rus
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'PPV-04' in 'name' field
And the user inputs 'PPV-04' in 'sku' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs 'Большая цена' in 'purchasePrice' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю. |

Scenario: Purchase price validation symbols
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'PPV-05' in 'name' field
And the user inputs 'PPV-05' in 'sku' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs '!@#$%^&*()' in 'purchasePrice' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю. |

Scenario: Purchase price validation length good
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'PPV-090' in 'name' field
And the user inputs 'PPV-090' in 'sku' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs '10000000' in 'purchasePrice' field
And the user clicks the create button
Then the user checks that he is on the 'ProductListPage'
And the user checks the product with 'PPV-090' sku has 'purchasePrice' equal to '10000000'

Scenario: Purchase price validation length negative
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'PPV-0941' in 'name' field
And the user inputs 'PPV-0941' in 'sku' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs '10000001' in 'purchasePrice' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Цена не должна быть больше 10000000 |
