8.2 Валидация вводимых данных при создании накладной без данных о товаре

Narrative:
Правила валидации:

1. Номер приёмки - обязательное поле. строка до 100 символов
2. Поставщик - обязательное поле (строка до 300 символов)
3. Дата и время приёмки - обязательное поле. подставляется автоматически при открытии страницы.
При клике на поле появляется дата пикер.
4. Кто принял - обязательное поле (строка до 100 символов)
5. Получатель - обязательное поле (строка до 300 символов)
6. Номер входящей накладной - необязательное поле. строка до 100 символов.
7. Дата входящей накладной - необязательное поле. При клике на поле появляется дата пикер.

Scenario: Invoice sku validation is required
Given the user is on the invoice list page
When the user clicks the create button on the invoice list page
And the user inputs '!todayDateAndTime' in the invoice 'acceptanceDate' field
And the user inputs 'Валидация поставщик' in the invoice 'supplier' field
And the user inputs 'Валидация кто принял' in the invoice 'accepter' field
And the user inputs 'Валидация получатель' in the invoice 'legalEntity' field
And the user navigates to invoice product addition
Then the user sees error messages
| error message |
| Заполните это поле |

Scenario: Invoice sku validation good
Given the user is on the invoice list page
When the user clicks the create button on the invoice list page
And the user generates charData with '100' number in the 'sku' invoice field
Then the user checks 'sku' invoice field contains only '100' symbols
When the user inputs '!todayDateAndTime' in the invoice 'acceptanceDate' field
And the user inputs 'Валидация поставщик' in the invoice 'supplier' field
And the user inputs 'Валидация кто принял' in the invoice 'accepter' field
And the user inputs 'Валидация получатель' in the invoice 'legalEntity' field
And the user navigates to invoice product addition
Then the user sees no error messages

Scenario: Invoice sku validation negative
Given the user is on the invoice list page
When the user clicks the create button on the invoice list page
And the user generates charData with '101' number in the 'sku' invoice field
Then the user checks 'sku' invoice field contains only '101' symbols
When the user inputs '!todayDateAndTime' in the invoice 'acceptanceDate' field
And the user inputs 'Валидация поставщик' in the invoice 'supplier' field
And the user inputs 'Валидация кто принял' in the invoice 'accepter' field
And the user inputs 'Валидация получатель' in the invoice 'legalEntity' field
And the user navigates to invoice product addition
Then the user sees error messages
| error message |
| Не более 100 символов |

Scenario: Invoice Supplier validation is required
Given the user is on the invoice list page
When the user clicks the create button on the invoice list page
And the user inputs 'ISVIR-01' in the invoice 'sku' field
And the user inputs '!todayDateAndTime' in the invoice 'acceptanceDate' field
And the user inputs 'Валидация кто принял' in the invoice 'accepter' field
And the user inputs 'Валидация получатель' in the invoice 'legalEntity' field
And the user navigates to invoice product addition
Then the user sees error messages
| error message |
| Заполните это поле |

Scenario: Invoice Supplier validation good
Given the user is on the invoice list page
When the user clicks the create button on the invoice list page
And the user inputs 'ISVG-01' in the invoice 'sku' field
And the user generates charData with '300' number in the 'supplier' invoice field
Then the user checks 'supplier' invoice field contains only '300' symbols
When the user inputs '!todayDateAndTime' in the invoice 'acceptanceDate' field
And the user inputs 'Валидация кто принял' in the invoice 'accepter' field
And the user inputs 'Валидация получатель' in the invoice 'legalEntity' field
And the user navigates to invoice product addition
Then the user sees no error messages

Scenario: Invoice Supplier validation negative
Given the user is on the invoice list page
When the user clicks the create button on the invoice list page
And the user inputs 'ISVN-01' in the invoice 'sku' field
And the user generates charData with '301' number in the 'supplier' invoice field
Then the user checks 'supplier' invoice field contains only '301' symbols
When the user inputs '!todayDateAndTime' in the invoice 'acceptanceDate' field
And the user inputs 'Валидация кто принял' in the invoice 'accepter' field
And the user inputs 'Валидация получатель' in the invoice 'legalEntity' field
And the user navigates to invoice product addition
Then the user sees error messages
| error message |
| Не более 300 символов |

