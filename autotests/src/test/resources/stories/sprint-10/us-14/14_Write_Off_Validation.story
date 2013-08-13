
Meta:
@sprint 10
@us 14

Scenario: Write off Validation - number is required

Given the user opens the write off create page
And the user logs in as 'departmentManager'
When the user inputs '' in the 'writeOff number' field on the write off page
And the user inputs 'todayDate' in the 'writeOff date' field on the write off page
And the user continues the write off creation
Then the user sees error messages
| error message |
| Заполните это поле |
When the user logs out

Scenario: Write off Validation - valid number 100 symbols

Given the user opens the write off create page
And the user logs in as 'departmentManager'
When the user inputs 'todayDate' in the 'writeOff date' field on the write off page
And the user generates charData with '100' number in the 'writeOff number' write off field
Then the user checks 'writeOff number' write off field contains only '100' symbols
When the user continues the write off creation
Then the user sees no error messages
When the user logs out

Scenario: Write off Validation - invalid 101 symbols

Given the user opens the write off create page
And the user logs in as 'departmentManager'
When the user inputs 'todayDate' in the 'writeOff date' field on the write off page
And the user generates charData with '101' number in the 'writeOff number' write off field
Then the user checks 'writeOff number' write off field contains only '101' symbols
When the user continues the write off creation
Then the user sees error messages
| error message |
| Не более 100 символов |
When the user logs out

Scenario: Write off Validation - date is required

Given the user opens the write off create page
And the user logs in as 'departmentManager'
When the user inputs 'WriteOff Number-1' in the 'writeOff number' field on the write off page
And the user inputs '!' in the 'writeOff date' field on the write off page
When the user continues the write off creation
Then the user sees error messages
| error message |
| Заполните это поле |
When the user logs out

Scenario: Write Off date validation good manual

Given the user opens the write off create page
And the user logs in as 'departmentManager'
When the user inputs 'WriteOff test-1' in the 'writeOff number' field on the write off page
And the user inputs '!03.12.2012' in the write off 'writeOff date' field
When the user continues the write off creation
Then the user sees no error messages
When the user logs out

Scenario: Write Off date validation manual negative1 numbers

Given the user opens the write off create page
And the user logs in as 'departmentManager'
When the user inputs 'WriteOff test-2' in the 'writeOff number' field on the write off page
And the user inputs '!123454567890' in the write off 'writeOff date' field
When the user continues the write off creation
Then the user sees no error messages
When the user logs out

Scenario: Write Off date validation manual negative1 numbers 2

Given the user opens the write off create page
And the user logs in as 'departmentManager'
When the user inputs 'WriteOff test-3' in the 'writeOff number' field on the write off page
And the user inputs '!12345456789' in the write off 'writeOff date' field
When the user continues the write off creation
Then the user sees no error messages
When the user logs out

Scenario: Write off writeOff date validation manual negative2 eng symbols

Given the user opens the write off create page
And the user logs in as 'departmentManager'
When the user inputs 'WriteOff test-4' in the 'writeOff number' field on the write off page
And the user inputs '!HAasdfsfsfsf' in the write off 'writeOff date' field
When the user continues the write off creation
Then the user sees error messages
| error message |
| Заполните это поле |
When the user logs out

Scenario: WriteOff date validation manual negative3 rus symbols

Given the user opens the write off create page
And the user logs in as 'departmentManager'
When the user inputs 'WriteOff test-5' in the 'writeOff number' field on the write off page
And the user inputs '!Русский набор' in the write off 'writeOff date' field
When the user continues the write off creation
Then the user sees error messages
| error message |
| Заполните это поле |
When the user logs out

Scenario: WriteOff date validation manual negative symbols

Given the user opens the write off create page
And the user logs in as 'departmentManager'
When the user inputs 'WriteOff test-6' in the 'writeOff number' field on the write off page
And the user inputs '!"№;%:?*()_+' in the write off 'writeOff date' field
When the user continues the write off creation
Then the user sees error messages
| error message |
| Заполните это поле |
When the user logs out

