Meta:
@sprint_16
@us_28

Scenario: Create product mark up validation negative

Given the user is on the product create page
And the user logs in as 'commercialManager'
When the user inputs values in element fields
| elementName | value |
| name |RMU-PPV-01 |
| vat | 10 |
| purchasePrice | 1 |
| retailMarkupMax | 100 |
| retailMarkupMin | 0 |
And the user inputs <inputText> in <elementName> field
And the user clicks the create button
Then the user user sees <errorMessage>

Examples:
| inputText | elementName | errorMessage |
|-1 | retailMarkupMin | Наценка должна быть равна или больше 0% |
|-0.01 | retailMarkupMin | Наценка должна быть равна или больше 0% |
| 12,678 | retailMarkupMin | Значение не должно содержать больше 2 цифр после запятой |
| -12,678 | retailMarkupMin | Значение не должно содержать больше 2 цифр после запятой. Наценка должна быть равна или больше 0% |
| big price | retailMarkupMin | Значение должно быть числом |
| BIG PRICE | retailMarkupMin | Значение должно быть числом |
| большая цена | retailMarkupMin | Значение должно быть числом |
| БОЛЬШАЯ ЦЕНА | retailMarkupMin | Значение должно быть числом |
| !@#$%^&*() | retailMarkupMin | Значение должно быть числом |
| 120 | retailMarkupMin | Минимальная наценка не должна быть больше максимальной |
|-1 | retailMarkupMax | Наценка должна быть равна или больше 0% |
|-0.01 | retailMarkupMax | Наценка должна быть равна или больше 0% |
| 12,678 | retailMarkupMax | Значение не должно содержать больше 2 цифр после запятой |
| -12,678 | retailMarkupMax | Значение не должно содержать больше 2 цифр после запятой. Наценка должна быть равна или больше 0% |
| big price | retailMarkupMax | Значение должно быть числом |
| BIG PRICE | retailMarkupMax | Значение должно быть числом |
| большая цена | retailMarkupMax | Значение должно быть числом |
| БОЛЬШАЯ ЦЕНА | retailMarkupMax | Значение должно быть числом |
| !@#$%^&*() | retailMarkupMax | Значение должно быть числом |
|  | retailMarkupMin | Заполните это поле |
|  | retailMarkupMax | Заполните это поле |

Scenario: Create product mark up validation positive

Given the user is on the product create page
And the user logs in as 'commercialManager'

When the user inputs values in element fields
| elementName | value |
| name |RMU-PPV-02 |
| vat | 10 |
| purchasePrice | 1 |
| retailMarkupMax | 100 |
| retailMarkupMin | 0 |
And the user inputs <inputText> in <elementName> field
And the user clicks the create button
Then the user sees no error messages

Examples:
| inputText | elementName |
| 0 | retailMarkupMin |
| 0 | retailMarkupMax |
| 10,6 | retailMarkupMin |
| 10,6 | retailMarkupMax|
| 12,67 | retailMarkupMin |
| 12,67 | retailMarkupMax |

Scenario: Create product min mark up can't be more than max

Given the user is on the product create page
And the user logs in as 'commercialManager'
When the user inputs 'RMU-PPV-01' in 'name' field
And the user selects '10' in 'vat' dropdown
And the user inputs '1' in 'purchasePrice' field
And the user inputs '1' in 'retailMarkupMax' field
And the user inputs '2' in 'retailMarkupMin' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Минимальная наценка не должна быть больше максимальной |

Scenario: Edit product mark up validation negative

Given there is the product with 'RMU-PPV-0211' name, 'RMU-PPV-0211' barcode
And the user navigates to the product with name 'RMU-PPV-0211'
And the user logs in as 'commercialManager'
When the user clicks the edit button on product card view page
And the user inputs values in element fields
| elementName | value |
| retailMarkupMax | 100 |
| retailMarkupMin | 0 |
And the user inputs <inputText> in <elementName> field
And the user clicks the create button
Then the user user sees <errorMessage>

Examples:
| inputText | elementName | errorMessage |
|-1 | retailMarkupMin | Наценка должна быть равна или больше 0% |
|-0.01 | retailMarkupMin | Наценка должна быть равна или больше 0% |
|-1 | retailMarkupMax | Наценка должна быть равна или больше 0% |
|-0.01 | retailMarkupMax | Наценка должна быть равна или больше 0% |
| 12,678 | retailMarkupMin | Значение не должно содержать больше 2 цифр после запятой |
| 12,678 | retailMarkupMax | Значение не должно содержать больше 2 цифр после запятой |
| -12,678 | retailMarkupMax | Значение не должно содержать больше 2 цифр после запятой. Наценка должна быть равна или больше 0% |
| -12,678 | retailMarkupMin | Значение не должно содержать больше 2 цифр после запятой. Наценка должна быть равна или больше 0% |
| big price | retailMarkupMax | Значение должно быть числом |
| BIG PRICE | retailMarkupMax | Значение должно быть числом |
| большая цена | retailMarkupMax | Значение должно быть числом |
| БОЛЬШАЯ ЦЕНА | retailMarkupMax | Значение должно быть числом |
| !@#$%^&*() | retailMarkupMax | Значение должно быть числом |
| big price | retailMarkupMin | Значение должно быть числом |
| BIG PRICE | retailMarkupMin | Значение должно быть числом |
| большая цена | retailMarkupMin | Значение должно быть числом |
| БОЛЬШАЯ ЦЕНА | retailMarkupMin | Значение должно быть числом |
| !@#$%^&*() | retailMarkupMin | Значение должно быть числом |
|  | retailMarkupMin | Заполните это поле |
|  | retailMarkupMax | Заполните это поле |

Scenario: Edit product mark up validation positive

Given the user navigates to the product with name
And the user logs in as 'commercialManager'
When the user clicks the edit button on product card view page
And the user inputs values in element fields
| elementName | value |
| retailMarkupMax | 100 |
| retailMarkupMin | 0 |
And the user inputs <inputText> in <elementName> field
And the user clicks the create button
Then the user sees no error messages

Examples:
| name | inputText | elementName |
| RMU1-PPV-02 | 0 | retailMarkupMin |
| RMU1-PPV-03 | 0 | retailMarkupMax |
| RMU1-PPV-04 | 10,6 | retailMarkupMin |
| RMU1-PPV-05 | 10,6 | retailMarkupMax|
| RMU1-PPV-06 | 12,67 | retailMarkupMin |
| RMU1-PPV-07 | 12,67 | retailMarkupMax |

Scenario: Edit product min mark up can't be more than max

Given there is the product with 'RMU-PPV1-02' name, 'RMU-PPV1-02' barcode
And the user navigates to the product with name 'RMU-PPV1-02'
And the user logs in as 'commercialManager'
When the user clicks the edit button on product card view page
And the user inputs '1' in 'retailMarkupMax' field
And the user inputs '2' in 'retailMarkupMin' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Минимальная наценка не должна быть больше максимальной |