Scenario: Invoice acceptanceDate validation is required
Given the user is on the invoice list page
When the user clicks the create button on the invoice list page
And the user inputs '89654464645' in the invoice 'sku' field
And the user inputs '!' in the invoice 'acceptanceDate' field
And the user inputs 'Валидация поставщик' in the invoice 'supplier' field
And the user inputs 'Валидация кто принял' in the invoice 'accepter' field
And the user inputs 'Валидация получатель' in the invoice 'legalEntity' field
And the user navigates to invoice product addition
Then the user sees error messages
| error message |
| Заполните это поле |

Scenario: Invoice acceptanceDate autofilling
Given the user is on the invoice list page
When the user clicks the create button on the invoice list page
Then the user checks the 'acceptanceDate' is prefilled and equals NowDate

Scenario: Invoice acceptanceDate validation good manual
Given the user is on the invoice list page
When the user clicks the create button on the invoice list page
And the user inputs '89654464645' in the invoice 'sku' field
And the user inputs '!03.12.2012 10:45' in the invoice 'acceptanceDate' field
And the user inputs 'Валидация поставщик' in the invoice 'supplier' field
And the user inputs 'Валидация кто принял' in the invoice 'accepter' field
And the user inputs 'Валидация получатель' in the invoice 'legalEntity' field
And the user navigates to invoice product addition
Then the user sees no error messages

Scenario: Invoice acceptanceDate validation manual negative1 numbers
Given the user is on the invoice list page
When the user clicks the create button on the invoice list page
And the user inputs 'IAVMN1' in the invoice 'sku' field
And the user inputs '!123454567890' in the invoice 'acceptanceDate' field
And the user inputs 'Валидация поставщик' in the invoice 'supplier' field
And the user inputs 'Валидация кто принял' in the invoice 'accepter' field
And the user inputs 'Валидация получатель' in the invoice 'legalEntity' field
And the user navigates to invoice product addition
Then the user sees error messages
| error message |
| Вы ввели неверную дату 12.34.5456 78:90, формат должен быть следующий дд.мм.гггг чч:мм |

Scenario: Invoice acceptanceDate validation manual negative1 numbers 2
Given the user is on the invoice list page
When the user clicks the create button on the invoice list page
And the user inputs 'IAVMN1' in the invoice 'sku' field
And the user inputs '!12345456789' in the invoice 'acceptanceDate' field
And the user inputs 'Валидация поставщик' in the invoice 'supplier' field
And the user inputs 'Валидация кто принял' in the invoice 'accepter' field
And the user inputs 'Валидация получатель' in the invoice 'legalEntity' field
And the user navigates to invoice product addition
Then the user sees error messages
| error message |
| Заполните это поле |

Scenario: Invoice acceptanceDate validation manual negative2 eng symbols
Given the user is on the invoice list page
When the user clicks the create button on the invoice list page
And the user inputs 'IAVMN2' in the invoice 'sku' field
And the user inputs '!HAasdfsfsfsf' in the invoice 'acceptanceDate' field
And the user inputs 'Валидация поставщик' in the invoice 'supplier' field
And the user inputs 'Валидация кто принял' in the invoice 'accepter' field
And the user inputs 'Валидация получатель' in the invoice 'legalEntity' field
And the user navigates to invoice product addition
Then the user sees error messages
| error message |
| Заполните это поле |