Scenario: WriteOff date validation manual negative symbols mix

Given the user opens the write off create page
And the user logs in as 'departmentManager'
When the user inputs 'WriteOff test-6' in the 'writeOff number' field on the write off page
And the user inputs '!"56gfЛВ' in the write off 'writeOff date' field
When the user continues the write off creation
Then the user sees error messages
| error message |
| Заполните это поле |
When the user logs out

Scenario: WriteOff date validation through datepicker good

Given the user opens the write off create page
And the user logs in as 'departmentManager'
When the user inputs 'WriteOff test-7' in the 'writeOff number' field on the write off page
And the user inputs 'todayDate' in the write off 'writeOff date' field
When the user continues the write off creation
Then the user sees no error messages
When the user logs out

Scenario: WriteOff autocomplete is required

Given there is the write off with number 'WriteOff test-10'
And the user navigates to the write off with number 'WriteOff test-10'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user presses the add product button and add the product to write off
Then the user sees error messages
| error message |
| Такого товара не существует |
When the user logs out

Scenario: WriteOff no product name validation

Given there is the write off with number 'WriteOff test-11'
And the user navigates to the write off with number 'WriteOff test-11'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user inputs '!Лвражрварврадв-45-345' in the 'writeOff product name autocomplete' field on the write off page
And the user presses the add product button and add the product to write off
Then the user sees error messages
| error message |
| Такого товара не существует |
When the user logs out

Scenario: WriteOff no product barcode validation

Given there is the write off with number 'WriteOff test-12'
And the user navigates to the write off with number 'WriteOff test-12'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user inputs '!Лвражрварврадв-45-345' in the 'writeOff product sku autocomplete' field on the write off page
And the user presses the add product button and add the product to write off
Then the user sees error messages
| error message |
| Такого товара не существует |
When the user logs out

Scenario: WriteOff no product sku validation

Given there is the write off with number 'WriteOff test-13'
And the user navigates to the write off with number 'WriteOff test-13'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user inputs '!Лвражрварврадв-45-345' in the 'writeOff product barCode autocomplete' field on the write off page
And the user presses the add product button and add the product to write off
Then the user sees error messages
| error message |
| Такого товара не существует |
When the user logs out

Scenario: Write off Validation - quantity is required

Given there is the product with 'Name-WOV-QIR' name, 'SKU-WOV-QIR' sku, 'BARCode-WOV-QIR' barcode
And there is the write off with number 'WriteOff test-14'
And the user navigates to the write off with number 'WriteOff test-14'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user inputs 'Name-WOV-QIR' in the 'writeOff product name autocomplete' field on the write off page
And the user inputs '15' in the 'writeOff product price' field on the write off page
And the user inputs 'writeOff cause' in the 'writeOff cause' field on the write off page
When the user presses the add product button and add the product to write off
Then the user sees error messages
| error message |
| Заполните это поле |
When the user logs out

Scenario: Write off product quantity validation sub zero

Given there is the product with 'Name-WOV-QIR' name, 'SKU-WOV-QIR' sku, 'BARCode-WOV-QIR' barcode
And there is the write off with number 'WriteOff test-14'
And the user navigates to the write off with number 'WriteOff test-14'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user inputs 'Name-WOV-QIR' in the 'writeOff product name autocomplete' field on the write off page
And the user inputs '15' in the 'writeOff product price' field on the write off page
And the user inputs 'writeOff cause' in the 'writeOff cause' field on the write off page
When the user inputs '-10' in the write off product 'writeOff product quantity' field
And the user presses the add product button and add the product to write off
Then the user sees error messages
| error message |
| Значение должно быть больше 0 |
When the user logs out

Scenario: Write off product quantity validation zero

