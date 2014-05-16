Meta:
@us_70
@sprint_34

Narrative:
Как комерческий директор
Я хочу при добавлении алкоголя ввести все необходимые данные,
Чтобы товар можно было продавать в магазинах по всем правилам

Крепость - 0 <= x <100, один знак после запятой, число (Decimal), не обязательное
Объем тары - x >0, три знака после запятой, число (Decimal), не обязательное

Scenario: Product edit alcoholByVolume field positive validation

Meta:
@id_s34u70s8

Given there is the product with <productName> name, 'alcohol' type
And the user navigates to the product with name <productName>
And the user logs in as 'commercialManager'

When the user inputs <exampleValue> in <exampleElement> element field

When the user clicks the create button

Then the user sees no error messages

And the user checks the product with <productName> name is present

Examples:
| exampleElement | exampleValue | productName |
| alcoholByVolume | 0 | s34u70s07e06.0 |
| alcoholByVolume | 0,1 | s34u70s07e06.1 |
| alcoholByVolume | 0.1 | s34u70s07e06.2 |
| alcoholByVolume | 1 | s34u70s07e06.3 |
| alcoholByVolume | 99.9 | s34u70s07e06.4 |
| alcoholByVolume | 99,9 | s34u70s07e06.5 |
| alcoholByVolume | 40 | s34u70s07e06.6 |
| alcoholByVolume | 40,0 | s34u70s07e06.7 |
| alcoholByVolume | 40.0 | s34u70s07e06.8 |


Scenario: Product edit alcoholByVolume field negative validation

Meta:
@id_s34u70s9

Given there is the product with <productName> name, 'alcohol' type
And the user navigates to the product with name <productName>
And the user logs in as 'commercialManager'

When the user inputs <exampleValue> in <exampleElement> element field

When the user clicks the create button

Then the user checks the element field 'alcoholByVolume' has errorMessage

Examples:
| exampleElement | exampleValue | errorMessage | productName |
| alcoholByVolume | 0,0 | Сообщение об ошибке | s34u70s07e06.1 |
| alcoholByVolume | 0.0 | Сообщение об ошибке | s34u70s07e06.2 |
| alcoholByVolume | -0,1 | Сообщение об ошибке | s34u70s07e06.3 |
| alcoholByVolume | -0.1 | Сообщение об ошибке | s34u70s07e06.4 |
| alcoholByVolume | -1 | Сообщение об ошибке | s34u70s07e06.5 |
| alcoholByVolume | 1,12 | Сообщение об ошибке | s34u70s07e06.6 |
| alcoholByVolume | 1.12 | Сообщение об ошибке | s34u70s07e06.7 |
| alcoholByVolume | 100 | Сообщение об ошибке | s34u70s07e06.8 |
| alcoholByVolume | 101 | Сообщение об ошибке | s34u70s07e06.9 |
| alcoholByVolume | alco | Сообщение об ошибке | s34u70s07e06.10 |
| alcoholByVolume | ALCO | Сообщение об ошибке | s34u70s07e06.11 |
| alcoholByVolume | алко | Сообщение об ошибке | s34u70s07e06.12 |
| alcoholByVolume | АЛКО | Сообщение об ошибке | s34u70s07e06.13 |
| alcoholByVolume | !"№;%:?*() | Сообщение об ошибке | s34u70s07e06.14 |
| alcoholByVolume | 1 1 | Сообщение об ошибке | s34u70s07e06.15 |
| alcoholByVolume | вы33434№4 | Сообщение об ошибке | s34u70s07e06.16 |

Scenario: Product create volume field positive validation

Meta:
@id_s34u70s10

Given there is the product with <productName> name, 'alcohol' type
And the user navigates to the product with name <productName>
And the user logs in as 'commercialManager'

When the user inputs <exampleValue> in <exampleElement> element field

When the user clicks the create button

Then the user sees no error messages

And the user checks the product with <productName> name is present

Examples:
| exampleElement | exampleValue | productName |
| volume | 1 | s34u70s07d06.0 |
| volume | 100,000 | s34u70s07d06.1 |
| volume | 1,123 | s34u70s07d06.2 |
| volume | 1.123 | s34u70s07d06.3 |
| volume | 1.1 | s34u70s07d06.4 |
| volume | 1,1 | s34u70s07d06.5 |
| volume | 1,12 | s34u70s07d06.6 |
| volume | 1.12 | s34u70s07d06.7 |
| volume | 1.13 | s34u70s07d06.8 |
| volume | 1,13 | s34u70s07d06.9 |


Scenario: Product create volume field negative validation

Meta:
@id_s34u70s11

Given there is the product with <productName> name, 'alcohol' type
And the user navigates to the product with name <productName>
And the user logs in as 'commercialManager'

When the user inputs <exampleValue> in <exampleElement> element field

When the user clicks the create button

Then the user checks the element field 'volume' has errorMessage

Examples:
| exampleElement | exampleValue | errorMessage | productName |
| volume | 0 | Сообщение об ошибке | s34u70s07d07.1 |
| volume | 0,123 | Сообщение об ошибке | s34u70s07d07.2 |
| volume | 0,999 | Сообщение об ошибке | s34u70s07d07.3 |
| volume | -1 | Сообщение об ошибке | s34u70s07d07.4 |
| volume | -1 | Сообщение об ошибке | s34u70s07d07.5 |
| volume | 1,1234 | Сообщение об ошибке | s34u70s07d07.6 |
| volume | 1.1234 | Сообщение об ошибке | s34u70s07d07.7 |
| volume | alco | Сообщение об ошибке | s34u70s07d07.8 |
| volume | ALCO | Сообщение об ошибке | s34u70s07d07.9 |
| volume | алко | Сообщение об ошибке | s34u70s07d07.10 |
| volume | АЛКО | Сообщение об ошибке | s34u70s07d07.11 |
| volume | !"№;%:?*() | Сообщение об ошибке | s34u70s07d07.12 |
| volume | 1 1 | Сообщение об ошибке | s34u70s07d07.13 |
| volume | вы33434№4 | Сообщение об ошибке | s34u70s07d07.14 |