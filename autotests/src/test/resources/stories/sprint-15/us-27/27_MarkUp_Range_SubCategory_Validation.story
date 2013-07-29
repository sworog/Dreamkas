Meta:
@sprint 15
@us 27

Scenario: subCategory mark up properties validation - min mark up validation good

Given the user validates 'min' mark up with '1' value of subCategory with name 'subCategoryMarkUp-valid' of category with name 'CategoryMarkUp-valid' of group with name 'GroupMarkUp-valid'
Then the user sees success message 'Свойства успешно сохранены' and logs out

Scenario: subCategory mark up properties validation - min mark up validation eng small register

Given the user validates 'min' mark up with 'abc' value of subCategory with name 'subCategoryMarkUp-valid' of category with name 'CategoryMarkUp-valid' of group with name 'GroupMarkUp-valid'
Then the user sees error message and logs out
| error message|
| Значение должно быть числом |

Scenario: subCategory mark up properties validation - min mark up validation eng big register

Given the user validates 'min' mark up with 'ABC' value of subCategory with name 'subCategoryMarkUp-valid' of category with name 'CategoryMarkUp-valid' of group with name 'GroupMarkUp-valid'
Then the user sees error message and logs out
| error message|
| Значение должно быть числом |

Scenario: subCategory mark up properties validation - min mark up validation rus small register

Given the user validates 'min' mark up with 'абв' value of subCategory with name 'subCategoryMarkUp-valid' of category with name 'CategoryMarkUp-valid' of group with name 'GroupMarkUp-valid'
Then the user sees error message and logs out
| error message|
| Значение должно быть числом |

Scenario: subCategory mark up properties validation - min mark up validation rus big register

Given the user validates 'min' mark up with 'АБВ' value of subCategory with name 'subCategoryMarkUp-valid' of category with name 'CategoryMarkUp-valid' of group with name 'GroupMarkUp-valid'
Then the user sees error message and logs out
| error message|
| Значение должно быть числом |

Scenario: subCategory mark up properties validation - min mark up validation symbols

Given the user validates 'min' mark up with '!"№;%:?*()' value of subCategory with name 'subCategoryMarkUp-valid' of category with name 'CategoryMarkUp-valid' of group with name 'GroupMarkUp-valid'
Then the user sees error message and logs out
| error message|
| Значение должно быть числом |

Scenario: subCategory mark up properties validation - min mark up validation - Boundary-value analysis -99

Given the user validates 'min' mark up with '-99' value of subCategory with name 'subCategoryMarkUp-valid' of category with name 'CategoryMarkUp-valid' of group with name 'GroupMarkUp-valid'
Then the user sees success message 'Свойства успешно сохранены' and logs out

Scenario: subCategory mark up properties validation - min mark up validation - Boundary-value analysis -99.99

Given the user validates 'min' mark up with '-99.99' value of subCategory with name 'subCategoryMarkUp-valid' of category with name 'CategoryMarkUp-valid' of group with name 'GroupMarkUp-valid'
Then the user sees success message 'Свойства успешно сохранены' and logs out

Scenario: subCategory mark up properties validation - min mark up validation - Boundary-value analysis -100

Given the user validates 'min' mark up with '-100' value of subCategory with name 'subCategoryMarkUp-valid' of category with name 'CategoryMarkUp-valid' of group with name 'GroupMarkUp-valid'
Then the user sees error message and logs out
| error message|
| Значение должно быть больше -100 |

Scenario: subCategory mark up properties validation - min mark up validation - Boundary-value analysis -101

Given the user validates 'min' mark up with '-101' value of subCategory with name 'subCategoryMarkUp-valid' of category with name 'CategoryMarkUp-valid' of group with name 'GroupMarkUp-valid'
Then the user sees error message and logs out
| error message|
| Значение должно быть больше -100 |

Scenario: subCategory mark up properties validation - max mark up validation good

Given the user validates 'max' mark up with '1' value of subCategory with name 'subCategoryMarkUp-valid' of category with name 'CategoryMarkUp-valid' of group with name 'GroupMarkUp-valid'
Then the user sees success message 'Свойства успешно сохранены' and logs out

Scenario: subCategory mark up properties validation - max mark up validation eng small register

Given the user validates 'max' mark up with 'abc' value of subCategory with name 'subCategoryMarkUp-valid' of category with name 'CategoryMarkUp-valid' of group with name 'GroupMarkUp-valid'
Then the user sees error message and logs out
| error message|
| Значение должно быть числом |

Scenario: subCategory mark up properties validation - max mark up validation eng big register

Given the user validates 'max' mark up with 'ABC' value of subCategory with name 'subCategoryMarkUp-valid' of category with name 'CategoryMarkUp-valid' of group with name 'GroupMarkUp-valid'
Then the user sees error message and logs out
| error message|
| Значение должно быть числом |

Scenario: subCategory mark up properties validation - max mark up validation rus small register

Given the user validates 'max' mark up with 'абв' value of subCategory with name 'subCategoryMarkUp-valid' of category with name 'CategoryMarkUp-valid' of group with name 'GroupMarkUp-valid'
Then the user sees error message and logs out
| error message|
| Значение должно быть числом |

Scenario: subCategory mark up properties validation - max mark up validation rus big register

Given the user validates 'max' mark up with 'АБВ' value of subCategory with name 'subCategoryMarkUp-valid' of category with name 'CategoryMarkUp-valid' of group with name 'GroupMarkUp-valid'
Then the user sees error message and logs out
| error message|
| Значение должно быть числом |

Scenario: subCategory mark up properties validation - max mark up validation symbols

Given the user validates 'max' mark up with 'Ё!"№;%:?*()_' value of subCategory with name 'subCategoryMarkUp-valid' of category with name 'CategoryMarkUp-valid' of group with name 'GroupMarkUp-valid'
Then the user sees error message and logs out
| error message|
| Значение должно быть числом |

Scenario: subCategory mark up properties validation - max mark up validation - Boundary-value analysis -99

Given the user validates 'max' mark up with '-99' value of subCategory with name 'subCategoryMarkUp-valid' of category with name 'CategoryMarkUp-valid' of group with name 'GroupMarkUp-valid'
Then the user sees success message 'Свойства успешно сохранены' and logs out

Scenario: subCategory mark up properties validation - max mark up validation - Boundary-value analysis -99.99

Given the user validates 'max' mark up with '-99.99' value of subCategory with name 'subCategoryMarkUp-valid' of category with name 'CategoryMarkUp-valid' of group with name 'GroupMarkUp-valid'
Then the user sees success message 'Свойства успешно сохранены' and logs out

Scenario: subCategory mark up properties validation - max mark up validation - Boundary-value analysis -100

Given the user validates 'max' mark up with '-100' value of subCategory with name 'subCategoryMarkUp-valid' of category with name 'CategoryMarkUp-valid' of group with name 'GroupMarkUp-valid'
Then the user sees error message and logs out
| error message|
| Значение должно быть больше -100 |

Scenario: subCategory mark up properties validation - max mark up validation - Boundary-value analysis -101

Given the user validates 'max' mark up with '-101' value of subCategory with name 'subCategoryMarkUp-valid' of category with name 'CategoryMarkUp-valid' of group with name 'GroupMarkUp-valid'
Then the user sees error message and logs out
| error message|
| Значение должно быть больше -100 |

Scenario: subCategory mark up properties validation - min mark up cant be more than max mark up

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
When the user clicks on end edition link and ends the edition
And the user logs out
