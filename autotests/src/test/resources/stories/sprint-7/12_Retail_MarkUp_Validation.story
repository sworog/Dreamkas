
Scenario: Retail Markup validation sub zero -105
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'RMU-PPV-01' in 'name' field
And the user inputs 'RMU-PPV-01' in 'sku' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs '1' in 'purchasePrice' field
And the user inputs '-105' in 'retailMarkup' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю. |

Scenario: Retail Markup validation sub zero -100
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'RMU-PPV-01' in 'name' field
And the user inputs 'RMU-PPV-01' in 'sku' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs '1' in 'purchasePrice' field
And the user inputs '-100' in 'retailMarkup' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю. |

Scenario: Retail Markup validation sub zero -99
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'RMU-PPV-012' in 'name' field
And the user inputs 'RMU-PPV-012' in 'sku' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs '1' in 'purchasePrice' field
And the user inputs '-99' in 'retailMarkup' field
And the user clicks the create button
Then the user sees no error messages

Scenario: Retail Markup validation sub zero -99.99
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'RMU-PPV-01' in 'name' field
And the user inputs 'RMU-PPV-01' in 'sku' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs '1' in 'purchasePrice' field
And the user inputs '-99.99' in 'retailMarkup' field
And the user clicks the create button
Then the user sees no error messages

Scenario: Retail Markup validation zero
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'RMU-PPV-02' in 'name' field
And the user inputs 'RMU-PPV-02' in 'sku' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs '1' in 'purchasePrice' field
And the user inputs '0' in 'retailMarkup' field
And the user clicks the create button
Then the user sees no error messages

Scenario: Retail Markup validation one digit
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'purchase price one digit' in 'name' field
And the user inputs 'RMU-FTY64' in 'sku' field
And the user inputs '1' in 'purchasePrice' field
And the user inputs '10,6' in 'retailMarkup' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user clicks the create button
Then the user sees no error messages

Scenario: Retail Markup validation two digits
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'purchase price two digits' in 'name' field
And the user inputs 'RMU-FTY645' in 'sku' field
And the user inputs '1' in 'purchasePrice' field
And the user inputs '12,67' in 'retailMarkup' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user clicks the create button
Then the user sees no error messages

Scenario: Retail Markup validation three digits
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'purchase price three digits' in 'name' field
And the user inputs 'RMU-FTY6456' in 'sku' field
And the user inputs '1' in 'purchasePrice' field
And the user inputs '12,678' in 'retailMarkup' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user clicks the create button
Then the user sees error messages
| error message |
| Значение не должно содержать больше 2 цифр после запятой |

Scenario: Retail Markup validation String en small register
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'RP-PPV-03' in 'name' field
And the user inputs 'RP-PPV-03' in 'sku' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs '1' in 'purchasePrice' field
And the user inputs 'big price' in 'retailMarkup' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Значение должно быть числом |

Scenario: Retail Markup validation String en big register
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'RP-PPV-03' in 'name' field
And the user inputs 'RP-PPV-03' in 'sku' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs '1' in 'purchasePrice' field
And the user inputs 'BIG PRICE' in 'retailMarkup' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Значение должно быть числом |

Scenario: Retail Markup validation String rus small register
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'RP-PPV-04' in 'name' field
And the user inputs 'RP-PPV-04' in 'sku' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs '1' in 'purchasePrice' field
And the user inputs 'большая цена' in 'retailMarkup' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Значение должно быть числом |

Scenario: Retail Markup validation String rus big register
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'RP-PPV-04' in 'name' field
And the user inputs 'RP-PPV-04' in 'sku' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs '1' in 'purchasePrice' field
And the user inputs 'БОЛЬЩАЯ ЦЕНА' in 'retailMarkup' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Значение должно быть числом |

Scenario: Retail Markup validation symbols
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'RP-PPV-05' in 'name' field
And the user inputs 'RP-PPV-05' in 'sku' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs '1' in 'purchasePrice' field
And the user inputs '!@#$%^&*()' in 'retailMarkup' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Значение должно быть числом |

Scenario: Retail Markup regress
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'RP-PPV-094123' in 'name' field
And the user inputs 'RP-PPV-094123' in 'sku' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs '1' in 'purchasePrice' field
And the user inputs '-100' in 'retailMarkup' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Наценка должна быть больше -100% |
Then the user sees no error messages
| error message |
| Цена не должна быть меньше или равна нулю. |