Scenario: Invoice acceptanceDate validation manual negative3 rus symbols
Given the user is on the invoice list page
When the user clicks the create button on the invoice list page
And the user inputs 'IAVMN2' in the invoice 'sku' field
And the user inputs '!Русский набор' in the invoice 'acceptanceDate' field
And the user inputs 'Валидация поставщик' in the invoice 'supplier' field
And the user inputs 'Валидация кто принял' in the invoice 'accepter' field
And the user inputs 'Валидация получатель' in the invoice 'legalEntity' field
And the user navigates to invoice product addition
Then the user sees error messages
| error message |
| Заполните это поле |

Scenario: Invoice acceptanceDate validation manual negative symbols
Given the user is on the invoice list page
When the user clicks the create button on the invoice list page
And the user inputs 'IAVMN2' in the invoice 'sku' field
And the user inputs '!"№;%:?*()_+' in the invoice 'acceptanceDate' field
And the user inputs 'Валидация поставщик' in the invoice 'supplier' field
And the user inputs 'Валидация кто принял' in the invoice 'accepter' field
And the user inputs 'Валидация получатель' in the invoice 'legalEntity' field
And the user navigates to invoice product addition
Then the user sees error messages
| error message |
| Заполните это поле |

Scenario: Invoice acceptanceDate validation manual negative symbols mix
Given the user is on the invoice list page
When the user clicks the create button on the invoice list page
And the user inputs 'IAVMN2' in the invoice 'sku' field
And the user inputs '!"56gfЛВ' in the invoice 'acceptanceDate' field
And the user inputs 'Валидация поставщик' in the invoice 'supplier' field
And the user inputs 'Валидация кто принял' in the invoice 'accepter' field
And the user inputs 'Валидация получатель' in the invoice 'legalEntity' field
And the user navigates to invoice product addition
Then the user sees error messages
| error message |
| Заполните это поле |

Scenario: Invoice acceptanceDate validation through datepicker good
Given the user is on the invoice list page
When the user clicks the create button on the invoice list page
And the user inputs '89654464645' in the invoice 'sku' field
And the user inputs 'todayDateAndTime' in the invoice 'acceptanceDate' field
And the user inputs 'Валидация поставщик' in the invoice 'supplier' field
And the user inputs 'Валидация кто принял' in the invoice 'accepter' field
And the user inputs 'Валидация получатель' in the invoice 'legalEntity' field
And the user navigates to invoice product addition
Then the user sees no error messages

Scenario: Invoice acceptanceDate validation through datepicker negative1
Given the user is on the invoice list page
When the user clicks the create button on the invoice list page
And the user inputs 'IAVMN1' in the invoice 'sku' field
And the user inputs '27.03.2013 9999:9999' in the invoice 'acceptanceDate' field
And the user inputs 'Валидация поставщик' in the invoice 'supplier' field
And the user inputs 'Валидация кто принял' in the invoice 'accepter' field
And the user inputs 'Валидация получатель' in the invoice 'legalEntity' field
And the user navigates to invoice product addition
Then the user sees error messages
| error message |
| Вы ввели неверную дату |


Scenario: Invoice acceptanceDate validation through datepicker negative2
Given the user is on the invoice list page
When the user clicks the create button on the invoice list page
And the user inputs 'IAVMN1' in the invoice 'sku' field
And the user inputs '27.03.2013 1155:222255' in the invoice 'acceptanceDate' field
And the user inputs 'Валидация поставщик' in the invoice 'supplier' field
And the user inputs 'Валидация кто принял' in the invoice 'accepter' field
And the user inputs 'Валидация получатель' in the invoice 'legalEntity' field
And the user navigates to invoice product addition
Then the user sees error messages
| error message |
| Вы ввели неверную дату 27.03.2013 55:55, формат должен быть следующий дд.мм.гггг чч:мм |


Scenario: Invoice accepter validation is required
Given the user is on the invoice list page
When the user clicks the create button on the invoice list page
And the user inputs 'IAVIR-01' in the invoice 'sku' field
And the user inputs '!todayDateAndTime' in the invoice 'acceptanceDate' field
And the user inputs 'Валидация поставщик' in the invoice 'supplier' field
And the user inputs 'Валидация получатель' in the invoice 'legalEntity' field
And the user navigates to invoice product addition
Then the user sees error messages
| error message |
| Заполните это поле |


