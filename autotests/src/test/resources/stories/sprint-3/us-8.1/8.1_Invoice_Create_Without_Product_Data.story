Создание накладной без данных о товаре

Narrative:
Как заведущий отделом,
Я хочу создать накладную,
Чтобы зафиксировать в системе факт начала приемки

Meta:
@sprint 3
@us 8.1

Scenario: Invoice Create

Given the user is on the invoice create page
And the user logs in as 'departmentManager'
When the user inputs '123456' in the invoice 'sku' field
And the user inputs '!todayDateAndTime' in the invoice 'acceptanceDate' field
And the user inputs 'ООО Поставщик' in the invoice 'supplier' field
And the user inputs 'Иван Петров' in the invoice 'accepter' field
And the user inputs 'ООО234' in the invoice 'legalEntity' field
And the user inputs '123' in the invoice 'supplierInvoiceSku' field
And the user inputs '!todayDate' in the invoice 'supplierInvoiceDate' field
And the user navigates to invoice product addition
And the user clicks finish edit button and ends the invoice edition
