Meta:
@sprint_16
@us_28

Scenario: Create product retail price range validation positive

Given the user is on the product create page
And the user logs in as 'commercialManager'

When the user inputs values in element fields
| elementName | value |
| purchasePrice | 0,01 |
And the user clicks 'retailPriceHint' to make it avalaible
And the user inputs values in element fields
| elementName | value |
| name | RP-PPV-07 |
| unit | unit |
| vat | 10 |
| retailPriceMax | 10000000 |
| retailPriceMin | 0,01 |
And the user inputs <inputText> in <elementName> field
And the user clicks the create button
Then the user sees no error messages

Examples:
| inputText | elementName |
| ,78 | retailPriceMin |
| .78 | retailPriceMin |
| 123.25 | retailPriceMin |
| 125,26 | retailPriceMin |
| 789,6 | retailPriceMin |
| 739,67 | retailPriceMin |
| 739,67 | retailPriceMin |
| 10000000 | retailPriceMin |
| ,78 | retailPriceMax |
| .78 | retailPriceMax |
| 123.25 | retailPriceMax |
| 125,26 | retailPriceMax |
| 789,6 | retailPriceMax |
| 739,67 | retailPriceMax |
| 739,67 | retailPriceMax |
| 10000000 | retailPriceMax |
| 1 102,23 | retailPriceMax |
| 1 102,23 | retailPriceMin |


Scenario: Create product retail price range validation negative

Given the user is on the product create page
And the user logs in as 'commercialManager'
When the user inputs values in element fields
| elementName | value |
| purchasePrice | 0,01 |
When the user clicks 'retailPriceHint' to make it avalaible
And the user inputs values in element fields
| elementName | value |
| name | RP-PPV-06 |
| unit | unit |
| vat | 10 |
| retailPriceMax | 100 |
| retailPriceMin | 0,01 |
And the user inputs <inputText> in <elementName> field
And the user clicks the create button
Then the user user sees <errorMessage>

Examples:
| inputText | elementName | errorMessage |
| '%^#$Fgbdf345) | retailPriceMin | Значение должно быть числом |
| 739,678 | retailPriceMin | Цена не должна содержать больше 2 цифр после запятой |
| -1 | retailPriceMin | Цена не должна быть меньше или равна нулю. Цена продажи должна быть больше или равна цене закупки |
| 0 | retailPriceMin | Цена не должна быть меньше или равна нулю. Цена продажи должна быть больше или равна цене закупки |
| big price | retailPriceMin | Значение должно быть числом |
| BIG PRICE | retailPriceMin | Значение должно быть числом |
| большая цена | retailPriceMin | Значение должно быть числом |
| БОЛЬШАЯ | retailPriceMin | Значение должно быть числом |
| 10000001 | retailPriceMin | Цена не должна быть больше 10000000 |
| '%^#$Fgbdf345) | retailPriceMax | Значение должно быть числом |
| 739,678 | retailPriceMax | Цена не должна содержать больше 2 цифр после запятой |
| -1 | retailPriceMax | Цена не должна быть меньше или равна нулю. Цена продажи должна быть больше или равна цене закупки |
| 0 | retailPriceMax | Цена не должна быть меньше или равна нулю. Цена продажи должна быть больше или равна цене закупки |
| big price | retailPriceMax | Значение должно быть числом |
| BIG PRICE | retailPriceMax | Значение должно быть числом |
| большая цена | retailPriceMax | Значение должно быть числом |
| БОЛЬШАЯ | retailPriceMax | Значение должно быть числом |
| 10000001 | retailPriceMax | Цена не должна быть больше 10000000 |
|  | retailPriceMax | Заполните это поле |
|  | retailPriceMin | Заполните это поле |

Scenario: Create product retail price min cant be more then max

Given the user is on the product create page
And the user logs in as 'commercialManager'
When the user inputs 'RP-PPV-06' in 'name' field
And the user selects '10' in 'vat' dropdown
And the user inputs '1' in 'purchasePrice' field
And the user clicks 'retailPriceHint' to make it avalaible
And the user inputs '1' in 'retailPriceMax' field
And the user inputs '2' in 'retailPriceMin' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Минимальная цена продажи не должна быть больше максимальной |

Scenario: Create product retail price make retail mark up below zero

Given the user is on the product create page
And the user logs in as 'commercialManager'
When the user inputs 'RP-PPV-06' in 'name' field
And the user selects '10' in 'vat' dropdown
And the user inputs '1' in 'purchasePrice' field
And the user clicks 'retailPriceHint' to make it avalaible
And the user inputs '2' in 'retailPriceMax' field
And the user inputs '0,5' in 'retailPriceMin' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Цена продажи должна быть больше или равна цене закупки |

