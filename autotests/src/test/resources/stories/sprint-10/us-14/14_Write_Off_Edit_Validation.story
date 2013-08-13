
Meta:
@sprint 10
@us 14

Scenario: Write off Edit Validation - number is required

Given there is the write off with number 'WriteOff-Edit-Val-1'
And the user navigates to the write off with number 'WriteOff-Edit-Val-1'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff number review' write off element to edit it
And the user inputs '' in the 'inline writeOff number' field on the write off page
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Заполните это поле |
When the user clicks Cancel and discard changes
And the user clicks finish edit button and ends the write off edition
And the user logs out

Scenario: Write off Edit Validation - valid number 100 symbols

Given there is the write off with number 'WriteOff-Edit-Val-2'
And the user navigates to the write off with number 'WriteOff-Edit-Val-2'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff number review' write off element to edit it
And the user generates charData with '100' number in the 'inline writeOff number' write off field
Then the user checks 'inline writeOff number' write off field contains only '100' symbols
When the user clicks OK and accepts changes
Then the user sees no error messages
When the user clicks finish edit button and ends the write off edition
And the user logs out

Scenario: Write off Edit Validation - number invalid 101 symbols

Given there is the write off with number 'WriteOff-Edit-Val-3'
And the user navigates to the write off with number 'WriteOff-Edit-Val-3'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff number review' write off element to edit it
And the user generates charData with '101' number in the 'inline writeOff number' write off field
Then the user checks 'inline writeOff number' write off field contains only '101' symbols
When the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Не более 100 символов |
When the user clicks Cancel and discard changes
And the user clicks finish edit button and ends the write off edition
And the user logs out

Scenario: Write off Edit Validation - date is required

Given there is the write off with number 'WriteOff-Edit-Val-4'
And the user navigates to the write off with number 'WriteOff-Edit-Val-4'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff date review' write off element to edit it
And the user inputs '!' in the 'inline writeOff date' field on the write off page
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Заполните это поле |
When the user clicks Cancel and discard changes
And the user clicks finish edit button and ends the write off edition
And the user logs out

Scenario: WriteOff date Edit validation good manual

Given there is the write off with number 'WriteOff-Edit-Val-5'
And the user navigates to the write off with number 'WriteOff-Edit-Val-5'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff date review' write off element to edit it
And the user inputs '!03.12.2012' in the 'inline writeOff date' field on the write off page
And the user clicks OK and accepts changes
Then the user sees no error messages
When the user clicks finish edit button and ends the write off edition
And the user logs out

Scenario: WriteOff date Edit validation manual negative1 numbers

Given there is the write off with number 'WriteOff-Edit-Val-6'
And the user navigates to the write off with number 'WriteOff-Edit-Val-6'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff date review' write off element to edit it
And the user inputs '!123454567890' in the 'inline writeOff date' field on the write off page
And the user clicks OK and accepts changes
Then the user sees no error messages
When the user clicks finish edit button and ends the write off edition
And the user logs out

Scenario: WriteOff date Edit validation manual negative1 numbers 2

Given there is the write off with number 'WriteOff-Edit-Val-7'
And the user navigates to the write off with number 'WriteOff-Edit-Val-7'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff date review' write off element to edit it
And the user inputs '!12345456789' in the 'inline writeOff date' field on the write off page
And the user clicks OK and accepts changes
Then the user sees no error messages
When the user clicks finish edit button and ends the write off edition
And the user logs out

Scenario: Write off date Edit validation manual negative2 eng symbols

Given there is the write off with number 'WriteOff-Edit-Val-8'
And the user navigates to the write off with number 'WriteOff-Edit-Val-8'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff date review' write off element to edit it
And the user inputs '!HAasdfsfsfsf' in the 'inline writeOff date' field on the write off page
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Заполните это поле |
When the user clicks Cancel and discard changes
And the user clicks finish edit button and ends the write off edition
And the user logs out

Scenario: WriteOff date Edit validation manual negative3 rus symbols

