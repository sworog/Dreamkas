Просмотр накладной

Narrative:
Как заведующий отделом,
Я хочу просматривать накладную,
Чтобы определять верно ли завфиксирована приемка

Scenario: Invoice browsing verification
Given the user is on the invoice list page
When the user clicks the create button on the invoice list page
And the user inputs '89698' in the invoice 'sku' field
And the user inputs '24.09.2012' in the invoice 'acceptanceDate' field
And the user inputs 'ОАЭ Поставщик в квадрате' in the invoice 'supplier' field
And the user inputs 'Иван Петрович Петрович' in the invoice 'accepter' field
And the user inputs '8659' in the invoice 'supplierInvoiceSku' field
And the user inputs '25.10.2012' in the invoice 'supplierInvoiceDate' field
And the user clicks the invoice create button
Then the user checks the invoice with '654321' sku is present
When the user open the invoice card with '$skuValue' sku
Then the user checks invoice elements values $checkValuesTable
| elementName | expectedValue |
| sku | 89698 |
| supplier | ОАЭ Поставщик в квадрате |
| accepter | Иван Петрович Петрович |
| acceptanceDate | 24.09.2012 |
| supplierInvoiceDate | 25.10.2012 |
| supplierInvoiceSku | 8659 |