Просмотр накладной

Narrative:
Как заведующий отделом,
Я хочу просматривать накладную,
Чтобы определять верно ли завфиксирована приемка

Scenario: Invoice browsing verification
Given the user is on the invoice list page
When the user clicks the create button on the invoice list page
And the user inputs '89698' in the invoice 'sku' field
And the user inputs '02.04.2013 16:23' in the invoice 'acceptanceDate' field
And the user inputs 'ОАЭ Поставщик в квадрате' in the invoice 'supplier' field
And the user inputs 'Иван Петрович Петрович' in the invoice 'accepter' field
And the user inputs 'ООО Компания' in the invoice 'legalEntity' field
And the user inputs '8659' in the invoice 'supplierInvoiceSku' field
And the user inputs '01.04.2013' in the invoice 'supplierInvoiceDate' field
And the user clicks the invoice create button
Then the user checks the invoice with '89698' sku is present
When the user open the invoice card with '89698' sku
Then the user checks invoice elements values
| elementName | expectedValue |
| sku | 89698 |
| supplier | ОАЭ Поставщик в квадрате |
| accepter | Иван Петрович Петрович |
| legalEntity | ООО Компания |
| acceptanceDate | 02.04.2013 16:23 |
| supplierInvoiceDate | 01.04.2013 |
| supplierInvoiceSku | 8659 |