Given there is the write off with number 'WriteOff-Edit-Val-9'
And the user navigates to the write off with number 'WriteOff-Edit-Val-9'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff date review' write off element to edit it
And the user inputs '!Русский набор' in the 'inline writeOff date' field on the write off page
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Заполните это поле |
When the user clicks Cancel and discard changes
And the user clicks finish edit button and ends the write off edition
And the user logs out

Scenario: WriteOff date Edit validation manual negative symbols

Given there is the write off with number 'WriteOff-Edit-Val-10'
And the user navigates to the write off with number 'WriteOff-Edit-Val-10'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff date review' write off element to edit it
And the user inputs '!"№;%:?*()_+' in the 'inline writeOff date' field on the write off page
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Заполните это поле |
When the user clicks Cancel and discard changes
And the user clicks finish edit button and ends the write off edition
And the user logs out

Scenario: WriteOff date Edit validation manual negative symbols mix

Given there is the write off with number 'WriteOff-Edit-Val-11'
And the user navigates to the write off with number 'WriteOff-Edit-Val-11'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff date review' write off element to edit it
And the user inputs '!"56gfЛВ' in the 'inline writeOff date' field on the write off page
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Заполните это поле |
When the user clicks Cancel and discard changes
And the user clicks finish edit button and ends the write off edition
And the user logs out

Scenario: WriteOff date Edit validation through datepicker good

Given there is the write off with number 'WriteOff-Edit-Val-12'
And the user navigates to the write off with number 'WriteOff-Edit-Val-12'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff date review' write off element to edit it
And the user inputs 'todayDate' in the 'inline writeOff date' field on the write off page
And the user clicks OK and accepts changes
Then the user sees no error messages
When the user clicks finish edit button and ends the write off edition
And the user logs out

Scenario: WriteOff edit - no product name validation

Given there is the write off with 'WriteOff-Edit-Val-13' number with product 'Name-WOV-QIR' with quantity '10', price '15' and cause 'Причина'
And the user navigates to the write off with number 'WriteOff-Edit-Val-13'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff product name review' element of write off product with 'Name-WOV-QIR' sku to edit
And the user inputs '!Лвражрварврадв-45-345' in the 'inline writeOff product name autocomplete' field on the write off page
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Такого товара не существует |
When the user clicks Cancel and discard changes
And the user clicks finish edit button and ends the write off edition
And the user logs out

Scenario: WriteOff edit - no product barcode validation

Given there is the write off with 'WriteOff-Edit-Val-14' number with product 'Name-WOV-QIR' with quantity '10', price '15' and cause 'Причина'
And the user navigates to the write off with number 'WriteOff-Edit-Val-14'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff product barCode review' element of write off product with 'Name-WOV-QIR' sku to edit
And the user inputs '!Лвражрварврадв-45-345' in the 'inline writeOff product barCode autocomplete' field on the write off page
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Такого товара не существует |
When the user clicks Cancel and discard changes
And the user clicks finish edit button and ends the write off edition
And the user logs out

Scenario: WriteOff edit - no product sku validation

Given there is the write off with 'WriteOff-Edit-Val-15' number with product 'Name-WOV-QIR' with quantity '10', price '15' and cause 'Причина'
And the user navigates to the write off with number 'WriteOff-Edit-Val-15'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff product sku review' element of write off product with 'Name-WOV-QIR' sku to edit
And the user inputs '!Лвражрварврадв-45-345' in the 'inline writeOff product sku autocomplete' field on the write off page
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Такого товара не существует |
When the user clicks Cancel and discard changes
And the user clicks finish edit button and ends the write off edition
And the user logs out

Scenario: Write off Validation - quantity is required

Given there is the write off with 'WriteOff-Edit-Val-16' number with product 'Name-WOV-QIR' with quantity '10', price '15' and cause 'Причина'
And the user navigates to the write off with number 'WriteOff-Edit-Val-16'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff product quantity review' element of write off product with 'Name-WOV-QIR' sku to edit
And the user inputs '' in the 'inline writeOff product quantity' field on the write off page
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Заполните это поле |
When the user clicks Cancel and discard changes
And the user clicks finish edit button and ends the write off edition
And the user logs out

