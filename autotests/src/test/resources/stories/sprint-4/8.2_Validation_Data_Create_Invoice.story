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

And the user inputs '654321' in the invoice 'sku' field
And the user inputs 'todayDate' in the invoice 'acceptanceDate' field
And the user inputs 'Валидация поставщик' in the invoice 'supplier' field
And the user inputs 'Валидация кто принял' in the invoice 'accepter' field
And the user inputs 'Валидация получатель' in the invoice 'legalEntity' field


Scenario: Invoice sku validation is required
Given the user is on the invoice list page
When the user clicks the create button on the invoice list page
And the user inputs 'todayDate' in the invoice 'acceptanceDate' field
And the user inputs 'Валидация поставщик' in the invoice 'supplier' field
And the user inputs 'Валидация кто принял' in the invoice 'accepter' field
And the user inputs 'Валидация получатель' in the invoice 'legalEntity' field
And the user clicks the invoice create button
Then the user sees error messages
| error message |
| Заполните это поле |

Scenario: Invoice sku validation good
Given the user is on the invoice list page
When the user clicks the create button on the invoice list page
And the user generates charData with '100' number in the 'sku' invoice field
Then the user checks 'sku' invoice field contains only '100' symbols
When the user inputs 'todayDate' in the invoice 'acceptanceDate' field
And the user inputs 'Валидация поставщик' in the invoice 'supplier' field
And the user inputs 'Валидация кто принял' in the invoice 'accepter' field
And the user inputs 'Валидация получатель' in the invoice 'legalEntity' field
And the user clicks the invoice create button
Then the user sees no error messages

Scenario: Invoice sku validation negative
Given the user is on the invoice list page
When the user clicks the create button on the invoice list page
And the user generates charData with '101' number in the 'sku' invoice field
Then the user checks 'sku' invoice field contains only '101' symbols
When the user inputs 'todayDate' in the invoice 'acceptanceDate' field
And the user inputs 'Валидация поставщик' in the invoice 'supplier' field
And the user inputs 'Валидация кто принял' in the invoice 'accepter' field
And the user inputs 'Валидация получатель' in the invoice 'legalEntity' field
And the user clicks the invoice create button
Then the user sees error messages
| error message |
| Не более 100 символов |

Scenario: Invoice Supplier validation is required
Given the user is on the invoice list page
When the user clicks the create button on the invoice list page
And the user inputs 'ISVIR-01' in the invoice 'sku' field
And the user inputs 'todayDate' in the invoice 'acceptanceDate' field
And the user inputs 'Валидация кто принял' in the invoice 'accepter' field
And the user inputs 'Валидация получатель' in the invoice 'legalEntity' field
And the user clicks the invoice create button
Then the user sees error messages
| error message |
| Заполните это поле |

Scenario: Invoice Supplier validation good
Given the user is on the invoice list page
When the user clicks the create button on the invoice list page
And the user inputs 'ISVG-01' in the invoice 'sku' field
And the user generates charData with '300' number in the 'supplier' invoice field
Then the user checks 'supplier' invoice field contains only '300' symbols
When the user inputs 'todayDate' in the invoice 'acceptanceDate' field
And the user inputs 'Валидация кто принял' in the invoice 'accepter' field
And the user inputs 'Валидация получатель' in the invoice 'legalEntity' field
And the user clicks the invoice create button
Then the user sees no error messages

Scenario: Invoice Supplier validation negative
Given the user is on the invoice list page
When the user clicks the create button on the invoice list page
And the user inputs 'ISVN-01' in the invoice 'sku' field
And the user generates charData with '301' number in the 'supplier' invoice field
Then the user checks 'supplier' invoice field contains only '301' symbols
When the user inputs 'todayDate' in the invoice 'acceptanceDate' field
And the user inputs 'Валидация кто принял' in the invoice 'accepter' field
And the user inputs 'Валидация получатель' in the invoice 'legalEntity' field
And the user clicks the invoice create button
Then the user sees error messages
| error message |
| Не более 300 символов |

Scenario: Invoice acceptanceDate validation is required
Scenario: Invoice acceptanceDate validation good
Scenario: Invoice acceptanceDate validation negative1
Scenario: Invoice acceptanceDate validation negative2
Scenario: Invoice acceptanceDate autofilling

