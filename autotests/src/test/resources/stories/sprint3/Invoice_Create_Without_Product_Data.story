Создание накладной без данных о товаре

Narrative:
Как заведущий отделом,
Я хочу создать накладную,
Чтобы зафиксировать в системе факт начала приемки

Scenario: Invoice Create
Given the user is on the invoice create page
When the user inputs '123456' in the invoice 'sku' field
When the user inputs '24.11.2006' in the invoice 'acceptanceDate' field
When the user inputs 'ООО Поставщик' in the invoice 'supplier' field
When the user inputs 'Иван Петров' in the invoice 'accepter' field
When the user inputs '123' in the invoice 'supplierInvoiceSku' field
When the user inputs '25.25.2009' in the invoice 'supplierInvoiceDate' field
And the user clicks the invoice create button
Then the user checks that he is on the 'InvoiceListPage'
