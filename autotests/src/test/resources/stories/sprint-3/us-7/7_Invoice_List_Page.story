Просмотр списка накладных

Narrative:
Как заведующий отделом,
Я хочу просматривать список накладных,
Чтобы контролировать процесс приемки

Meta:
@sprint 3
@us 7

Scenario: Invoice list item check

Given the user is on the invoice list page
And the user logs in as 'departmentManager'
When the user clicks the create button on the invoice list page
And the user inputs '654321' in the invoice 'sku' field
And the user inputs '02.04.2013 16:23' in the invoice 'acceptanceDate' field
And the user inputs 'ОАЭ Поставщик' in the invoice 'supplier' field
And the user inputs 'Иван Петрович Васильев' in the invoice 'accepter' field
And the user inputs 'ООО23' in the invoice 'legalEntity' field
And the user inputs '799' in the invoice 'supplierInvoiceSku' field
And the user inputs '01.04.2013' in the invoice 'supplierInvoiceDate' field
And the user navigates to invoice product addition
And the user clicks finish edit button and ends the invoice edition
Given the user is on the invoice list page
Then the user checks the invoice with '654321' sku is present
And the user checks the invoice with '654321' sku has 'acceptanceDate' equal to '02.04.2013 16:23'
And the user checks the invoice with '654321' sku has 'supplier' equal to 'ОАЭ Поставщик'
And the user checks the invoice with '654321' sku has 'sumTotal' equal to ''
And the user checks the invoice with '654321' sku has 'accepter' equal to 'Иван Петрович Васильев'