Scenario: Invoice accepter validation is required
Given the user is on the invoice list page
When the user clicks the create button on the invoice list page
And the user inputs 'IAVIR-01' in the invoice 'sku' field
And the user inputs 'todayDate' in the invoice 'acceptanceDate' field
And the user inputs 'Валидация поставщик' in the invoice 'supplier' field
And the user inputs 'Валидация получатель' in the invoice 'legalEntity' field
And the user clicks the invoice create button
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
And the user inputs 'todayDate' in the invoice 'acceptanceDate' field
And the user inputs 'Валидация получатель' in the invoice 'legalEntity' field
And the user clicks the invoice create button
Then the user sees no error messages

Scenario: Invoice accepter validation negative
Given the user is on the invoice list page
When the user clicks the create button on the invoice list page
And the user inputs 'IAVN-01' in the invoice 'sku' field
And the user generates charData with '101' number in the 'accepter' invoice field
Then the user checks 'accepter' invoice field contains only '101' symbols
When the user inputs 'Валидация поставщик' in the invoice 'supplier' field
And the user inputs 'todayDate' in the invoice 'acceptanceDate' field
And the user inputs 'Валидация получатель' in the invoice 'legalEntity' field
And the user clicks the invoice create button
Then the user sees error messages
| error message |
| Не более 300 символов |

Scenario: Invoice legalEntity validation is required
Given the user is on the invoice list page
When the user clicks the create button on the invoice list page
And the user inputs 'ILEVIR-01' in the invoice 'sku' field
And the user inputs 'todayDate' in the invoice 'acceptanceDate' field
And the user inputs 'Валидация кто принял' in the invoice 'accepter' field
And the user inputs 'Валидация поставщик' in the invoice 'supplier' field
And the user clicks the invoice create button
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
And the user inputs 'todayDate' in the invoice 'acceptanceDate' field
And the user inputs 'Валидация кто принял' in the invoice 'accepter' field
And the user clicks the invoice create button
Then the user sees no error messages


Scenario: Invoice legalEntity validation negative
Given the user is on the invoice list page
When the user clicks the create button on the invoice list page
And the user inputs 'ILEVN-01' in the invoice 'sku' field
And the user generates charData with '301' number in the 'legalEntity' invoice field
Then the user checks 'legalEntity' invoice field contains only '301' symbols
When the user inputs 'Валидация поставщик' in the invoice 'supplier' field
And the user inputs 'todayDate' in the invoice 'acceptanceDate' field
And the user inputs 'Валидация кто принял' in the invoice 'accepter' field
And the user clicks the invoice create button
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
And the user inputs 'todayDate' in the invoice 'acceptanceDate' field
And the user inputs 'Валидация кто принял' in the invoice 'accepter' field
And the user inputs 'Валидация получатель' in the invoice 'legalEntity' field
And the user clicks the invoice create button
Then the user sees no error messages

Scenario: Invoice supplierInvoiceSku validation negative
Given the user is on the invoice list page
When the user clicks the create button on the invoice list page
And the user inputs 'ISSIVVN-01' in the invoice 'sku' field
And the user generates charData with '101' number in the 'supplierInvoiceSku' invoice field
Then the user checks 'supplierInvoiceSku' invoice field contains only '101' symbols
When the user inputs 'Валидация поставщик' in the invoice 'supplier' field
And the user inputs 'todayDate' in the invoice 'acceptanceDate' field
And the user inputs 'Валидация кто принял' in the invoice 'accepter' field
And the user inputs 'Валидация получатель' in the invoice 'legalEntity' field
And the user clicks the invoice create button
Then the user sees error messages
| error message |
| Не более 100 символов |

Scenario: Invoice supplierInvoiceSku, supplierInvoiceDate are not required
Given the user is on the invoice list page
When the user clicks the create button on the invoice list page
And the user inputs 'ISISSID-01' in the invoice 'sku' field
And the user inputs 'todayDate' in the invoice 'acceptanceDate' field
And the user inputs 'Валидация поставщик' in the invoice 'supplier' field
And the user inputs 'Валидация кто принял' in the invoice 'accepter' field
And the user inputs 'Валидация получатель' in the invoice 'legalEntity' field
And the user clicks the invoice create button
Then the user sees no error messages

Scenario: Invoice supplierInvoiceDate validation good
Scenario: Invoice supplierInvoiceDate validation negative1
Scenario: Invoice supplierInvoiceDate validation negative2