Scenario: Edit product retail price range validation positive

Given the user navigates to the product with name
And the user logs in as 'commercialManager'
When the user clicks the edit button on product card view page
And the user clicks 'retailPriceHint' to make it avalaible
And the user inputs values in element fields
| elementName | value |
| retailPriceMax | 10000000 |
| retailPriceMin | 0,01 |
And the user inputs <inputText> in <elementName> field
And the user clicks the create button
Then the user sees no error messages

Examples:
| name | inputText | elementName |
| RP1-PPV-07 | ,78 | retailPriceMin |
| RP1-PPV-09 | .78 | retailPriceMin |
| RP1-PPV-10 | 123.25 | retailPriceMin |
| RP1-PPV-11 | 125,26 | retailPriceMin |
| RP1-PPV-12 | 789,6 | retailPriceMin |
| RP1-PPV-13 | 739,67 | retailPriceMin |
| RP1-PPV-15 | 10000000 | retailPriceMin |
| RP1-PPV-16 | ,78 | retailPriceMax |
| RP1-PPV-17 | .78 | retailPriceMax |
| RP1-PPV-18 | 123.25 | retailPriceMax |
| RP1-PPV-19 | 125,26 | retailPriceMax |
| RP1-PPV-20 | 789,6 | retailPriceMax |
| RP1-PPV-21 | 739,67 | retailPriceMax |
| RP1-PPV-23 | 10000000 | retailPriceMax |
| RP1-PPV-24 | 1 102,78 | retailPriceMin |
| RP1-PPV-25 | 1 102,78 | retailPriceMax |

Scenario: Edit product retail price range validation negative

Given the user navigates to the product with random name
And the user logs in as 'commercialManager'
When the user clicks the edit button on product card view page
And the user clicks 'retailPriceHint' to make it avalaible
And the user inputs values in element fields
| elementName | value |
| retailPriceMax | 10000000 |
| retailPriceMin | 0,01 |
And the user inputs <inputText> in <elementName> field
And the user clicks the create button
Then the user user sees <errorMessage>

Examples:
| inputText | elementName | errorMessage |
| '%^#$Fgbdf345) | retailPriceMin | Значение должно быть числом |
| 739,678 | retailPriceMin | Цена не должна содержать больше 2 цифр после запятой |
| -1 | retailPriceMin | Цена не должна быть меньше или равна нулю. Цена продажи должна быть больше или равна цене закупки |
| 0 | retailPriceMin | Цена не должна быть меньше или равна нулю. Цена продажи должна быть больше или равна цене закупки |
| big price | retailPriceMin | Значение должно быть числом |
| BIG PRICE | retailPriceMin | Значение должно быть числом |
| большая цена | retailPriceMin | Значение должно быть числом |
| БОЛЬШАЯ | retailPriceMin | Значение должно быть числом |
| 10000001 | retailPriceMin | Цена не должна быть больше 10000000 |
| '%^#$Fgbdf345) | retailPriceMax | Значение должно быть числом |
| 739,678 | retailPriceMax | Цена не должна содержать больше 2 цифр после запятой |
| -1 | retailPriceMax | Цена не должна быть меньше или равна нулю. Цена продажи должна быть больше или равна цене закупки |
| 0 | retailPriceMax | Цена не должна быть меньше или равна нулю. Цена продажи должна быть больше или равна цене закупки |
| big price | retailPriceMax | Значение должно быть числом |
| BIG PRICE | retailPriceMax | Значение должно быть числом |
| большая цена | retailPriceMax | Значение должно быть числом |
| БОЛЬШАЯ | retailPriceMax | Значение должно быть числом |
| 10000001 | retailPriceMax | Цена не должна быть больше 10000000 |
|  | retailPriceMax | Заполните это поле |
|  | retailPriceMin | Заполните это поле |

Scenario: Edit product retail price min cant be more then max

Given there is the product with 'RMU12-PPV1-02' name, 'RMU12-PPV1-02' barcode
And the user navigates to the product with name 'RMU12-PPV1-02'
And the user logs in as 'commercialManager'
When the user clicks the edit button on product card view page
And the user clicks 'retailPriceHint' to make it avalaible
And the user inputs '1' in 'retailPriceMax' field
And the user inputs '2' in 'retailPriceMin' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Цена продажи должна быть больше или равна цене закупки, Цена продажи должна быть больше или равна цене закупки |