Given there is the product with 'Name-WOV-QIR' name, 'SKU-WOV-QIR' sku, 'BARCode-WOV-QIR' barcode
And there is the write off with number 'WriteOff test-14'
And the user navigates to the write off with number 'WriteOff test-14'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user inputs 'Name-WOV-QIR' in the 'writeOff product name autocomplete' field on the write off page
And the user inputs '15' in the 'writeOff product price' field on the write off page
And the user inputs 'writeOff cause' in the 'writeOff cause' field on the write off page
When the user inputs '0' in the write off product 'writeOff product quantity' field
And the user presses the add product button and add the product to write off
Then the user sees error messages
| error message |
| Значение должно быть больше 0 |
When the user logs out

Scenario: Write off product quantity validation String en small register

Given there is the product with 'Name-WOV-QIR' name, 'SKU-WOV-QIR' sku, 'BARCode-WOV-QIR' barcode
And there is the write off with number 'WriteOff test-14'
And the user navigates to the write off with number 'WriteOff test-14'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user inputs 'Name-WOV-QIR' in the 'writeOff product name autocomplete' field on the write off page
And the user inputs '15' in the 'writeOff product price' field on the write off page
And the user inputs 'writeOff cause' in the 'writeOff cause' field on the write off page
When the user inputs 'asdd' in the write off product 'writeOff product quantity' field
And the user presses the add product button and add the product to write off
Then the user sees error messages
| error message |
| Значение должно быть целым числом |
When the user logs out

Scenario: Write off product quantity validation String en big register

Given there is the product with 'Name-WOV-QIR' name, 'SKU-WOV-QIR' sku, 'BARCode-WOV-QIR' barcode
And there is the write off with number 'WriteOff test-14'
And the user navigates to the write off with number 'WriteOff test-14'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user inputs 'Name-WOV-QIR' in the 'writeOff product name autocomplete' field on the write off page
And the user inputs '15' in the 'writeOff product price' field on the write off page
And the user inputs 'writeOff cause' in the 'writeOff cause' field on the write off page
When the user inputs 'ADHF' in the write off product 'writeOff product quantity' field
And the user presses the add product button and add the product to write off
Then the user sees error messages
| error message |
| Значение должно быть целым числом |
When the user logs out

Scenario: Write off product quantity validation String rus small register

Given there is the product with 'Name-WOV-QIR' name, 'SKU-WOV-QIR' sku, 'BARCode-WOV-QIR' barcode
And there is the write off with number 'WriteOff test-14'
And the user navigates to the write off with number 'WriteOff test-14'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user inputs 'Name-WOV-QIR' in the 'writeOff product name autocomplete' field on the write off page
And the user inputs '15' in the 'writeOff product price' field on the write off page
And the user inputs 'writeOff cause' in the 'writeOff cause' field on the write off page
When the user inputs 'домик' in the write off product 'writeOff product quantity' field
And the user presses the add product button and add the product to write off
Then the user sees error messages
| error message |
| Значение должно быть целым числом |
When the user logs out

Scenario: Write off product quantity validation String rus big register\

Given there is the product with 'Name-WOV-QIR' name, 'SKU-WOV-QIR' sku, 'BARCode-WOV-QIR' barcode
And there is the write off with number 'WriteOff test-14'
And the user navigates to the write off with number 'WriteOff test-14'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user inputs 'Name-WOV-QIR' in the 'writeOff product name autocomplete' field on the write off page
And the user inputs '15' in the 'writeOff product price' field on the write off page
And the user inputs 'writeOff cause' in the 'writeOff cause' field on the write off page
When the user inputs 'Домище' in the write off product 'writeOff product quantity' field
And the user presses the add product button and add the product to write off
Then the user sees error messages
| error message |
| Значение должно быть целым числом |
When the user logs out

Scenario: Write off product quantity validation symbols