Scenario: Invoice accepter validation good
Given the user is on the invoice list page
When the user clicks the create button on the invoice list page
And the user inputs 'IAVG-01' in the invoice 'sku' field
And the user generates charData with '100' number in the 'accepter' invoice field
Then the user checks 'accepter' invoice field contains only '100' symbols
When the user inputs 'Валидация поставщик' in the invoice 'supplier' field
And the user inputs '!todayDateAndTime' in the invoice 'acceptanceDate' field
And the user inputs 'Валидация получатель' in the invoice 'legalEntity' field
And the user navigates to invoice product addition
Then the user sees no error messages

Scenario: Invoice accepter validation negative
Given the user is on the invoice list page
When the user clicks the create button on the invoice list page
And the user inputs 'IAVN-01' in the invoice 'sku' field
And the user generates charData with '101' number in the 'accepter' invoice field
Then the user checks 'accepter' invoice field contains only '101' symbols
When the user inputs 'Валидация поставщик' in the invoice 'supplier' field
And the user inputs '!todayDateAndTime' in the invoice 'acceptanceDate' field
And the user inputs 'Валидация получатель' in the invoice 'legalEntity' field
And the user navigates to invoice product addition
Then the user sees error messages
| error message |
| Не более 100 символов |

Scenario: Invoice legalEntity validation is required
Given the user is on the invoice list page
When the user clicks the create button on the invoice list page
And the user inputs 'ILEVIR-01' in the invoice 'sku' field
And the user inputs '!todayDateAndTime' in the invoice 'acceptanceDate' field
And the user inputs 'Валидация кто принял' in the invoice 'accepter' field
And the user inputs 'Валидация поставщик' in the invoice 'supplier' field
And the user navigates to invoice product addition
Then the user sees error messages
| error message |
| Заполните это поле |


Scenario: Invoice legalEntity validation good
Given the user is on the invoice list page
When the user clicks the create button on the invoice list page
And the user inputs 'ILEVG-01' in the invoice 'sku' field
And the user generates charData with '300' number in the 'legalEntity' invoice field
Then the user checks 'legalEntity' invoice field contains only '300' symbols
When the user inputs 'Валидация поставщик' in the invoice 'supplier' field
And the user inputs '!todayDateAndTime' in the invoice 'acceptanceDate' field
And the user inputs 'Валидация кто принял' in the invoice 'accepter' field
And the user navigates to invoice product addition
Then the user sees no error messages


Scenario: Invoice legalEntity validation negative
Given the user is on the invoice list page
When the user clicks the create button on the invoice list page
And the user inputs 'ILEVN-01' in the invoice 'sku' field
And the user generates charData with '301' number in the 'legalEntity' invoice field
Then the user checks 'legalEntity' invoice field contains only '301' symbols
When the user inputs 'Валидация поставщик' in the invoice 'supplier' field
And the user inputs '!todayDateAndTime' in the invoice 'acceptanceDate' field
And the user inputs 'Валидация кто принял' in the invoice 'accepter' field
And the user navigates to invoice product addition
Then the user sees error messages
| error message |
| Не более 300 символов |

Scenario: Invoice supplierInvoiceSku validation good
Given the user is on the invoice list page
When the user clicks the create button on the invoice list page
And the user inputs 'ISSVG-01' in the invoice 'sku' field
And the user generates charData with '100' number in the 'supplierInvoiceSku' invoice field
Then the user checks 'supplierInvoiceSku' invoice field contains only '100' symbols
When the user inputs 'Валидация поставщик' in the invoice 'supplier' field
And the user inputs '!todayDateAndTime' in the invoice 'acceptanceDate' field
And the user inputs 'Валидация кто принял' in the invoice 'accepter' field
And the user inputs 'Валидация получатель' in the invoice 'legalEntity' field
And the user navigates to invoice product addition
Then the user sees no error messages

