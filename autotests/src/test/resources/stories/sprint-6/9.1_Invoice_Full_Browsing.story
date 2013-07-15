9.1 Просмотр полной накладной

Narrative:
Как азведущий отделом,
Я хочу просматривать накладную с товарными позициями,
Чтобы определять верно ли зафиксирвоана приемка

Meta:
@sprint 6
@us 9.1

Scenario: invoice full browsing kg
Given there is the product with 'IFBKG-11' name, 'IFBKG-11' sku, 'IFBKG-11' barcode
And the user is on the invoice list page
And the user logs in as 'departmentManager'
When the user clicks the create button on the invoice list page
And the user inputs 'Invoice-IFBKG-11' in the invoice 'sku' field
And the user inputs '02.04.2013 16:23' in the invoice 'acceptanceDate' field
And the user inputs 'Поставщик' in the invoice 'supplier' field
And the user inputs 'Иван Петрович Петрович' in the invoice 'accepter' field
And the user inputs 'Компания' in the invoice 'legalEntity' field
And the user inputs '123456' in the invoice 'supplierInvoiceSku' field
And the user inputs '01.04.2013' in the invoice 'supplierInvoiceDate' field
And the user navigates to invoice product addition
When the user inputs 'IFBKG-11' in the invoice product 'productName' field
And the user inputs '1' in the invoice product 'productAmount' field
And the user inputs '1' in the invoice product 'invoiceCost' field
And the user clicks the add more product button
When the user clicks finish edit button and ends the invoice edition
Given the user is on the invoice list page
Then the user checks the invoice with 'Invoice-IFBKG-11' sku is present
When the user open the invoice card with 'Invoice-IFBKG-11' sku
Then the user checks invoice 'head' elements  values
| elementName | value |
| sku | Invoice-IFBKG-11 |
| supplier | Поставщик |
| accepter | Иван Петрович Петрович |
| legalEntity | Компания |
| acceptanceDate | 02.04.2013 16:23 |
| supplierInvoiceDate | 01.04.2013 |
| supplierInvoiceSku | 123456 |
Then the user checks the product with 'IFBKG-11' sku has values
| elementName | value |
| productName | IFBKG-11 |
| productSku | IFBKG-11 |
| productBarcode | IFBKG-11 |
| productUnits | кг |
| productAmount | 1 |
| productPrice | 1 |
| productSum | 1 |
And the user checks invoice elements values
| elementName | value |
| totalProducts | 1 |
| totalSum | 1 |
When the user logs out

Scenario: invoice full browsing units
Given there is the product with 'IFBUNITS-11' name, 'IFBUNITS-11' sku, 'IFBUNITS-11' barcode, 'unit' units
And the user is on the invoice list page
And the user logs in as 'departmentManager'
When the user clicks the create button on the invoice list page
And the user inputs 'Invoice-IFBUNITS-11' in the invoice 'sku' field
And the user inputs '02.04.2013 16:23' in the invoice 'acceptanceDate' field
And the user inputs 'Поставщик' in the invoice 'supplier' field
And the user inputs 'Иван Петрович Петрович' in the invoice 'accepter' field
And the user inputs 'Компания' in the invoice 'legalEntity' field
And the user inputs '123456' in the invoice 'supplierInvoiceSku' field
And the user inputs '01.04.2013' in the invoice 'supplierInvoiceDate' field
And the user navigates to invoice product addition
When the user inputs 'IFBUNITS-11' in the invoice product 'productName' field
And the user inputs '1' in the invoice product 'productAmount' field
And the user inputs '1' in the invoice product 'invoiceCost' field
And the user clicks the add more product button
When the user clicks finish edit button and ends the invoice edition
Given the user is on the invoice list page
Then the user checks the invoice with 'Invoice-IFBUNITS-11' sku is present
When the user open the invoice card with 'Invoice-IFBUNITS-11' sku
Then the user checks invoice 'head' elements  values
| elementName | value |
| sku | Invoice-IFBUNITS-11 |
| supplier | Поставщик |
| accepter | Иван Петрович Петрович |
| legalEntity | Компания |
| acceptanceDate | 02.04.2013 16:23 |
| supplierInvoiceDate | 01.04.2013 |
| supplierInvoiceSku | 123456 |
Then the user checks the product with 'IFBUNITS-11' sku has values
| elementName | value |
| productName | IFBUNITS-11 |
| productSku | IFBUNITS-11 |
| productBarcode | IFBUNITS-11 |
| productUnits | шт. |
| productAmount | 1 |
| productPrice | 1 |
| productSum | 1 |
And the user checks invoice elements values
| elementName | value |
| totalProducts | 1 |
| totalSum | 1 |
When the user logs out

