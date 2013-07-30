Meta:
@sprint 15
@us 27

Scenario: category mark up properties validation - min mark up validation good

Given the user validates 'min' mark up with '1' value of category with name 'CategoryMarkUp-valid' of group with name 'GroupMarkUp-valid'
Then the user sees success message 'Свойства успешно сохранены' and logs out

Scenario: category mark up properties validation - min mark up validation eng small register

Given the user validates 'min' mark up with 'abc' value of category with name 'CategoryMarkUp-valid' of group with name 'GroupMarkUp-valid'
Then the user sees error message and logs out
| error message|
| Значение должно быть числом |

Scenario: category mark up properties validation - min mark up validation eng big register

Given the user validates 'min' mark up with 'ABC' value of category with name 'CategoryMarkUp-valid' of group with name 'GroupMarkUp-valid'
Then the user sees error message and logs out
| error message|
| Значение должно быть числом |

Scenario: category mark up properties validation - min mark up validation rus small register

Given the user validates 'min' mark up with 'абв' value of category with name 'CategoryMarkUp-valid' of group with name 'GroupMarkUp-valid'
Then the user sees error message and logs out
| error message|
| Значение должно быть числом |

Scenario: category mark up properties validation - min mark up validation rus big register

Given the user validates 'min' mark up with 'АБВ' value of category with name 'CategoryMarkUp-valid' of group with name 'GroupMarkUp-valid'
Then the user sees error message and logs out
| error message|
| Значение должно быть числом |

Scenario: category mark up properties validation - min mark up validation symbols

Given the user validates 'min' mark up with '!"№;%:?*()' value of category with name 'CategoryMarkUp-valid' of group with name 'GroupMarkUp-valid'
Then the user sees error message and logs out
| error message|
| Значение должно быть числом |

Scenario: category mark up properties validation - min mark up validation - Boundary-value analysis -0.01

Given the user validates 'min' mark up with '-0.01' value of category with name 'CategoryMarkUp-valid' of group with name 'GroupMarkUp-valid'
Then the user sees error message and logs out
| error message|
| Значение должно быть больше или равно 0 |

Scenario: category mark up properties validation - min mark up validation - Boundary-value analysis -1

Given the user validates 'min' mark up with '-1' value of category with name 'CategoryMarkUp-valid' of group with name 'GroupMarkUp-valid'
Then the user sees error message and logs out
| error message|
| Значение должно быть больше или равно 0 |

Scenario: category mark up properties validation - max mark up validation good

Given the user validates 'max' mark up with '1' value of category with name 'CategoryMarkUp-valid' of group with name 'GroupMarkUp-valid'
Then the user sees success message 'Свойства успешно сохранены' and logs out

Scenario: category mark up properties validation - max mark up validation eng small register

Given the user validates 'max' mark up with 'abc' value of category with name 'CategoryMarkUp-valid' of group with name 'GroupMarkUp-valid'
Then the user sees error message and logs out
| error message|
| Значение должно быть числом |

Scenario: category mark up properties validation - max mark up validation eng big register

Given the user validates 'max' mark up with 'ABC' value of category with name 'CategoryMarkUp-valid' of group with name 'GroupMarkUp-valid'
Then the user sees error message and logs out
| error message|
| Значение должно быть числом |

Scenario: category mark up properties validation - max mark up validation rus small register

Given the user validates 'max' mark up with 'абв' value of category with name 'CategoryMarkUp-valid' of group with name 'GroupMarkUp-valid'
Then the user sees error message and logs out
| error message|
| Значение должно быть числом |

Scenario: category mark up properties validation - max mark up validation rus big register

Given the user validates 'max' mark up with 'АБВ' value of category with name 'CategoryMarkUp-valid' of group with name 'GroupMarkUp-valid'
Then the user sees error message and logs out
| error message|
| Значение должно быть числом |

Scenario: category mark up properties validation - max mark up validation symbols

Given the user validates 'max' mark up with '!"№;%:?*()' value of category with name 'CategoryMarkUp-valid' of group with name 'GroupMarkUp-valid'
Then the user sees error message and logs out
| error message|
| Значение должно быть числом |

Scenario: category mark up properties validation - max mark up validation - Boundary-value analysis -0.01

Given the user validates 'max' mark up with '-0.01' value of category with name 'CategoryMarkUp-valid' of group with name 'GroupMarkUp-valid'
Then the user sees error message and logs out
| error message|
| Значение должно быть больше или равно 0 |

Scenario: category mark up properties validation - max mark up validation - Boundary-value analysis -1

Given the user validates 'max' mark up with '-1' value of category with name 'CategoryMarkUp-valid' of group with name 'GroupMarkUp-valid'
Then the user sees error message and logs out
| error message|
| Значение должно быть больше или равно 0 |

Scenario: category mark up properties validation - min mark up cant be more than max mark up

Given there is the category with name 'CategoryMarkUp-valid' related to group named 'GroupMarkUp-valid'
And the user navigates to the category with name 'CategoryMarkUp-valid' related to group named 'GroupMarkUp-valid'
And the user logs in as 'commercialManager'
When the user clicks on start edition link and starts the edition
And the user switches to 'category' properties tab
And the user sets min mark up value to '2'
And the user sets max mark up value to '1'
And the user clicks save mark up button
Then the user sees error messages
| error message|
| Минимальная наценка не может быть больше максимальной |
When the user clicks on end edition link and ends the edition
And the user logs out
