Meta:
@sprint 15
@us 27

Scenario: SubCategory - mark up validation good

Given there is the subCategory with name 'subCategoryMarkUp-valid' related to group named 'GroupMarkUp-valid' and category named 'CategoryMarkUp-valid'
And the user navigates to the subCategory 'subCategoryMarkUp-valid', category 'CategoryMarkUp-valid', group 'GroupMarkUp-valid' product list page
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user switches to 'subCategory' properties tab
And the user sets <markUpType> with <value>
And the user clicks save mark up button
Then the user sees success message 'Свойства успешно сохранены'

Examples:
| markUpType | value |
| min | 1 |
| max | 1 |

Scenario: SubCategory - mark up validation required fields

Given there is the subCategory with name 'subCategoryMarkUp-valid' related to group named 'GroupMarkUp-valid' and category named 'CategoryMarkUp-valid'
And the user navigates to the subCategory 'subCategoryMarkUp-valid', category 'CategoryMarkUp-valid', group 'GroupMarkUp-valid' product list page
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user switches to 'subCategory' properties tab
And the user sets <markUpType> with <value>
And the user clicks save mark up button
Then the user sees error messages
| error message |
| Заполните это поле |

Examples:
| markUpType | value |
| min |  |
| max |  |

Scenario: SubCategory - mark up validation negative

Given there is the subCategory with name 'subCategoryMarkUp-valid' related to group named 'GroupMarkUp-valid' and category named 'CategoryMarkUp-valid'
And the user navigates to the subCategory 'subCategoryMarkUp-valid', category 'CategoryMarkUp-valid', group 'GroupMarkUp-valid' product list page
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user switches to 'subCategory' properties tab
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

Scenario: SubCategory - min mark up cant be more than max mark up

Given there is the subCategory with name 'subCategoryMarkUp-valid' related to group named 'GroupMarkUp-valid' and category named 'CategoryMarkUp-valid'
And the user navigates to the subCategory 'subCategoryMarkUp-valid', category 'CategoryMarkUp-valid', group 'GroupMarkUp-valid' product list page
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user switches to 'subCategory' properties tab
And the user sets min mark up value to '2'
And the user sets max mark up value to '1'
And the user clicks save mark up button
Then the user sees error messages
| error message|
| Минимальная наценка не может быть больше максимальной |
