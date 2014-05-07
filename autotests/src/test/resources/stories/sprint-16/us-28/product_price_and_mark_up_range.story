Meta:
@sprint_16
@us_28

Narrative:
As a коммерческий директор
I want to установить диапазон наценки товаров
In order to управлять розничной ценой на товары и удерживать плановый уровень маржинальности

Scenario: Product creation without filling any ranges

Given the user is on the product create page
And the user logs in as 'commercialManager'

When the user inputs 'Retail price - PCWRPR-1' in 'name' field
And the user selects '10' in 'vat' dropdown
And the user inputs '12356' in 'purchasePrice' field
And the user clicks the create button

Then the user checks the products list contain product with name 'Retail price - PCWRPR-1'

When the user clicks on product with name 'Retail price - PCWRPR-1'

Then the user checks the elements values
| elementName | value |
| purchasePrice | 12 356,00 |
| retailMarkupRange | отсутствует |
| retailPriceRange | отсутствует |

Scenario: Product creation with markup range filling

Given the user is on the product create page
And the user logs in as 'commercialManager'
When the user inputs 'Retail price - PCWMF' in 'name' field
And the user selects '10' in 'vat' dropdown
And the user inputs '112' in 'purchasePrice' field
And the user inputs '10' in 'retailMarkupMin' field
And the user inputs '15' in 'retailMarkupMax' field
Then the user checks the 'retailPriceHint' value is '123,20 - 128,80'
When the user clicks the create button

Then the user checks the products list contain product with name 'Retail price - PCWMF'

When the user clicks on product with name 'Retail price - PCWMF'

Then the user checks the elements values
| elementName | value |
| purchasePrice | 112 |
| retailMarkupRange | 10,00 - 15,00 |
| retailPriceRange | 123,20 - 128,80 |

Scenario: Product creation with retailPriceRange range filling

Given the user is on the product create page
And the user logs in as 'commercialManager'

When the user inputs 'Retail price - PCWRPF' in 'name' field
And the user selects '10' in 'vat' dropdown
And the user inputs '100' in 'purchasePrice' field
And the user clicks 'retailPriceHint' to make it avalaible
When the user inputs '140' in 'retailPriceMin' field
And the user inputs '145' in 'retailPriceMax' field
Then the user checks the 'retailMarkupHint' value is '40,00 - 45,00%'
When the user clicks the create button

Then the user checks the products list contain product with name 'Retail price - PCWRPF'

When the user clicks on product with name 'Retail price - PCWRPF'

Then the user checks the elements values
| elementName | value |
| purchasePrice | 100 |
| retailMarkupRange | 40,00 - 45,00 |
| retailPriceRange | 140,00 - 145,00 |

Scenario: Retail mark up last used field is active in product edition

Given the user is on the product create page
And the user logs in as 'commercialManager'

When the user inputs 'Retailprice-RM-LUFIAOPE' in 'name' field
And the user selects '10' in 'vat' dropdown
And the user inputs '100' in 'purchasePrice' field
And the user inputs '10' in 'retailMarkupMin' field
And the user inputs '15' in 'retailMarkupMax' field
When the user clicks the create button

Then the user checks the products list contain product with name 'Retailprice-RM-LUFIAOPE'

When the user clicks on product with name 'Retailprice-RM-LUFIAOPE'

When the user clicks the edit button on product card view page

Then the user checks the elements values
| elementName | value |
| retailPriceHint | 110,00 - 115,00 |
And the user checks 'retailPriceMin' 'is not' avalaible
And the user checks 'retailPriceMax' 'is not' avalaible
And the user checks 'retailMarkupMin' 'is' avalaible
And the user checks 'retailMarkupMax' 'is' avalaible
And the user checks 'retailPriceHint' 'is' avalaible
And the user checks 'retailMarkupHint' 'is not' avalaible

Scenario: Retail price last used field is active in product edition

Given the user is on the product create page
And the user logs in as 'commercialManager'

When the user inputs 'Retailprice-RP-LUFIAIPE' in 'name' field
And the user selects '10' in 'vat' dropdown
And the user inputs '100' in 'purchasePrice' field
And the user clicks 'retailPriceHint' to make it avalaible
When the user inputs '140' in 'retailPriceMin' field
And the user inputs '145' in 'retailPriceMax' field
When the user clicks the create button

Then the user checks the products list contain product with name 'Retailprice-RP-LUFIAIPE'

When the user clicks on product with name 'Retailprice-RP-LUFIAIPE'
And the user clicks the edit button on product card view page
Then the user checks the elements values

| elementName | value |
| retailMarkupHint | 40,00 - 45,00 |
And the user checks 'retailPriceMin' 'is' avalaible
And the user checks 'retailPriceMax' 'is' avalaible
And the user checks 'retailMarkupMin' 'is not' avalaible
And the user checks 'retailMarkupMax' 'is not' avalaible
And the user checks 'retailMarkupHint' 'is' avalaible
And the user checks 'retailPriceHint' 'is not' avalaible

Scenario: Retail price is active by default

Given the user is on the product create page
And the user logs in as 'commercialManager'
When the user inputs '1' in 'purchasePrice' field
Then the user checks 'retailMarkupMin' 'is' avalaible
Then the user checks 'retailMarkupMax' 'is' avalaible
And the user checks 'retailPriceMin' 'is not' avalaible
And the user checks 'retailPriceMax' 'is not' avalaible
And the user checks 'retailMarkupHint' 'is not' avalaible
And the user checks 'retailPriceHint' 'is' avalaible

Scenario: Retail price hint text

Given the user is on the product create page
And the user logs in as 'commercialManager'
When the user inputs '1' in 'purchasePrice' field
Then the user checks the elements values
| elementName | value |
| retailPriceHint | Введите значение |

Scenario: Retail markup hint text

Given the user is on the product create page
And the user logs in as 'commercialManager'
When the user inputs '1' in 'purchasePrice' field
And the user clicks 'retailPriceHint' to make it avalaible
Then the user checks the elements values
| elementName | value |
| retailMarkupHint | Введите значение |

Scenario: Retail mark up range inheritance

Meta:
@id_s16u28s9
@smoke

Given there is the subCategory with name 'subCategorymarkUp-777' related to group named 'groupMarkUp-777' and category named 'categoryMarkUp-777'
And the user navigates to the subCategory 'subCategorymarkUp-777', category 'categoryMarkUp-777', group 'groupMarkUp-777' product list page
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user switches to 'subCategory' properties tab
And the user sets min mark up value to '1'
And the user sets max mark up value to '1'
And the user clicks save mark up button
Then the user sees success message 'Свойства успешно сохранены'
When the user creates new product from product list page
And the user inputs '1' in 'purchasePrice' field by sendKeys method
Then the user checks the elements values
| elementName | value |
| retailMarkupMin | 1 |
| retailMarkupMax | 1 |