Scenario: Write off product quantity validation sub zero

Given there is the write off with 'WriteOff-Edit-Val-17' number with product 'Name-WOV-QIR' with quantity '10', price '15' and cause 'Причина'
And the user navigates to the write off with number 'WriteOff-Edit-Val-17'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff product quantity review' element of write off product with 'Name-WOV-QIR' sku to edit
And the user inputs '-10' in the 'inline writeOff product quantity' field on the write off page
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Значение должно быть больше 0 |
When the user clicks Cancel and discard changes
And the user clicks finish edit button and ends the write off edition
And the user logs out

Scenario: Write off product quantity validation zero

Given there is the write off with 'WriteOff-Edit-Val-18' number with product 'Name-WOV-QIR' with quantity '10', price '15' and cause 'Причина'
And the user navigates to the write off with number 'WriteOff-Edit-Val-18'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff product quantity review' element of write off product with 'Name-WOV-QIR' sku to edit
And the user inputs '0' in the 'inline writeOff product quantity' field on the write off page
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Значение должно быть больше 0 |
When the user clicks Cancel and discard changes
And the user clicks finish edit button and ends the write off edition
And the user logs out

Scenario: Write off product quantity validation String en small register

Given there is the write off with 'WriteOff-Edit-Val-19' number with product 'Name-WOV-QIR' with quantity '10', price '15' and cause 'Причина'
And the user navigates to the write off with number 'WriteOff-Edit-Val-19'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff product quantity review' element of write off product with 'Name-WOV-QIR' sku to edit
And the user inputs 'asdd' in the 'inline writeOff product quantity' field on the write off page
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Значение должно быть целым числом |
When the user clicks Cancel and discard changes
And the user clicks finish edit button and ends the write off edition
And the user logs out

Scenario: Write off product quantity validation String en big register

Given there is the write off with 'WriteOff-Edit-Val-20' number with product 'Name-WOV-QIR' with quantity '10', price '15' and cause 'Причина'
And the user navigates to the write off with number 'WriteOff-Edit-Val-20'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff product quantity review' element of write off product with 'Name-WOV-QIR' sku to edit
And the user inputs 'ADHF' in the 'inline writeOff product quantity' field on the write off page
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Значение должно быть целым числом |
When the user clicks Cancel and discard changes
And the user clicks finish edit button and ends the write off edition
And the user logs out

Scenario: Write off product quantity validation String rus small register

Given there is the write off with 'WriteOff-Edit-Val-21' number with product 'Name-WOV-QIR' with quantity '10', price '15' and cause 'Причина'
And the user navigates to the write off with number 'WriteOff-Edit-Val-21'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff product quantity review' element of write off product with 'Name-WOV-QIR' sku to edit
And the user inputs 'рыба' in the 'inline writeOff product quantity' field on the write off page
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Значение должно быть целым числом |
When the user clicks Cancel and discard changes
And the user clicks finish edit button and ends the write off edition
And the user logs out

Scenario: Write off product quantity validation String rus big register\

Given there is the write off with 'WriteOff-Edit-Val-22' number with product 'Name-WOV-QIR' with quantity '10', price '15' and cause 'Причина'
And the user navigates to the write off with number 'WriteOff-Edit-Val-22'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff product quantity review' element of write off product with 'Name-WOV-QIR' sku to edit
And the user inputs 'Рыба' in the 'inline writeOff product quantity' field on the write off page
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Значение должно быть целым числом |
When the user clicks Cancel and discard changes
And the user clicks finish edit button and ends the write off edition
And the user logs out

Scenario: Write off product quantity validation symbols

Given there is the write off with 'WriteOff-Edit-Val-23' number with product 'Name-WOV-QIR' with quantity '10', price '15' and cause 'Причина'
And the user navigates to the write off with number 'WriteOff-Edit-Val-23'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff product quantity review' element of write off product with 'Name-WOV-QIR' sku to edit
And the user inputs '^%#$)&' in the 'inline writeOff product quantity' field on the write off page
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Значение должно быть целым числом |
When the user clicks Cancel and discard changes
And the user clicks finish edit button and ends the write off edition
And the user logs out