Scenario: Invoice supplierInvoiceSku validation negative
Given the user is on the invoice list page
When the user clicks the create button on the invoice list page
And the user inputs 'ISSIVVN-01' in the invoice 'sku' field
And the user generates charData with '101' number in the 'supplierInvoiceSku' invoice field
Then the user checks 'supplierInvoiceSku' invoice field contains only '101' symbols
When the user inputs 'Валидация поставщик' in the invoice 'supplier' field
And the user inputs '!todayDateAndTime' in the invoice 'acceptanceDate' field
And the user inputs 'Валидация кто принял' in the invoice 'accepter' field
And the user inputs 'Валидация получатель' in the invoice 'legalEntity' field
And the user navigates to invoice product addition
Then the user sees error messages
| error message |
| Не более 100 символов |

Scenario: Invoice supplierInvoiceSku, supplierInvoiceDate are not required
Given the user is on the invoice list page
When the user clicks the create button on the invoice list page
And the user inputs 'ISISSID-01' in the invoice 'sku' field
And the user inputs '!todayDateAndTime' in the invoice 'acceptanceDate' field
And the user inputs 'Валидация поставщик' in the invoice 'supplier' field
And the user inputs 'Валидация кто принял' in the invoice 'accepter' field
And the user inputs 'Валидация получатель' in the invoice 'legalEntity' field
And the user navigates to invoice product addition
Then the user sees no error messages

Scenario: Invoice supplierInvoiceDate validation good manual
Given the user is on the invoice list page
When the user clicks the create button on the invoice list page
And the user inputs '!todayDateAndTime' in the invoice 'acceptanceDate' field
And the user inputs '6765934' in the invoice 'sku' field
And the user inputs '!03.12.2012' in the invoice 'supplierInvoiceDate' field
And the user inputs 'Валидация поставщик' in the invoice 'supplier' field
And the user inputs 'Валидация кто принял' in the invoice 'accepter' field
And the user inputs 'Валидация получатель' in the invoice 'legalEntity' field
And the user navigates to invoice product addition
Then the user sees no error messages

Scenario: Invoice supplierInvoiceDate validation manual negative1 numbers
Given the user is on the invoice list page
When the user clicks the create button on the invoice list page
And the user inputs '!todayDateAndTime' in the invoice 'acceptanceDate' field
And the user inputs 'ISSSSAVMN1' in the invoice 'sku' field
And the user inputs '!12345456789' in the invoice 'supplierInvoiceDate' field
And the user inputs 'Валидация поставщик' in the invoice 'supplier' field
And the user inputs 'Валидация кто принял' in the invoice 'accepter' field
And the user inputs 'Валидация получатель' in the invoice 'legalEntity' field
And the user navigates to invoice product addition
Then the user sees error messages
| error message |
| Вы ввели неверную дату |

Scenario: Invoice supplierInvoiceDate validation manual negative2 eng symbols
Given the user is on the invoice list page
When the user clicks the create button on the invoice list page
And the user inputs '!todayDateAndTime' in the invoice 'acceptanceDate' field
And the user inputs 'ISSAVMN2' in the invoice 'sku' field
And the user inputs '!HAasdfsfsfsf' in the invoice 'supplierInvoiceDate' field
And the user inputs 'Валидация поставщик' in the invoice 'supplier' field
And the user inputs 'Валидация кто принял' in the invoice 'accepter' field
And the user inputs 'Валидация получатель' in the invoice 'legalEntity' field
And the user navigates to invoice product addition
Then the user sees no error messages

Scenario: Invoice supplierInvoiceDate validation manual negative3 rus symbols
Given the user is on the invoice list page
When the user clicks the create button on the invoice list page
And the user inputs '!todayDateAndTime' in the invoice 'acceptanceDate' field
And the user inputs 'ISAVMN2' in the invoice 'sku' field
And the user inputs '!Русский набор' in the invoice 'supplierInvoiceDate' field
And the user inputs 'Валидация поставщик' in the invoice 'supplier' field
And the user inputs 'Валидация кто принял' in the invoice 'accepter' field
And the user inputs 'Валидация получатель' in the invoice 'legalEntity' field
And the user navigates to invoice product addition
Then the user sees no error messages