Given there is the product with 'Name-WOV-QIR' name, 'SKU-WOV-QIR' sku, 'BARCode-WOV-QIR' barcode
And there is the write off with number 'WriteOff test-14'
And the user navigates to the write off with number 'WriteOff test-14'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user inputs 'Name-WOV-QIR' in the 'writeOff product name autocomplete' field on the write off page
And the user inputs '15' in the 'writeOff product price' field on the write off page
And the user inputs 'writeOff cause' in the 'writeOff cause' field on the write off page
When the user presses the add product button and add the product to write off
And the user inputs '^%#$)&' in the write off product 'writeOff product quantity' field
And the user presses the add product button and add the product to write off
Then the user sees error messages
| error message |
| Значение должно быть целым числом |
When the user logs out

Scenario: Write off product quantity positive validation

Given there is the product with 'Name-WOV-QIR-1' name, 'SKU-WOV-QIR-1' sku, 'BARCode-WOV-QIR-1' barcode
And there is the write off with number 'WriteOff test-15'
And the user navigates to the write off with number 'WriteOff test-15'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user inputs 'Name-WOV-QIR-1' in the 'writeOff product name autocomplete' field on the write off page
And the user inputs '15' in the 'writeOff product price' field on the write off page
And the user inputs 'writeOff cause' in the 'writeOff cause' field on the write off page
And the user inputs '1' in the write off product 'writeOff product quantity' field
And the user presses the add product button and add the product to write off
Then the user sees no error messages
When the user logs out

Scenario: Write off price validation - price is required

Given there is the product with 'Name-WOV-QIR' name, 'SKU-WOV-QIR' sku, 'BARCode-WOV-QIR' barcode
And there is the write off with number 'WriteOff test-16'
And the user navigates to the write off with number 'WriteOff test-16'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user inputs 'Name-WOV-QIR' in the 'writeOff product name autocomplete' field on the write off page
And the user inputs '' in the 'writeOff product price' field on the write off page
And the user inputs 'writeOff cause' in the 'writeOff cause' field on the write off page
And the user inputs '1' in the 'writeOff product quantity' field on the write off page
When the user presses the add product button and add the product to write off
Then the user sees error messages
| error message |
| Заполните это поле |
When the user logs out

Scenario: Write off product price validation commma

Given there is the product with 'Name-WOV-QIR' name, 'SKU-WOV-QIR' sku, 'BARCode-WOV-QIR' barcode
And there is the write off with number 'WriteOff test-17'
And the user navigates to the write off with number 'WriteOff test-17'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user inputs 'Name-WOV-QIR' in the 'writeOff product name autocomplete' field on the write off page
And the user inputs 'writeOff cause' in the 'writeOff cause' field on the write off page
And the user inputs '1' in the 'writeOff product quantity' field on the write off page
And the user inputs ',78' in the 'writeOff product price' field on the write off page
When the user presses the add product button and add the product to write off
Then the user sees no error messages
When the user logs out

Scenario: Write off product price validation dott

Given there is the product with 'Name-WOV-QIR' name, 'SKU-WOV-QIR' sku, 'BARCode-WOV-QIR' barcode
And there is the write off with number 'WriteOff test-18'
And the user navigates to the write off with number 'WriteOff test-18'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user inputs 'Name-WOV-QIR' in the 'writeOff product name autocomplete' field on the write off page
And the user inputs 'writeOff cause' in the 'writeOff cause' field on the write off page
And the user inputs '1' in the 'writeOff product quantity' field on the write off page
And the user inputs '.78' in the 'writeOff product price' field on the write off page
When the user presses the add product button and add the product to write off
Then the user sees no error messages
When the user logs out

Scenario: Write off product price validation comma

Given there is the product with 'Name-WOV-QIR' name, 'SKU-WOV-QIR' sku, 'BARCode-WOV-QIR' barcode
And there is the write off with number 'WriteOff test-19'
And the user navigates to the write off with number 'WriteOff test-19'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user inputs 'Name-WOV-QIR' in the 'writeOff product name autocomplete' field on the write off page
And the user inputs 'writeOff cause' in the 'writeOff cause' field on the write off page
And the user inputs '1' in the 'writeOff product quantity' field on the write off page
And the user inputs '123.25' in the 'writeOff product price' field on the write off page
When the user presses the add product button and add the product to write off
Then the user sees no error messages
When the user logs out

