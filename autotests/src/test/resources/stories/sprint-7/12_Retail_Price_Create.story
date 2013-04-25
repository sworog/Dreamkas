12 Создание розничной цены на продажу

Narrative:
Как коммерческий директор,
Я хочу задать розничную цену на конкретный товар,
Чтобы покрыть издержки и получить прибыль при продаже этого товара.

Scenario: Retail price - product creation without retail price filling
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'Наименование56' in 'name' field
And the user inputs '12356' in 'purchasePrice' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs 'Retail price - PCWRPR-1' in 'sku' field
And the user clicks the create button
Then the user checks the product with 'Retail price - PCWRPR-1' sku is present
When the user open the product card with 'Retail price - PCWRPR-1' sku
Then the user checks the elements values
| elementName | expectedValue |
| purchasePrice | 12356 |
| retailMarkup | отсутствует |
| retailPrice | отсутствует |

Scenario: Retail price - product creation with markup filling
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'Наименование56' in 'name' field
And the user inputs '112' in 'purchasePrice' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs 'Retail price - PCWMF' in 'sku' field
And the user inputs '10' in 'retailMarkup' field
Then the user checks the 'retailPriceHint' value is '123,2'
When the user clicks the create button
Then the user checks the product with 'Retail price - PCWMF' sku is present
When the user open the product card with 'Retail price - PCWMF' sku
Then the user checks the elements values
| elementName | expectedValue |
| purchasePrice | 112 |
| retailMarkup | 10 |
| retailPrice | 123,2 |

Scenario: Retail price - product creation with retailMarkup filling
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'Наименование56' in 'name' field
And the user inputs '112' in 'purchasePrice' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs 'Retail price - PCWRMF' in 'sku' field
And the user inputs '10' in 'retailMarkup' field
Then the user checks the 'retailPriceHint' value is '123,2'
When the user clicks the create button
Then the user checks the product with 'Retail price - PCWRMF' sku is present
When the user open the product card with 'Retail price - PCWRMF' sku
Then the user checks the elements values
| elementName | expectedValue |
| purchasePrice | 112 |
| retailMarkup | 10 |
| retailPrice | 123,2 |

Scenario: Retail price - product creation with retailPrice filling
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'Наименование56' in 'name' field
And the user inputs '112' in 'purchasePrice' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs 'Retail price - PCWRPF' in 'sku' field
And the user clicks 'retailPriceHint' to make it avalaible
Then the user checks 'retailMarkup' 'is not' avalaible
And the user checks 'retailPrice' 'is' avalaible
Then the user checks 'retailMarkupHint' 'is' avalaible
And the user checks 'retailPriceHint' 'is not' avalaible
When the user inputs '140' in 'retailPrice' field
Then the user checks the 'retailMarkupHint' value is '25'
When the user clicks the create button
Then the user checks the product with 'Retail price - PCWRPF' sku is present
When the user open the product card with 'Retail price - PCWRPF' sku
Then the user checks the elements values
| elementName | expectedValue |
| purchasePrice | 112 |
| retailMarkup | 25 |
| retailPrice | 140 |

Scenario: Retail price - retailMarkup - last used field is active in product edition
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'Наименование56' in 'name' field
And the user inputs '112' in 'purchasePrice' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs 'Retailprice-RM-LUFIAOPE' in 'sku' field
When the user inputs '140' in 'retailMarkup' field
When the user clicks the create button
Then the user checks the product with 'Retailprice-RM-LUFIAOPE' sku is present
When the user open the product card with 'Retailprice-RM-LUFIAOPE' sku
And the user clicks the edit button on product card view page
Then the user checks the elements values
| elementName | expectedValue |
| retailPriceHint | 268,8 |
And the user checks 'retailPrice' 'is not' avalaible
And the user checks 'retailMarkup' 'is' avalaible
And the user checks 'retailPriceHint' 'is' avalaible
And the user checks 'retailMarkupHint' 'is not' avalaible

