Валидация вводимых данных при создании товара

Narrative:
Как коммерческий директор,
Я хочу чтобы, при создании нового товара,
Система сообщала мне об ошибках в вводимых данных,
Чтобы исключить возможность создать товар с заведомо некорректными данными.

Meta:
@sprint_33
@us_69
@product
@s33u69s07

Scenario: Create product validation length validation negative

Meta:
@s33u69s07e01

Given the user is on the product list page
And the user logs in as 'commercialManager'
When the user creates new product from product list page
And the user selects product type 'Весовой'
And the user inputs <productName> in name element field
And the user selects '10' in 'vat' element dropdown
And the user generates charData with <charNumber> number in the <elementName> field
Then the user checks <elementName> field contains only <charNumber> symbols
When the user clicks the create button
Then the user user sees <errorMessage>

Examples:
| charNumber | elementName | errorMessage | productName |
| 257 | nameOnScales | Не более 256 символов | s33u69s07e01.0 |
| 257 | descriptionOnScales | Не более 256 символов | s33u69s07e01.1 |
| 1025 | ingredients | Не более 1024 символов | s33u69s07e01.2 |
| 1025 | nutritionFacts | Не более 1024 символов | s33u69s07e01.3 |

Scenario: Create product validation length validation positive

Meta:
@s33u69s07e02

Given the user is on the product list page
And the user logs in as 'commercialManager'
When the user creates new product from product list page
And the user selects product type 'Весовой'
And the user inputs <productName> in name element field
And the user selects '10' in 'vat' element dropdown
And the user generates charData with <charNumber> number in the <elementName> field
Then the user checks <elementName> field contains only <charNumber> symbols
When the user clicks the create button
Then the user sees no error messages
And the user checks the product with <productName> name is present

Examples:
| charNumber | elementName | productName |
| 256 | nameOnScales | s33u69s07e02.0 |
| 256 | descriptionOnScales | s33u69s07e02.1 |
| 1024 | ingredients | s33u69s07e02.2 |
| 1024 | nutritionFacts | s33u69s07e02.3 |

Scenario: Create product validation all weight fields are not required

Meta:
@s33u69s07e03

Given the user is on the product list page
And the user logs in as 'commercialManager'
When the user creates new product from product list page
And the user selects product type 'Весовой'
And the user inputs 's33u69s07e03' in 'name' element field
And the user selects '10' in 'vat' element dropdown
When the user clicks the create button
Then the user sees no error messages
And the user checks the product with 's33u69s07e03' name is present

Scenario: Create product validation shelfLife negative

Meta:
@s33u69s07e04

Given the user is on the product list page
And the user logs in as 'commercialManager'
When the user creates new product from product list page
And the user selects product type 'Весовой'
And the user inputs <productName> in name element field
And the user selects '10' in 'vat' element dropdown
And the user inputs <exampleValue> in <exampleElement> element field
When the user clicks the create button
Then the user user sees <errorMessage>

Examples:
| exampleElement | exampleValue | errorMessage | productName |
| shelfLife | 1001 | Значение должно быть меньше или равно 1000 | s33u69s07e04.0 |
| shelfLife | aa   | Значение должно быть числом | s33u69s07e04.1 |
| shelfLife | 1,02 | Значение должно быть числом | s33u69s07e04.2 |
| shelfLife | 1.02 | Значение должно быть числом | s33u69s07e04.3 |

Scenario: Create product validation shelfLife positive

Meta:
@s33u69s07e05

Given the user is on the product list page
And the user logs in as 'commercialManager'
When the user creates new product from product list page
And the user selects product type 'Весовой'
And the user inputs <productName> in name element field
And the user selects '10' in 'vat' element dropdown
And the user inputs <exampleValue> in <exampleElement> element field
When the user clicks the create button
Then the user sees no error messages
And the user checks the product with <productName> name is present

Examples:
| exampleElement | exampleValue | productName |
| shelfLife | 0 | s33u69s07e05.0 |
| shelfLife | 10 | s33u69s07e05.1 |
| shelfLife | 1000 | s33u69s07e05.2 |
| shelfLife | 24 | s33u69s07e05.3 |