Scenario: Write off product quantity positive validation

Given there is the write off with 'WriteOff-Edit-Val-24' number with product 'Name-WOV-QIR' with quantity '10', price '15' and cause 'Причина'
And the user navigates to the write off with number 'WriteOff-Edit-Val-24'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff product quantity review' element of write off product with 'Name-WOV-QIR' sku to edit
And the user inputs '1' in the 'inline writeOff product quantity' field on the write off page
And the user clicks OK and accepts changes
Then the user sees no error messages
When the user clicks finish edit button and ends the write off edition
And the user logs out

Scenario: Write off edit price validation - price is required

Given there is the write off with 'WriteOff-Edit-Val-25' number with product 'Name-WOV-QIR' with quantity '10', price '15' and cause 'Причина'
And the user navigates to the write off with number 'WriteOff-Edit-Val-25'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff product price review' element of write off product with 'Name-WOV-QIR' sku to edit
And the user inputs '' in the 'inline writeOff product price' field on the write off page
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Заполните это поле |
When the user clicks Cancel and discard changes
And the user clicks finish edit button and ends the write off edition
And the user logs out

Scenario: Write off edit product price validation commma

Given there is the write off with 'WriteOff-Edit-Val-26' number with product 'Name-WOV-QIR' with quantity '10', price '15' and cause 'Причина'
And the user navigates to the write off with number 'WriteOff-Edit-Val-26'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff product price review' element of write off product with 'Name-WOV-QIR' sku to edit
And the user inputs ',78' in the 'inline writeOff product price' field on the write off page
And the user clicks OK and accepts changes
Then the user sees no error messages
When the user clicks finish edit button and ends the write off edition
And the user logs out

Scenario: Write off edit product price validation dott

Given there is the write off with 'WriteOff-Edit-Val-27' number with product 'Name-WOV-QIR' with quantity '10', price '15' and cause 'Причина'
And the user navigates to the write off with number 'WriteOff-Edit-Val-27'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff product price review' element of write off product with 'Name-WOV-QIR' sku to edit
And the user inputs '.78' in the 'inline writeOff product price' field on the write off page
And the user clicks OK and accepts changes
Then the user sees no error messages
When the user clicks finish edit button and ends the write off edition
And the user logs out

Scenario: Write off edit product price validation comma

Given there is the write off with 'WriteOff-Edit-Val-28' number with product 'Name-WOV-QIR' with quantity '10', price '15' and cause 'Причина'
And the user navigates to the write off with number 'WriteOff-Edit-Val-28'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff product price review' element of write off product with 'Name-WOV-QIR' sku to edit
And the user inputs '123,25' in the 'inline writeOff product price' field on the write off page
And the user clicks OK and accepts changes
Then the user sees no error messages
When the user clicks finish edit button and ends the write off edition
And the user logs out

Scenario: Write off edit product price validation dot

Given there is the write off with 'WriteOff-Edit-Val-29' number with product 'Name-WOV-QIR' with quantity '10', price '15' and cause 'Причина'
And the user navigates to the write off with number 'WriteOff-Edit-Val-29'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff product price review' element of write off product with 'Name-WOV-QIR' sku to edit
And the user inputs '12.56' in the 'inline writeOff product price' field on the write off page
And the user clicks OK and accepts changes
Then the user sees no error messages
When the user clicks finish edit button and ends the write off edition
And the user logs out

Scenario: Write off edit product price validation one digit

Given there is the write off with 'WriteOff-Edit-Val-30' number with product 'Name-WOV-QIR' with quantity '10', price '15' and cause 'Причина'
And the user navigates to the write off with number 'WriteOff-Edit-Val-30'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff product price review' element of write off product with 'Name-WOV-QIR' sku to edit
And the user inputs '1' in the 'inline writeOff product price' field on the write off page
And the user clicks OK and accepts changes
Then the user sees no error messages
When the user clicks finish edit button and ends the write off edition
And the user logs out