Scenario: Retail price - retailPrice - last used field is active in product edition
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'Наименование56' in 'name' field
And the user inputs '112' in 'purchasePrice' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs 'Retailprice-RP-LUFIAIPE' in 'sku' field
And the user clicks 'retailPriceHint' to make it avalaible
When the user inputs '140' in 'retailPrice' field
When the user clicks the create button
Then the user checks the product with 'Retailprice-RP-LUFIAIPE' sku is present
When the user open the product card with 'Retailprice-RP-LUFIAIPE' sku
And the user clicks the edit button on product card view page
Then the user checks the elements values
| elementName | expectedValue |
| retailPrice | 140 |
And the user checks 'retailPrice' 'is' avalaible
And the user checks 'retailMarkup' 'is not' avalaible
And the user checks 'retailMarkupHint' 'is' avalaible
And the user checks 'retailPriceHint' 'is not' avalaible

Scenario: Retail price - retailMarkup - last used field is active in product edition all
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'Наименование56' in 'name' field
And the user inputs '112' in 'purchasePrice' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs 'Retailprice-RM-LUFIAOPE-all' in 'sku' field
When the user inputs '140' in 'retailMarkup' field
When the user clicks the create button
Then the user checks the product with 'Retailprice-RM-LUFIAOPE-all' sku is present
When the user open the product card with 'Retailprice-RM-LUFIAOPE-all' sku
And the user clicks the edit button on product card view page
Then the user checks the elements values
| elementName | expectedValue |
| retailPriceHint | 268,8 |
And the user checks 'retailPrice' 'is not' avalaible
And the user checks 'retailMarkup' 'is' avalaible
And the user checks 'retailPriceHint' 'is' avalaible
And the user checks 'retailMarkupHint' 'is not' avalaible
When the user clicks 'retailPriceHint' to make it avalaible
When the user inputs '140' in 'retailPrice' field
When the user clicks the create button
Then the user checks the product with 'Retailprice-RM-LUFIAOPE-all' sku is present
When the user open the product card with 'Retailprice-RM-LUFIAOPE-all' sku
And the user clicks the edit button on product card view page
Then the user checks the elements values
| elementName | expectedValue |
| retailPrice | 140 |
And the user checks 'retailMarkup' 'is not' avalaible
And the user checks 'retailPrice' 'is' avalaible
And the user checks 'retailMarkupHint' 'is' avalaible
And the user checks 'retailPriceHint' 'is not' avalaible

Scenario: Retail price - retailPrice is active by default
Given the user is on the product list page
When the user creates new product from product list page
Then the user checks 'retailMarkup' 'is' avalaible
And the user checks 'retailPrice' 'is not' avalaible
And the user checks 'retailMarkupHint' 'is not' avalaible
And the user checks 'retailPriceHint' 'is' avalaible

Scenario: Retail price - retail price hint text
Given the user is on the product list page
When the user creates new product from product list page
Then the user checks the elements values
| elementName | expectedValue |
| retailPriceHint | Введите значение |

Scenario: Retail price - retail markup hint text
Given the user is on the product list page
When the user creates new product from product list page
When the user clicks 'retailPriceHint' to make it avalaible
Then the user checks the elements values
| elementName | expectedValue |
| retailMarkupHint | Введите значение |

Scenario: Retail price - retail price correct round testing
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'Наименование56' in 'name' field
And the user inputs '112' in 'purchasePrice' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs 'Retailprice-PCWRMF' in 'sku' field
And the user inputs '140' in 'retailMarkup' field
Then the user checks the 'retailPriceHint' value is '268,80'

Scenario: Retail price - retail markup correct round testing
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'Наименование56' in 'name' field
And the user inputs '112' in 'purchasePrice' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs 'Retail price - PCWRPF' in 'sku' field
And the user clicks 'retailPriceHint' to make it avalaible
When the user inputs '268,8' in 'retailPrice' field
Then the user checks the 'retailMarkupHint' value is '140'

Scenario: Retail price regress 1
Given the user is on the product list page
When the user creates new product from product list page
And the user inputs 'Наименование56' in 'name' field
And the user inputs '112' in 'purchasePrice' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs 'Retailprice-RM-LUFIAOPE-all1' in 'sku' field
When the user inputs '23' in 'retailMarkup' field
When the user clicks the create button
Then the user checks the product with 'Retailprice-RM-LUFIAOPE-all1' sku is present
When the user open the product card with 'Retailprice-RM-LUFIAOPE-all1' sku
And the user clicks the edit button on product card view page
When the user clicks 'retailPriceHint' to make it avalaible
Then the user checks the elements values
| elementName | expectedValue |
| retailPrice | 137,76 |






