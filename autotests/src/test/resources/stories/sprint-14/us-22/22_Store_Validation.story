22 Создание/редактирование/просмотр магазина: Валидация

Narrative:
In order to управлять торговой сетью
As a коммерческий директор
I want to создавать, редактировать и просмотривать магазины торговой сети

Meta:
@sprint 14
@us 22
@debug us:22:validation

Scenario: Invalid store number

Given there is created store and user starts to edit it and fills form with
| elementName | value |
| number | @ $% |
Then the user sees error messages
| error message |
| Значение недопустимо. |

Scenario: Empty store number

Given there is created store and user starts to edit it and fills form with
| elementName | value |
| number |  |
Then the user sees error messages
| error message |
| Заполните это поле |

Scenario: Too long store number

Given there is created store and user starts to edit it and fills form with
| elementName | value | repeat |
| number | 0123456789 | 6 |
Then the user sees error messages
| error message |
| Не более 50 символов |

Scenario: Duplicate store number

Given there is created store with number 'storeExists', address 'address', contacts 'contacts'
And there is created store and user starts to edit it and fills form with
| elementName | value |
| number | storeExists |
Then the user sees error messages
| error message |
| Такой магазин уже есть |

Scenario: Empty store address

Given there is created store and user starts to edit it and fills form with
| elementName | value |
| address | |
Then the user sees error messages
| error message |
| Заполните это поле |

Scenario: Too long store address

Given there is created store and user starts to edit it and fills form with
| elementName | value | repeat |
| address | Миру - мир  | 31 |
Then the user sees error messages
| error message |
| Не более 300 символов |

Scenario: Empty store contacts

Given there is created store and user starts to edit it and fills form with
| elementName | value |
| contacts | |
Then the user sees error messages
| error message |
| Заполните это поле |

Scenario: Too long store contacts

Given there is created store and user starts to edit it and fills form with
| elementName | value | repeat |
| contacts | Миру - мир  | 11 |
Then the user sees error messages
| error message |
| Не более 100 символов |