Scenario: Write off edit product price validation two digits

Given there is the write off with 'WriteOff-Edit-Val-31' number with product 'Name-WOV-QIR' with quantity '10', price '15' and cause 'Причина'
And the user navigates to the write off with number 'WriteOff-Edit-Val-31'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff product price review' element of write off product with 'Name-WOV-QIR' sku to edit
And the user inputs '99' in the 'inline writeOff product price' field on the write off page
And the user clicks OK and accepts changes
Then the user sees no error messages
When the user clicks finish edit button and ends the write off edition
And the user logs out

Scenario: Write off edit product price validation three digits

Given there is the write off with 'WriteOff-Edit-Val-32' number with product 'Name-WOV-QIR' with quantity '10', price '15' and cause 'Причина'
And the user navigates to the write off with number 'WriteOff-Edit-Val-32'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff product price review' element of write off product with 'Name-WOV-QIR' sku to edit
And the user inputs '12,123' in the 'inline writeOff product price' field on the write off page
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Цена не должна содержать больше 2 цифр после запятой. |
When the user clicks Cancel and discard changes
And the user clicks finish edit button and ends the write off edition
And the user logs out

Scenario: Write off edit product price validation sub zero

Given there is the write off with 'WriteOff-Edit-Val-33' number with product 'Name-WOV-QIR' with quantity '10', price '15' and cause 'Причина'
And the user navigates to the write off with number 'WriteOff-Edit-Val-33'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff product price review' element of write off product with 'Name-WOV-QIR' sku to edit
And the user inputs '-1' in the 'inline writeOff product price' field on the write off page
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю. |
When the user clicks Cancel and discard changes
And the user clicks finish edit button and ends the write off edition
And the user logs out

Scenario: Write off edit product price validation zero

Given there is the write off with 'WriteOff-Edit-Val-34' number with product 'Name-WOV-QIR' with quantity '10', price '15' and cause 'Причина'
And the user navigates to the write off with number 'WriteOff-Edit-Val-34'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff product price review' element of write off product with 'Name-WOV-QIR' sku to edit
And the user inputs '0' in the 'inline writeOff product price' field on the write off page
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю. |
When the user clicks Cancel and discard changes
And the user clicks finish edit button and ends the write off edition
And the user logs out

Scenario: Write off edit product price validation String en small register

Given there is the write off with 'WriteOff-Edit-Val-35' number with product 'Name-WOV-QIR' with quantity '10', price '15' and cause 'Причина'
And the user navigates to the write off with number 'WriteOff-Edit-Val-35'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff product price review' element of write off product with 'Name-WOV-QIR' sku to edit
And the user inputs 'harry' in the 'inline writeOff product price' field on the write off page
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю. |
When the user clicks Cancel and discard changes
And the user clicks finish edit button and ends the write off edition
And the user logs out

Scenario: Write off edit product price validation String en big register

Given there is the write off with 'WriteOff-Edit-Val-36' number with product 'Name-WOV-QIR' with quantity '10', price '15' and cause 'Причина'
And the user navigates to the write off with number 'WriteOff-Edit-Val-36'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff product price review' element of write off product with 'Name-WOV-QIR' sku to edit
And the user inputs 'HARRY' in the 'inline writeOff product price' field on the write off page
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю. |
When the user clicks Cancel and discard changes
And the user clicks finish edit button and ends the write off edition
And the user logs out

Scenario: Write off edit product price validation String rus small register

Given there is the write off with 'WriteOff-Edit-Val-37' number with product 'Name-WOV-QIR' with quantity '10', price '15' and cause 'Причина'
And the user navigates to the write off with number 'WriteOff-Edit-Val-37'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff product price review' element of write off product with 'Name-WOV-QIR' sku to edit
And the user inputs 'цена' in the 'inline writeOff product price' field on the write off page
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю. |
When the user clicks Cancel and discard changes
And the user clicks finish edit button and ends the write off edition
And the user logs out

Scenario: Write off edit product price validation String rus big register

