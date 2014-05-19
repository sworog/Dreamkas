Meta:
@us_70
@sprint_34

Narrative:
Как комерческий директор
Я хочу при добавлении алкоголя ввести все необходимые данные,
Чтобы товар можно было продавать в магазинах по всем правилам

Крепость - 0 <= x <100, один знак после запятой, число (Decimal), не обязательное
Объем тары - x >0, три знака после запятой, число (Decimal), не обязательное

Scenario: Product create alcoholByVolume field positive validation

Meta:
@id_s34u70s4

Given the user is on the product create page
And the user logs in as 'commercialManager'

When the user selects product type 'Алкоголь'
And the user inputs <productName> in name element field
And the user selects '10' in 'vat' element dropdown
And the user inputs <exampleValue> in <exampleElement> element field
When the user clicks the create button

Then the user sees no error messages

And the user checks the product with <productName> name is present

Examples:
| exampleElement | exampleValue | productName |
| alcoholByVolume | 0 | s34u70s07e05.0 |
| alcoholByVolume | 0,1 | s34u70s07e05.1 |
| alcoholByVolume | 0.1 | s34u70s07e05.2 |
| alcoholByVolume | 1 | s34u70s07e05.3 |
| alcoholByVolume | 99.9 | s34u70s07e05.4 |
| alcoholByVolume | 99,9 | s34u70s07e05.5 |
| alcoholByVolume | 40 | s34u70s07e05.6 |
| alcoholByVolume | 40,0 | s34u70s07e05.7 |
| alcoholByVolume | 40.0 | s34u70s07e05.8 |


Scenario: Product create alcoholByVolume field negative validation

Meta:
@id_s34u70s5

Given the user is on the product create page
And the user logs in as 'commercialManager'

When the user selects product type 'Алкоголь'
And the user inputs <productName> in name element field
And the user selects '10' in 'vat' element dropdown
And the user inputs <exampleValue> in <exampleElement> element field

When the user clicks the create button

Then the user checks the element field 'alcoholByVolume' has errorMessage

Examples:
| exampleElement | exampleValue | errorMessage | productName |
| alcoholByVolume | 0,0 | Сообщение об ошибке | s34u70s07e04.1 |
| alcoholByVolume | 0.0 | Сообщение об ошибке | s34u70s07e04.2 |
| alcoholByVolume | -0,1 | Сообщение об ошибке | s34u70s07e04.3 |
| alcoholByVolume | -0.1 | Сообщение об ошибке | s34u70s07e04.4 |
| alcoholByVolume | -1 | Сообщение об ошибке | s34u70s07e04.5 |
| alcoholByVolume | 1,12 | Сообщение об ошибке | s34u70s07e04.6 |
| alcoholByVolume | 1.12 | Сообщение об ошибке | s34u70s07e04.7 |
| alcoholByVolume | 100 | Сообщение об ошибке | s34u70s07e04.8 |
| alcoholByVolume | 101 | Сообщение об ошибке | s34u70s07e04.9 |
| alcoholByVolume | alco | Сообщение об ошибке | s34u70s07e04.10 |
| alcoholByVolume | ALCO | Сообщение об ошибке | s34u70s07e04.11 |
| alcoholByVolume | алко | Сообщение об ошибке | s34u70s07e04.12 |
| alcoholByVolume | АЛКО | Сообщение об ошибке | s34u70s07e04.13 |
| alcoholByVolume | !"№;%:?*() | Сообщение об ошибке | s34u70s07e04.14 |
| alcoholByVolume | 1 1 | Сообщение об ошибке | s34u70s07e04.15 |
| alcoholByVolume | вы33434№4 | Сообщение об ошибке | s34u70s07e04.16 |

Scenario: Product create volume field positive validation

Meta:
@id_s34u70s6

Given the user is on the product create page
And the user logs in as 'commercialManager'

When the user selects product type 'Алкоголь'
And the user inputs <productName> in name element field
And the user selects '10' in 'vat' element dropdown
And the user inputs <exampleValue> in <exampleElement> element field
When the user clicks the create button

Then the user sees no error messages

And the user checks the product with <productName> name is present

Examples:
| exampleElement | exampleValue | productName |
| volume | 1 | s34u70s07d05.0 |
| volume | 100,000 | s34u70s07d05.1 |
| volume | 1,123 | s34u70s07d05.2 |
| volume | 1.123 | s34u70s07d05.3 |
| volume | 1.1 | s34u70s07d05.4 |
| volume | 1,1 | s34u70s07d05.5 |
| volume | 1,12 | s34u70s07d05.6 |
| volume | 1.12 | s34u70s07d05.7 |
| volume | 1.13 | s34u70s07d05.8 |
| volume | 1,13 | s34u70s07d05.9 |


Scenario: Product create volume field negative validation

Meta:
@id_s34u70s7

Given the user is on the product create page
And the user logs in as 'commercialManager'

When the user selects product type 'Алкоголь'
And the user inputs <productName> in name element field
And the user selects '10' in 'vat' element dropdown
And the user inputs <exampleValue> in <exampleElement> element field

When the user clicks the create button

Then the user checks the element field 'volume' has errorMessage

Examples:
| exampleElement | exampleValue | errorMessage | productName |
| volume | 0 | Сообщение об ошибке | s34u70s07d04.1 |
| volume | 0,123 | Сообщение об ошибке | s34u70s07d04.2 |
| volume | 0,999 | Сообщение об ошибке | s34u70s07d04.3 |
| volume | -1 | Сообщение об ошибке | s34u70s07d04.4 |
| volume | -1 | Сообщение об ошибке | s34u70s07d04.5 |
| volume | 1,1234 | Сообщение об ошибке | s34u70s07d04.6 |
| volume | 1.1234 | Сообщение об ошибке | s34u70s07d04.7 |
| volume | alco | Сообщение об ошибке | s34u70s07d04.8 |
| volume | ALCO | Сообщение об ошибке | s34u70s07d04.9 |
| volume | алко | Сообщение об ошибке | s34u70s07d04.10 |
| volume | АЛКО | Сообщение об ошибке | s34u70s07d04.11 |
| volume | !"№;%:?*() | Сообщение об ошибке | s34u70s07d04.12 |
| volume | 1 1 | Сообщение об ошибке | s34u70s07d04.13 |
| volume | вы33434№4 | Сообщение об ошибке | s34u70s07d04.14 |