Scenario: Write off product price validation dot

Given there is the product with 'Name-WOV-QIR' name, 'SKU-WOV-QIR' sku, 'BARCode-WOV-QIR' barcode
And there is the write off with number 'WriteOff test-19'
And the user navigates to the write off with number 'WriteOff test-19'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user inputs 'Name-WOV-QIR' in the 'writeOff product name autocomplete' field on the write off page
And the user inputs 'writeOff cause' in the 'writeOff cause' field on the write off page
And the user inputs '1' in the 'writeOff product quantity' field on the write off page
And the user inputs '12.56' in the 'writeOff product price' field on the write off page
When the user presses the add product button and add the product to write off
Then the user sees no error messages
When the user logs out

Scenario: Write off product price validation one digit

Given there is the product with 'Name-WOV-QIR' name, 'SKU-WOV-QIR' sku, 'BARCode-WOV-QIR' barcode
And there is the write off with number 'WriteOff test-20'
And the user navigates to the write off with number 'WriteOff test-20'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user inputs 'Name-WOV-QIR' in the 'writeOff product name autocomplete' field on the write off page
And the user inputs 'writeOff cause' in the 'writeOff cause' field on the write off page
And the user inputs '1' in the 'writeOff product quantity' field on the write off page
And the user inputs '2' in the 'writeOff product price' field on the write off page
When the user presses the add product button and add the product to write off
Then the user sees no error messages
When the user logs out

Scenario: Write off product price validation two digits

Given there is the product with 'Name-WOV-QIR' name, 'SKU-WOV-QIR' sku, 'BARCode-WOV-QIR' barcode
And there is the write off with number 'WriteOff test-21'
And the user navigates to the write off with number 'WriteOff test-21'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user inputs 'Name-WOV-QIR' in the 'writeOff product name autocomplete' field on the write off page
And the user inputs 'writeOff cause' in the 'writeOff cause' field on the write off page
And the user inputs '1' in the 'writeOff product quantity' field on the write off page
And the user inputs '99' in the 'writeOff product price' field on the write off page
When the user presses the add product button and add the product to write off
Then the user sees no error messages
When the user logs out

Scenario: Write off product price validation three digits

Given there is the product with 'Name-WOV-QIR' name, 'SKU-WOV-QIR' sku, 'BARCode-WOV-QIR' barcode
And there is the write off with number 'WriteOff test-22'
And the user navigates to the write off with number 'WriteOff test-22'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user inputs 'Name-WOV-QIR' in the 'writeOff product name autocomplete' field on the write off page
And the user inputs 'writeOff cause' in the 'writeOff cause' field on the write off page
And the user inputs '1' in the 'writeOff product quantity' field on the write off page
And the user inputs '12,123' in the 'writeOff product price' field on the write off page
When the user presses the add product button and add the product to write off
Then the user sees error messages
| error message |
| Цена не должна содержать больше 2 цифр после запятой. |
When the user logs out

Scenario: Write off product price validation sub zero

Given there is the product with 'Name-WOV-QIR' name, 'SKU-WOV-QIR' sku, 'BARCode-WOV-QIR' barcode
And there is the write off with number 'WriteOff test-22'
And the user navigates to the write off with number 'WriteOff test-22'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user inputs 'Name-WOV-QIR' in the 'writeOff product name autocomplete' field on the write off page
And the user inputs 'writeOff cause' in the 'writeOff cause' field on the write off page
And the user inputs '1' in the 'writeOff product quantity' field on the write off page
And the user inputs '-1' in the 'writeOff product price' field on the write off page
When the user presses the add product button and add the product to write off
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю. |
When the user logs out

Scenario: Write off product price validation zero

