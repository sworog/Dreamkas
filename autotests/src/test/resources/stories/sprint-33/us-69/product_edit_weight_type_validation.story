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
@s33u69s09

Scenario: Edit weight product validation length validation negative

Meta:
@s33u69s09e01

Given there is the product with <productName> name, 'weight' type
And the user navigates to the product with name <productName>
And the user logs in as 'commercialManager'
When the user clicks the edit button on product card view page
And the user generates charData with <charNumber> number in the <elementName> field
Then the user checks <elementName> field contains only <charNumber> symbols
When the user clicks the create button
Then the user user sees <errorMessage>

Examples:
| charNumber | elementName | errorMessage | productName |
| 257 | nameOnScales | Не более 256 символов | s33u69s09e01.0 |
| 257 | descriptionOnScales | Не более 256 символов | s33u69s09e01.1 |
| 1025 | ingredients | Не более 1024 символов | s33u69s09e01.2 |
| 1025 | nutritionFacts | Не более 1024 символов | s33u69s09e01.3 |

Scenario: Edit weight product length validation positive

Meta:
@s33u69s09e02

Given there is the product with <productName> name, 'weight' type
And the user navigates to the product with name <productName>
And the user logs in as 'commercialManager'
When the user clicks the edit button on product card view page
And the user generates charData with <charNumber> number in the <elementName> field
Then the user checks <elementName> field contains only <charNumber> symbols
When the user clicks the create button
Then the user sees no error messages
Given the user is on the product list page
Then the user checks the product with <productName> name is present

Examples:
| charNumber | elementName | productName |
| 256 | nameOnScales | s33u69s09e02.0 |
| 256 | descriptionOnScales | s33u69s09e02.1 |
| 1024 | ingredients | s33u69s09e02.2 |
| 1024 | nutritionFacts | s33u69s09e02.3 |

Scenario: Edit weight product all weight fields are not required

Meta:
@s33u69s09e03

Given there is the product with 's33u69s09e03' name, 'weight' type
And the user navigates to the product with name 's33u69s09e03'
And the user logs in as 'commercialManager'
When the user clicks the edit button on product card view page
And the user inputs '' in 'nameOnScales' element field
And the user inputs '' in 'descriptionOnScales' element field
And the user inputs '' in 'ingredients' element field
And the user inputs '' in 'nutritionFacts' element field
And the user inputs '' in 'shelfLife' element field
When the user clicks the create button
Then the user sees no error messages
Given the user is on the product list page
Then the user checks the product with 's33u69s09e03' name is present

Scenario: Edit weight product shelfLife negative

Meta:
@s33u69s09e04

Given there is the product with <productName> name, 'weight' type
And the user navigates to the product with name <productName>
And the user logs in as 'commercialManager'
When the user clicks the edit button on product card view page
And the user inputs <exampleValue> in <exampleElement> element field
When the user clicks the create button
Then the user user sees <errorMessage>

Examples:
| exampleElement | exampleValue | errorMessage | productName |
| shelfLife | 1001 | Значение должно быть меньше или равно 1000 | s33u69s09e04.0 |
| shelfLife | aa   | Значение должно быть числом | s33u69s09e04.1 |
| shelfLife | 1,02 | Значение должно быть числом | s33u69s09e04.2 |
| shelfLife | 1.02 | Значение должно быть числом | s33u69s09e04.3 |

Scenario: Edit weight product shelfLife positive

Meta:
@s33u69s09e05

Given there is the product with <productName> name, 'weight' type
And the user navigates to the product with name <productName>
And the user logs in as 'commercialManager'
When the user clicks the edit button on product card view page
And the user inputs <exampleValue> in <exampleElement> element field
When the user clicks the create button
Then the user sees no error messages
Given the user is on the product list page
Then the user checks the product with <productName> name is present

Examples:
| exampleElement | exampleValue | productName |
| shelfLife | 0 | s33u69s09e05.0 |
| shelfLife | 10 | s33u69s09e05.1 |
| shelfLife | 1000 | s33u69s09e05.2 |
| shelfLife | 24 | s33u69s09e05.3 |

Scenario: Edit weight product switch to unit type

Meta:
@s33u69s09e06

Given there is the product with 's33u69s09e06' name, 'weight' type
And the user navigates to the product with name 's33u69s09e06'
And the user logs in as 'commercialManager'
When the user clicks the edit button on product card view page
And the user inputs 's33u69s09e06' in 'nameOnScales' element field
And the user inputs 's33u69s09e06' in 'descriptionOnScales' element field
And the user inputs 's33u69s09e06' in 'ingredients' element field
And the user inputs 's33u69s09e06' in 'nutritionFacts' element field
And the user inputs '1' in 'shelfLife' element field
When the user clicks the create button
Then the user sees no error messages
When the user clicks the edit button on product card view page
And the user selects product type 'Штучный'
When the user clicks the create button
Then the user sees no error messages
Given the user is on the product list page
Then the user checks the product with 's33u69s09e06' name is present