Scenario: Invoice supplierInvoiceDate validation manual negative symbols
Given the user is on the invoice list page
When the user clicks the create button on the invoice list page
And the user inputs '!todayDateAndTime' in the invoice 'acceptanceDate' field
And the user inputs 'ISAVMN2' in the invoice 'sku' field
And the user inputs '!"№;%:?*()_+' in the invoice 'supplierInvoiceDate' field
And the user inputs 'Валидация поставщик' in the invoice 'supplier' field
And the user inputs 'Валидация кто принял' in the invoice 'accepter' field
And the user inputs 'Валидация получатель' in the invoice 'legalEntity' field
And the user navigates to invoice product addition
Then the user sees no error messages

Scenario: Invoice supplierInvoiceDate validation manual negative symbols mix
Given the user is on the invoice list page
When the user clicks the create button on the invoice list page
And the user inputs '!todayDateAndTime' in the invoice 'acceptanceDate' field
And the user inputs 'ISAVMN2' in the invoice 'sku' field
And the user inputs '!"56gfЛВ' in the invoice 'supplierInvoiceDate' field
And the user inputs 'Валидация поставщик' in the invoice 'supplier' field
And the user inputs 'Валидация кто принял' in the invoice 'accepter' field
And the user inputs 'Валидация получатель' in the invoice 'legalEntity' field
And the user navigates to invoice product addition
Then the user sees no error messages

Scenario: Invoice supplierInvoiceDate validation through datepicker good
Given the user is on the invoice list page
When the user clicks the create button on the invoice list page
And the user inputs '8S9654464645' in the invoice 'sku' field
And the user inputs 'todayDate' in the invoice 'supplierInvoiceDate' field
And the user inputs 'Валидация поставщик' in the invoice 'supplier' field
And the user inputs 'Валидация кто принял' in the invoice 'accepter' field
And the user inputs 'Валидация получатель' in the invoice 'legalEntity' field
And the user navigates to invoice product addition
Then the user sees no error messages

Scenario: Invoice supplier date cantbe older then acceptance date 1
Given the user is on the invoice list page
When the user clicks the create button on the invoice list page
And the user inputs '!27.03.2013 10:11' in the invoice 'acceptanceDate' field
And the user inputs 'ISAVMN1' in the invoice 'sku' field
And the user inputs '!28.03.2013' in the invoice 'supplierInvoiceDate' field
And the user inputs 'Валидация поставщик' in the invoice 'supplier' field
And the user inputs 'Валидация кто принял' in the invoice 'accepter' field
And the user inputs 'Валидация получатель' in the invoice 'legalEntity' field
And the user navigates to invoice product addition
Then the user sees error messages
| error message |
| Дата накладной не должна быть старше даты приемки |

Scenario: regression bug 1
Given the user is on the invoice list page
When the user clicks the create button on the invoice list page
And the user inputs '!77.77.7777 77:77' in the invoice 'acceptanceDate' field
And the user inputs 'I56SAVMN1' in the invoice 'sku' field
And the user inputs '!todayDate' in the invoice 'supplierInvoiceDate' field
And the user inputs 'Валидация поставщик' in the invoice 'supplier' field
And the user inputs 'Валидация кто принял' in the invoice 'accepter' field
And the user inputs 'Валидация получатель' in the invoice 'legalEntity' field
And the user navigates to invoice product addition
Then the user sees no error messages
| error message |
| Дата накладной не должна быть старше даты приемки |
And the user sees error messages
| error message |
| Вы ввели неверную дату 77.77.7777 77:77, формат должен быть следующий дд.мм.гггг чч:мм |