Given there is the product with 'Name-WOV-QIR' name, 'SKU-WOV-QIR' sku, 'BARCode-WOV-QIR' barcode
And there is the write off with number 'WriteOff test-22'
And the user navigates to the write off with number 'WriteOff test-22'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user inputs 'Name-WOV-QIR' in the 'writeOff product name autocomplete' field on the write off page
And the user inputs 'writeOff cause' in the 'writeOff cause' field on the write off page
And the user inputs '1' in the 'writeOff product quantity' field on the write off page
And the user inputs '0' in the 'writeOff product price' field on the write off page
When the user presses the add product button and add the product to write off
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю. |
When the user logs out

Scenario: Write off product price validation String en small register

Given there is the product with 'Name-WOV-QIR' name, 'SKU-WOV-QIR' sku, 'BARCode-WOV-QIR' barcode
And there is the write off with number 'WriteOff test-22'
And the user navigates to the write off with number 'WriteOff test-22'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user inputs 'Name-WOV-QIR' in the 'writeOff product name autocomplete' field on the write off page
And the user inputs 'writeOff cause' in the 'writeOff cause' field on the write off page
And the user inputs '1' in the 'writeOff product quantity' field on the write off page
And the user inputs 'harry' in the 'writeOff product price' field on the write off page
When the user presses the add product button and add the product to write off
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю. |
When the user logs out

Scenario: Write off product price validation String en big register

Given there is the product with 'Name-WOV-QIR' name, 'SKU-WOV-QIR' sku, 'BARCode-WOV-QIR' barcode
And there is the write off with number 'WriteOff test-22'
And the user navigates to the write off with number 'WriteOff test-22'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user inputs 'Name-WOV-QIR' in the 'writeOff product name autocomplete' field on the write off page
And the user inputs 'writeOff cause' in the 'writeOff cause' field on the write off page
And the user inputs '1' in the 'writeOff product quantity' field on the write off page
And the user inputs 'HARRY' in the 'writeOff product price' field on the write off page
When the user presses the add product button and add the product to write off
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю. |
When the user logs out

Scenario: Write off product price validation String rus small register

Given there is the product with 'Name-WOV-QIR' name, 'SKU-WOV-QIR' sku, 'BARCode-WOV-QIR' barcode
And there is the write off with number 'WriteOff test-22'
And the user navigates to the write off with number 'WriteOff test-22'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user inputs 'Name-WOV-QIR' in the 'writeOff product name autocomplete' field on the write off page
And the user inputs 'writeOff cause' in the 'writeOff cause' field on the write off page
And the user inputs '1' in the 'writeOff product quantity' field on the write off page
And the user inputs 'цена' in the 'writeOff product price' field on the write off page
When the user presses the add product button and add the product to write off
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю. |
When the user logs out

Scenario: Write off product price validation String rus big register

Given there is the product with 'Name-WOV-QIR' name, 'SKU-WOV-QIR' sku, 'BARCode-WOV-QIR' barcode
And there is the write off with number 'WriteOff test-22'
And the user navigates to the write off with number 'WriteOff test-22'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user inputs 'Name-WOV-QIR' in the 'writeOff product name autocomplete' field on the write off page
And the user inputs 'writeOff cause' in the 'writeOff cause' field on the write off page
And the user inputs '1' in the 'writeOff product quantity' field on the write off page
And the user inputs 'Цена' in the 'writeOff product price' field on the write off page
When the user presses the add product button and add the product to write off
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю. |
When the user logs out

Scenario: Write off product price validation symbols

Given there is the product with 'Name-WOV-QIR' name, 'SKU-WOV-QIR' sku, 'BARCode-WOV-QIR' barcode
And there is the write off with number 'WriteOff test-22'
And the user navigates to the write off with number 'WriteOff test-22'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user inputs 'Name-WOV-QIR' in the 'writeOff product name autocomplete' field on the write off page
And the user inputs 'writeOff cause' in the 'writeOff cause' field on the write off page
And the user inputs '1' in the 'writeOff product quantity' field on the write off page
And the user inputs '@#$#$#' in the 'writeOff product price' field on the write off page
When the user presses the add product button and add the product to write off
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю. |
When the user logs out

