Создание накладной без данных о товаре

Narrative:
Как заведущий отделом,
Я хочу создать накладную,
Чтобы зафиксировать в системе факт начала приемки

Scenario: Invoice Create
Given the user is on the invoice create page
When the user inputs '123456' in the invoice 'sku' field
When the user inputs '!todayDateAndTime' in the invoice 'acceptanceDate' field
When the user inputs 'ООО Поставщик' in the invoice 'supplier' field
When the user inputs 'Иван Петров' in the invoice 'accepter' field
And the user inputs 'ООО234' in the invoice 'legalEntity' field
When the user inputs '123' in the invoice 'supplierInvoiceSku' field
When the user inputs '!todayDate' in the invoice 'supplierInvoiceDate' field
And the user navigates to invoice product addition
Given the user is on the invoice list page
Then the user checks that he is on the 'InvoiceListPage'

Scenario: Invoice Create Cancel
Given the user is on the invoice create page
When the user clicks close button in the invoice create page
Then the user checks that he is on the 'InvoiceListPage'