Scenario: invoice full browsing liter
Given there is the product with 'IFBLITER-11' name, 'IFBLITER-11' sku, 'IFBLITER-11' barcode, 'liter' units
And the user is on the invoice list page
And the user logs in as 'departmentManager'
When the user clicks the create button on the invoice list page
And the user inputs 'Invoice-IFBLITER-11' in the invoice 'sku' field
And the user inputs '02.04.2013 16:23' in the invoice 'acceptanceDate' field
And the user inputs 'Поставщик' in the invoice 'supplier' field
And the user inputs 'Иван Петрович Петрович' in the invoice 'accepter' field
And the user inputs 'Компания' in the invoice 'legalEntity' field
And the user inputs '123456' in the invoice 'supplierInvoiceSku' field
And the user inputs '01.04.2013' in the invoice 'supplierInvoiceDate' field
And the user navigates to invoice product addition
When the user inputs 'IFBLITER-11' in the invoice product 'productName' field
And the user inputs '1' in the invoice product 'productAmount' field
And the user inputs '1' in the invoice product 'invoiceCost' field
And the user clicks the add more product button
When the user clicks finish edit button and ends the invoice edition
Given the user is on the invoice list page
Then the user checks the invoice with 'Invoice-IFBLITER-11' sku is present
When the user open the invoice card with 'Invoice-IFBLITER-11' sku
Then the user checks invoice 'head' elements  values
| elementName | value |
| sku | Invoice-IFBLITER-11 |
| supplier | Поставщик |
| accepter | Иван Петрович Петрович |
| legalEntity | Компания |
| acceptanceDate | 02.04.2013 16:23 |
| supplierInvoiceDate | 01.04.2013 |
| supplierInvoiceSku | 123456 |
Then the user checks the product with 'IFBLITER-11' sku has values
| elementName | value |
| productName | IFBLITER-11 |
| productSku | IFBLITER-11 |
| productBarcode | IFBLITER-11 |
| productUnits | л |
| productAmount | 1 |
| productPrice | 1 |
| productSum | 1 |
And the user checks invoice elements values
| elementName | value |
| totalProducts | 1 |
| totalSum | 1 |
When the user logs out

Scenario: invoice full browsing 3 products kg liter unit
Given there is the product with 'IFBKG-111' name, 'IFBKG-111' sku, 'IFBKG-111' barcode
Given there is the product with 'IFBUNITS-112' name, 'IFBUNITS-112' sku, 'IFBUNITS-112' barcode, 'unit' units
Given there is the product with 'IFBLITER-113' name, 'IFBLITER-113' sku, 'IFBLITER-113' barcode, 'liter' units
And the user is on the invoice list page
And the user logs in as 'departmentManager'
When the user clicks the create button on the invoice list page
And the user inputs 'Invoice-IFB3PKLU-11' in the invoice 'sku' field
And the user inputs '02.04.2013 16:23' in the invoice 'acceptanceDate' field
And the user inputs 'Поставщик' in the invoice 'supplier' field
And the user inputs 'Иван Петрович Петрович' in the invoice 'accepter' field
And the user inputs 'Компания' in the invoice 'legalEntity' field
And the user inputs '123456' in the invoice 'supplierInvoiceSku' field
And the user inputs '01.04.2013' in the invoice 'supplierInvoiceDate' field
And the user navigates to invoice product addition
When the user inputs 'IFBKG-111' in the invoice product 'productName' field
And the user inputs '1' in the invoice product 'productAmount' field
And the user inputs '1' in the invoice product 'invoiceCost' field
And the user clicks the add more product button
When the user inputs 'IFBUNITS-112' in the invoice product 'productName' field
And the user inputs '1' in the invoice product 'productAmount' field
And the user inputs '1' in the invoice product 'invoiceCost' field
And the user clicks the add more product button
When the user inputs 'IFBLITER-113' in the invoice product 'productName' field
And the user inputs '1' in the invoice product 'productAmount' field
And the user inputs '1' in the invoice product 'invoiceCost' field
And the user clicks the add more product button
When the user clicks finish edit button and ends the invoice edition
Given the user is on the invoice list page
Then the user checks the invoice with 'Invoice-IFBLITER-11' sku is present
When the user open the invoice card with 'Invoice-IFB3PKLU-11' sku
Then the user checks invoice elements values
| elementName | value |
| sku | Invoice-IFB3PKLU-11 |
| supplier | Поставщик |
| accepter | Иван Петрович Петрович |
| legalEntity | Компания |
| acceptanceDate | 02.04.2013 16:23 |
| supplierInvoiceDate | 01.04.2013 |
| supplierInvoiceSku | 123456 |
Then the user checks the product with 'IFBKG-111' sku has values
| elementName | value |
| productName | IFBKG-111 |
| productSku | IFBKG-111 |
| productBarcode | IFBKG-111 |
| productUnits | кг |
| productAmount | 1 |
| productPrice | 1 |
| productSum | 1 |
Then the user checks the product with 'IFBUNITS-112' sku has values
| elementName | value |
| productName | IFBUNITS-112 |
| productSku | IFBUNITS-112 |
| productBarcode | IFBUNITS-112 |
| productUnits | шт. |
| productAmount | 1 |
| productPrice | 1 |
| productSum | 1 |
Then the user checks the product with 'IFBLITER-113' sku has values
| elementName | value |
| productName | IFBLITER-113 |
| productSku | IFBLITER-113 |
| productBarcode | IFBLITER-113 |
| productUnits | л |
| productAmount | 1 |
| productPrice | 1 |
| productSum | 1 |
And the user checks invoice elements values
| elementName | value |
| totalProducts | 3 |
| totalSum | 3 |
When the user logs out