Scenario: Write off product price validation length good

Given there is the product with 'Name-WOV-QIR' name, 'SKU-WOV-QIR' sku, 'BARCode-WOV-QIR' barcode
And there is the write off with number 'WriteOff test-23'
And the user navigates to the write off with number 'WriteOff test-23'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user inputs 'Name-WOV-QIR' in the 'writeOff product name autocomplete' field on the write off page
And the user inputs 'writeOff cause' in the 'writeOff cause' field on the write off page
And the user inputs '1' in the 'writeOff product quantity' field on the write off page
And the user inputs '10000000' in the 'writeOff product price' field on the write off page
When the user presses the add product button and add the product to write off
Then the user sees no error messages
When the user logs out

Scenario: Write off product price validation length negative

Given there is the product with 'Name-WOV-QIR' name, 'SKU-WOV-QIR' sku, 'BARCode-WOV-QIR' barcode
And there is the write off with number 'WriteOff test-22'
And the user navigates to the write off with number 'WriteOff test-22'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user inputs 'Name-WOV-QIR' in the 'writeOff product name autocomplete' field on the write off page
And the user inputs 'writeOff cause' in the 'writeOff cause' field on the write off page
And the user inputs '1' in the 'writeOff product quantity' field on the write off page
And the user inputs '10000001' in the 'writeOff product price' field on the write off page
When the user presses the add product button and add the product to write off
Then the user sees error messages
| error message |
| Цена не должна быть больше 10000000 |
When the user logs out

Scenario: Write off Validation - cause is required

Given there is the product with 'Name-WOV-QIR' name, 'SKU-WOV-QIR' sku, 'BARCode-WOV-QIR' barcode
And there is the write off with number 'WriteOff test-16'
And the user navigates to the write off with number 'WriteOff test-16'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user inputs 'Name-WOV-QIR' in the 'writeOff product name autocomplete' field on the write off page
And the user inputs '15' in the 'writeOff product price' field on the write off page
And the user inputs '' in the 'writeOff cause' field on the write off page
And the user inputs '1' in the 'writeOff product quantity' field on the write off page
When the user presses the add product button and add the product to write off
Then the user sees error messages
| error message |
| Заполните это поле |
When the user logs out

Scenario: Write off Validation - cause 1000 symbols

Given there is the product with 'Name-WOV-QIR' name, 'SKU-WOV-QIR' sku, 'BARCode-WOV-QIR' barcode
And there is the write off with number 'WriteOff test-16'
And the user navigates to the write off with number 'WriteOff test-16'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user inputs 'Name-WOV-QIR' in the 'writeOff product name autocomplete' field on the write off page
And the user inputs '15' in the 'writeOff product price' field on the write off page
And the user generates charData with '1000' number in the 'writeOff cause' write off field
Then the user checks 'writeOff cause' write off field contains only '1000' symbols
When the user inputs '1' in the 'writeOff product quantity' field on the write off page
And the user presses the add product button and add the product to write off
Then the user sees no error messages
When the user logs out

Scenario: Write off Validation - cause 1001 symbols

Given there is the product with 'Name-WOV-QIR' name, 'SKU-WOV-QIR' sku, 'BARCode-WOV-QIR' barcode
And there is the write off with number 'WriteOff test-16'
And the user navigates to the write off with number 'WriteOff test-16'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user inputs 'Name-WOV-QIR' in the 'writeOff product name autocomplete' field on the write off page
And the user inputs '15' in the 'writeOff product price' field on the write off page
And the user generates charData with '1001' number in the 'writeOff cause' write off field
Then the user checks 'writeOff cause' write off field contains only '1001' symbols
When the user inputs '1' in the 'writeOff product quantity' field on the write off page
And the user presses the add product button and add the product to write off
Then the user sees error messages
| error message |
| Не более 1000 |
When the user logs out