Given there is the write off with 'WriteOff-Edit-Val-38' number with product 'Name-WOV-QIR' with quantity '10', price '15' and cause 'Причина'
And the user navigates to the write off with number 'WriteOff-Edit-Val-38'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff product price review' element of write off product with 'Name-WOV-QIR' sku to edit
And the user inputs 'ЦЕНА' in the 'inline writeOff product price' field on the write off page
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю. |
When the user clicks Cancel and discard changes
And the user clicks finish edit button and ends the write off edition
And the user logs out

Scenario: Write off edit product price validation symbols

Given there is the write off with 'WriteOff-Edit-Val-39' number with product 'Name-WOV-QIR' with quantity '10', price '15' and cause 'Причина'
And the user navigates to the write off with number 'WriteOff-Edit-Val-39'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff product price review' element of write off product with 'Name-WOV-QIR' sku to edit
And the user inputs ';№?:"?*:№"' in the 'inline writeOff product price' field on the write off page
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю. |
When the user clicks Cancel and discard changes
And the user clicks finish edit button and ends the write off edition
And the user logs out

Scenario: Write off edit product price validation length good

Given there is the write off with 'WriteOff-Edit-Val-40' number with product 'Name-WOV-QIR' with quantity '10', price '15' and cause 'Причина'
And the user navigates to the write off with number 'WriteOff-Edit-Val-40'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff product price review' element of write off product with 'Name-WOV-QIR' sku to edit
And the user inputs '10000000' in the 'inline writeOff product price' field on the write off page
And the user clicks OK and accepts changes
Then the user sees no error messages
When the user clicks finish edit button and ends the write off edition
And the user logs out

Scenario: Write off edit product price validation length negative

Given there is the write off with 'WriteOff-Edit-Val-41' number with product 'Name-WOV-QIR' with quantity '10', price '15' and cause 'Причина'
And the user navigates to the write off with number 'WriteOff-Edit-Val-41'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff product price review' element of write off product with 'Name-WOV-QIR' sku to edit
And the user inputs '10000001' in the 'inline writeOff product price' field on the write off page
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Цена не должна быть больше 10000000 |
When the user clicks Cancel and discard changes
And the user clicks finish edit button and ends the write off edition
And the user logs out

Scenario: Write off edit Validation - cause is required

Given there is the write off with 'WriteOff-Edit-Val-42' number with product 'Name-WOV-QIR' with quantity '10', price '15' and cause 'Причина'
And the user navigates to the write off with number 'WriteOff-Edit-Val-42'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff cause review' element of write off product with 'Name-WOV-QIR' sku to edit
And the user inputs '' in the 'inline writeOff cause' field on the write off page
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Заполните это поле |
When the user clicks Cancel and discard changes
And the user clicks finish edit button and ends the write off edition
And the user logs out

Scenario: Write off edit Validation - cause 1000 symbols

Given there is the write off with 'WriteOff-Edit-Val-43' number with product 'Name-WOV-QIR' with quantity '10', price '15' and cause 'Причина'
And the user navigates to the write off with number 'WriteOff-Edit-Val-43'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff cause review' element of write off product with 'Name-WOV-QIR' sku to edit
And the user generates charData with '1000' number in the 'inline writeOff cause' write off field
Then the user checks 'inline writeOff cause' write off field contains only '1000' symbols
When the user clicks OK and accepts changes
Then the user sees no error messages
When the user clicks finish edit button and ends the write off edition
And the user logs out

Scenario: Write off edit Validation - cause 1001 symbols

Given there is the write off with 'WriteOff-Edit-Val-44' number with product 'Name-WOV-QIR' with quantity '10', price '15' and cause 'Причина'
And the user navigates to the write off with number 'WriteOff-Edit-Val-44'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff cause review' element of write off product with 'Name-WOV-QIR' sku to edit
And the user generates charData with '1001' number in the 'inline writeOff cause' write off field
Then the user checks 'inline writeOff cause' write off field contains only '1001' symbols
When the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Не более 1000 |
When the user clicks Cancel and discard changes
And the user clicks finish edit button and ends the write off edition
And the user logs out

