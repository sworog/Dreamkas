Просмотр списка накладных

Narrative:
Как заведующий отделом,
Я хочу просматривать список накладных,
Чтобы контролировать процесс приемки

Scenario: Invoice list item check
Given the user is on the invoice list page
When the user clicks the create button on the invoice list page
And the user inputs '654321' in the invoice 'sku' field
And the user inputs '24.11.2007' in the invoice 'acceptanceDate' field
And the user inputs 'ОАЭ Поставщик' in the invoice 'supplier' field
And the user inputs 'Иван Петрович Васильев' in the invoice 'accepter' field
And the user inputs '799' in the invoice 'supplierInvoiceSku' field
And the user inputs '25.25.2012' in the invoice 'supplierInvoiceDate' field
And the user clicks the invoice create button
Then the user checks the invoice with '654321' sku is present
And the user checks the invoice with '654321' sku has 'acceptanceDate' equal to '24.11.2007'
And the user checks the invoice with '654321' sku has 'supplier' equal to 'ОАЭ Поставщик'
And the user checks the invoice with '654321' sku has 'sumTotal' equal to ''
And the user checks the invoice with '654321' sku has 'accepter' equal to 'Иван Петрович Васильев'

Scenario: Invoice list item create cancel
Given the user is on the invoice list page
When the user clicks the create button on the invoice list page
And the user clicks close button in the invoice create page
Then the user checks that he is on the 'InvoiceListPage'
