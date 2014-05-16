Meta:
@sprint_15
@us_27

Scenario: Group - mark up validation good

Given there is the group with name 'GroupMarkUp-valid'
And the user navigates to the group with name 'GroupMarkUp-valid'
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user switches to 'group' properties tab
And the user sets <markUpType> with <value>
And the user clicks save mark up button
Then the user sees success message 'Свойства успешно сохранены'

Examples:
| markUpType | value |
| min | 1 |
| max | 1 |

Scenario: Group - mark up validation required fields

Given there is the group with name 'GroupMarkUp-valid'
And the user navigates to the group with name 'GroupMarkUp-valid'
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user switches to 'group' properties tab
And the user sets <markUpType> with <value>
And the user clicks save mark up button
Then the user sees error messages
| error message |
| Заполните это поле |

Examples:
| markUpType | value |
| min |  |
| max |  |

Scenario: Group - mark up validation negative

Given there is the group with name 'GroupMarkUp-valid'
And the user navigates to the group with name 'GroupMarkUp-valid'
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user switches to 'group' properties tab
And the user sets <markUpType> with <value>
And the user clicks save mark up button
Then the user user sees <errorMessage>

Examples:
| markUpType | value | errorMessage |
| min | abc | Значение должно быть числом |
| min | ABC | Значение должно быть числом |
| min | абв | Значение должно быть числом |
| min | АБВ | Значение должно быть числом |
| min | !"№;%:?*() | Значение должно быть числом |
| min | -0.01 | Значение должно быть больше или равно 0 |
| min | -1 | Значение должно быть больше или равно 0 |
| max | abc | Значение должно быть числом |
| max | ABC | Значение должно быть числом |
| max | абв | Значение должно быть числом |
| max | АБВ | Значение должно быть числом |
| max | !"№;%:?*() | Значение должно быть числом |
| max | -0.01 | Значение должно быть больше или равно 0 |
| max | -1 | Значение должно быть больше или равно 0 |

Scenario: Group mark up properties validation - min mark up cant be more than max mark up

Given there is the group with name 'GroupMarkUp-valid'
And the user navigates to the group with name 'GroupMarkUp-valid'
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user switches to 'group' properties tab
And the user sets min mark up value to '2'
And the user sets max mark up value to '1'
And the user clicks save mark up button
Then the user sees error messages
| error message|
| Минимальная наценка не может быть больше максимальной |