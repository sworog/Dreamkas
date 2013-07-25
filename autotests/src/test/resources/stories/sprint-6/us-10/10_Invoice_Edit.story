10 Редактирование накладной

Narrative:
Как заведующий отделом,
Я хочу изменять данные накладной,
Чтобы исправлять в ней ошибки

Meta:
@sprint 6
@us 10

Scenario: Invoice head edition

Given the user is on the invoice list page
And the user logs in as 'departmentManager'
When the user clicks the create button on the invoice list page
And the user inputs 'Invoice-IE-IH' in the invoice 'sku' field
And the user inputs '02.04.2013 16:23' in the invoice 'acceptanceDate' field
And the user inputs 'Поставщик' in the invoice 'supplier' field
And the user inputs 'Иван Петрович Петрович' in the invoice 'accepter' field
And the user inputs 'Компания' in the invoice 'legalEntity' field
And the user inputs '123456' in the invoice 'supplierInvoiceSku' field
And the user inputs '01.04.2013' in the invoice 'supplierInvoiceDate' field
And the user navigates to invoice product addition
When the user clicks finish edit button and ends the invoice edition
Given the user is on the invoice list page
Then the user checks the invoice with 'Invoice-IE-IH' sku is present
When the user open the invoice card with 'Invoice-IE-IH' sku
When the user clicks edit button and starts invoice edition
When the user edits 'sku' element with new value 'Invoice-IE-IH-Edited' and verify the 'head' changes
When the user edits 'supplierInvoiceDate' element with new value '02.03.2012' and verify the 'head' changes
When the user edits 'supplier' element with new value 'Другой поставщик' and verify the 'head' changes
When the user edits 'accepter' element with new value 'Сидоров Иван Сидорович' and verify the 'head' changes
When the user edits 'legalEntity' element with new value 'Другая компания' and verify the 'head' changes
When the user edits 'supplierInvoiceSku' element with new value '654321' and verify the 'head' changes
When the user edits 'acceptanceDate' element with new value '03.03.2012 14:56' and verify the 'head' changes
When the user clicks finish edit button and ends the invoice edition
Then the user checks invoice 'head' elements  values
| elementName | value |
| sku | Invoice-IE-IH-Edited |
| supplier | Другой поставщик |
| accepter | Сидоров Иван Сидорович |
| legalEntity | Другая компания |
| acceptanceDate | 03.03.2012 14:56 |
| supplierInvoiceDate | 02.03.2012 |
| supplierInvoiceSku | 654321 |
When the user logs out

Scenario: Invoice head edition stop edit link click

Given there is the invoice with 'Invoice-IE-IPE-SELC' sku
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'Invoice-IE-IPE-SELC' sku
And the user clicks edit button and starts invoice edition
When the user edits 'sku' element with new value 'Invoice-IE-IPE-SELC-Edited' and verify the 'head' changes
And the user clicks finish edit link and ends the invoice edition
Then the user checks invoice 'head' elements  values
| elementName | value |
| sku | Invoice-IE-IPE-SELC-Edited |
When the user logs out

Scenario: Invoice product edition name autocomplete

Given there is the product with 'IE-IPE' name, 'IE-IPE' sku, 'IE-IPE' barcode
And there is the product with 'IE-IPE-1' name, 'IE-IPE-1' sku, 'IE-IPE-1' barcode, 'liter' units
And there is the invoice with 'Invoice-IE-IPE' sku
And the user logs in as 'departmentManager'
When the user inputs 'IE-IPE' in the invoice product 'productName' field
And the user inputs '1' in the invoice product 'productAmount' field
And the user inputs '2' in the invoice product 'invoiceCost' field
And the user clicks the add more product button
When the user clicks finish edit button and ends the invoice edition
Given the user is on the invoice list page
Then the user checks the invoice with 'Invoice-IE-IPE' sku is present
When the user open the invoice card with 'Invoice-IE-IPE' sku
When the user clicks edit button and starts invoice edition
When the user clicks on 'productName' element of invoice product with 'IE-IPE' sku
And the user inputs 'IE-IPE-1' in the invoice 'inline productName' field
And the user clicks OK and accepts changes
Then the user checks the product with 'IE-IPE-1' sku has values
| elementName | value |
| productName | IE-IPE-1 |
| productSku | IE-IPE-1 |
| productBarcode | IE-IPE-1 |
When the user clicks on 'productAmount' element of invoice product with 'IE-IPE-1' sku
And the user inputs '2' in the invoice 'inline quantity' field
And the user clicks OK and accepts changes
Then the user checks the product with 'IE-IPE-1' sku has values
| elementName | value |
| productAmount | 2 |
When the user clicks on 'productPrice' element of invoice product with 'IE-IPE-1' sku
And the user inputs '3' in the invoice 'inline price' field
And the user clicks OK and accepts changes
Then the user checks the product with 'IE-IPE-1' sku has values
| elementName | value |
| productAmount | 2 |
When the user clicks finish edit button and ends the invoice edition
Then the user checks the product with 'IE-IPE-1' sku has values
| elementName | value |
| productName | IE-IPE-1 |
| productSku | IE-IPE-1 |
| productBarcode | IE-IPE-1 |
| productUnits | л |
| productAmount | 2 |
| productPrice | 3 |
| productSum | 6 |
And the user checks invoice elements values
| elementName | value |
| totalProducts | 1 |
| totalSum | 6 |
When the user logs out

Scenario: Invoice product edition name autocomplete stop edit link click

Given there is the product with 'IE-IPE' name, 'IE-IPE' sku, 'IE-IPE' barcode
And there is the product with 'IE-IPE-1' name, 'IE-IPE-1' sku, 'IE-IPE-1' barcode, 'liter' units
And there is the invoice with 'Invoice-IE-IPE-ASELC' sku
And the user logs in as 'departmentManager'
When the user inputs 'IE-IPE' in the invoice product 'productName' field
And the user inputs '1' in the invoice product 'productAmount' field
And the user inputs '2' in the invoice product 'invoiceCost' field
And the user clicks the add more product button
When the user clicks finish edit button and ends the invoice edition
Given the user is on the invoice list page
Then the user checks the invoice with 'Invoice-IE-IPE-ASELC' sku is present
When the user open the invoice card with 'Invoice-IE-IPE-ASELC' sku
When the user clicks edit button and starts invoice edition
When the user clicks on 'productName' element of invoice product with 'IE-IPE' sku
And the user inputs 'IE-IPE-1' in the invoice 'inline productName' field
And the user clicks OK and accepts changes
Then the user checks the product with 'IE-IPE-1' sku has values
| elementName | value |
| productName | IE-IPE-1 |
| productSku | IE-IPE-1 |
| productBarcode | IE-IPE-1 |
When the user clicks finish edit link and ends the invoice edition
Then the user checks the product with 'IE-IPE-1' sku has values
| elementName | value |
| productName | IE-IPE-1 |
| productSku | IE-IPE-1 |
| productBarcode | IE-IPE-1 |
When the user logs out

Scenario: Invoice product edition sku autocomplete

Given there is the product with 'IE-IPE' name, 'IE-IPE' sku, 'IE-IPE' barcode
And there is the product with 'IE-IPE-1' name, 'IE-IPE-1' sku, 'IE-IPE-1' barcode, 'liter' units
And there is the invoice with 'Invoice-IE-IPE-999' sku
And the user logs in as 'departmentManager'
When the user inputs 'IE-IPE' in the invoice product 'productName' field
And the user inputs '1' in the invoice product 'productAmount' field
And the user inputs '2' in the invoice product 'invoiceCost' field
And the user clicks the add more product button
When the user clicks finish edit button and ends the invoice edition
Given the user is on the invoice list page
Then the user checks the invoice with 'Invoice-IE-IPE' sku is present
When the user open the invoice card with 'Invoice-IE-IPE-999' sku
When the user clicks edit button and starts invoice edition
When the user clicks on 'productSku' element of invoice product with 'IE-IPE' sku
And the user inputs 'IE-IPE-1' in the invoice 'inline productSku' field
And the user clicks OK and accepts changes
Then the user checks the product with 'IE-IPE-1' sku has values
| elementName | value |
| productName | IE-IPE-1 |
| productSku | IE-IPE-1 |
| productBarcode | IE-IPE-1 |
When the user clicks on 'productAmount' element of invoice product with 'IE-IPE-1' sku
And the user inputs '2' in the invoice 'inline quantity' field
And the user clicks OK and accepts changes
Then the user checks the product with 'IE-IPE-1' sku has values
| elementName | value |
| productAmount | 2 |
When the user clicks on 'productPrice' element of invoice product with 'IE-IPE-1' sku
And the user inputs '3' in the invoice 'inline price' field
And the user clicks OK and accepts changes
Then the user checks the product with 'IE-IPE-1' sku has values
| elementName | value |
| productAmount | 2 |
When the user clicks finish edit button and ends the invoice edition
Then the user checks the product with 'IE-IPE-1' sku has values
| elementName | value |
| productName | IE-IPE-1 |
| productSku | IE-IPE-1 |
| productBarcode | IE-IPE-1 |
| productUnits | л |
| productAmount | 2 |
| productPrice | 3 |
| productSum | 6 |
And the user checks invoice elements values
| elementName | value |
| totalProducts | 1 |
| totalSum | 6 |
When the user logs out

Scenario: Invoice product edition sku autocomplete stop edit link click

Given there is the product with 'IE-IPE' name, 'IE-IPE' sku, 'IE-IPE' barcode
And there is the product with 'IE-IPE-1' name, 'IE-IPE-1' sku, 'IE-IPE-1' barcode, 'liter' units
And there is the invoice with 'Invoice-IE-IPE-ASSELC' sku
And the user logs in as 'departmentManager'
When the user inputs 'IE-IPE' in the invoice product 'productName' field
And the user inputs '1' in the invoice product 'productAmount' field
And the user inputs '2' in the invoice product 'invoiceCost' field
And the user clicks the add more product button
When the user clicks finish edit button and ends the invoice edition
Given the user is on the invoice list page
Then the user checks the invoice with 'Invoice-IE-IPE-ASSELC' sku is present
When the user open the invoice card with 'Invoice-IE-IPE-ASSELC' sku
When the user clicks edit button and starts invoice edition
When the user clicks on 'productSku' element of invoice product with 'IE-IPE' sku
And the user inputs 'IE-IPE-1' in the invoice 'inline productSku' field
And the user clicks OK and accepts changes
Then the user checks the product with 'IE-IPE-1' sku has values
| elementName | value |
| productName | IE-IPE-1 |
| productSku | IE-IPE-1 |
| productBarcode | IE-IPE-1 |
When the user clicks finish edit link and ends the invoice edition
Then the user checks the product with 'IE-IPE-1' sku has values
| elementName | value |
| productName | IE-IPE-1 |
| productSku | IE-IPE-1 |
| productBarcode | IE-IPE-1 |
When the user logs out

Scenario: Invoice product edition barcode autocomplete

Given there is the product with 'IE-IPE' name, 'IE-IPE' sku, 'IE-IPE' barcode
And there is the product with 'IE-IPE-1' name, 'IE-IPE-1' sku, 'IE-IPE-1' barcode, 'liter' units
And there is the invoice with 'Invoice-IE-IPE-1012' sku
And the user logs in as 'departmentManager'
When the user inputs 'IE-IPE' in the invoice product 'productName' field
And the user inputs '1' in the invoice product 'productAmount' field
And the user inputs '2' in the invoice product 'invoiceCost' field
And the user clicks the add more product button
When the user clicks finish edit button and ends the invoice edition
Given the user is on the invoice list page
Then the user checks the invoice with 'Invoice-IE-IPE' sku is present
When the user open the invoice card with 'Invoice-IE-IPE-1012' sku
When the user clicks edit button and starts invoice edition
When the user clicks on 'productBarcode' element of invoice product with 'IE-IPE' sku
And the user inputs 'IE-IPE-1' in the invoice 'inline productBarCode' field
And the user clicks OK and accepts changes
Then the user checks the product with 'IE-IPE-1' sku has values
| elementName | value |
| productName | IE-IPE-1 |
| productSku | IE-IPE-1 |
| productBarcode | IE-IPE-1 |
When the user clicks on 'productAmount' element of invoice product with 'IE-IPE-1' sku
And the user inputs '2' in the invoice 'inline quantity' field
And the user clicks OK and accepts changes
Then the user checks the product with 'IE-IPE-1' sku has values
| elementName | value |
| productAmount | 2 |
When the user clicks on 'productPrice' element of invoice product with 'IE-IPE-1' sku
And the user inputs '3' in the invoice 'inline price' field
And the user clicks OK and accepts changes
Then the user checks the product with 'IE-IPE-1' sku has values
| elementName | value |
| productAmount | 2 |
When the user clicks finish edit button and ends the invoice edition
Then the user checks the product with 'IE-IPE-1' sku has values
| elementName | value |
| productName | IE-IPE-1 |
| productSku | IE-IPE-1 |
| productBarcode | IE-IPE-1 |
| productUnits | л |
| productAmount | 2 |
| productPrice | 3 |
| productSum | 6 |
And the user checks invoice elements values
| elementName | value |
| totalProducts | 1 |
| totalSum | 6 |
When the user logs out

Scenario: Invoice product edition barcode autocomplete stop edit link click

Given there is the product with 'IE-IPE' name, 'IE-IPE' sku, 'IE-IPE' barcode
And there is the product with 'IE-IPE-1' name, 'IE-IPE-1' sku, 'IE-IPE-1' barcode, 'liter' units
And there is the invoice with 'Invoice-IE-IPE-ASBELC' sku
And the user logs in as 'departmentManager'
When the user inputs 'IE-IPE' in the invoice product 'productName' field
And the user inputs '1' in the invoice product 'productAmount' field
And the user inputs '2' in the invoice product 'invoiceCost' field
And the user clicks the add more product button
When the user clicks finish edit button and ends the invoice edition
Given the user is on the invoice list page
Then the user checks the invoice with 'Invoice-IE-IPE-ASBELC' sku is present
When the user open the invoice card with 'Invoice-IE-IPE-ASBELC' sku
When the user clicks edit button and starts invoice edition
When the user clicks on 'productBarcode' element of invoice product with 'IE-IPE' sku
And the user inputs 'IE-IPE-1' in the invoice 'inline productBarCode' field
And the user clicks OK and accepts changes
Then the user checks the product with 'IE-IPE-1' sku has values
| elementName | value |
| productName | IE-IPE-1 |
| productSku | IE-IPE-1 |
| productBarcode | IE-IPE-1 |
When the user clicks finish edit link and ends the invoice edition
Then the user checks the product with 'IE-IPE-1' sku has values
| elementName | value |
| productName | IE-IPE-1 |
| productSku | IE-IPE-1 |
| productBarcode | IE-IPE-1 |
When the user logs out

Scenario: Invoice product adding

Given there is the product with 'IE-IPA-1' name, 'IE-IPA-1' sku, 'IE-IPA-1' barcode, 'liter' units
And there is the invoice with 'Invoice-IE-IPA' sku
And the user is on the invoice list page
And the user logs in as 'departmentManager'
When the user open the invoice card with 'Invoice-IE-IPA' sku
When the user clicks edit button and starts invoice edition
And the user inputs 'IE-IPA-1' in the invoice product 'productName' field
And the user inputs '1' in the invoice product 'productAmount' field
And the user inputs '3' in the invoice product 'invoiceCost' field
And the user clicks the add invoice product button and adds the invoice product
Then the user checks the product with 'IE-IPA-1' sku has values
| elementName | value |
| productName | IE-IPA-1 |
| productSku | IE-IPA-1 |
| productBarcode | IE-IPA-1 |
| productUnits | л |
| productAmount | 1 |
| productPrice | 3 |
| productSum | 3 |
When the user clicks finish edit button and ends the invoice edition
Then the user checks the product with 'IE-IPA-1' sku has values
| elementName | value |
| productName | IE-IPA-1 |
| productSku | IE-IPA-1 |
| productBarcode | IE-IPA-1 |
| productUnits | л |
| productAmount | 1 |
| productPrice | 3 |
| productSum | 3 |
When the user logs out

Scenario: Invoice product adding stop edit link click

Given there is the product with 'IE-IPA-1' name, 'IE-IPA-1' sku, 'IE-IPA-1' barcode, 'liter' units
And there is the invoice with 'Invoice-IE-IPA-EBC' sku
And the user is on the invoice list page
And the user logs in as 'departmentManager'
When the user open the invoice card with 'Invoice-IE-IPA-EBC' sku
When the user clicks edit button and starts invoice edition
And the user inputs 'IE-IPA-1' in the invoice product 'productName' field
And the user inputs '1' in the invoice product 'productAmount' field
And the user inputs '3' in the invoice product 'invoiceCost' field
And the user clicks the add invoice product button and adds the invoice product
Then the user checks the product with 'IE-IPA-1' sku has values
| elementName | value |
| productName | IE-IPA-1 |
| productSku | IE-IPA-1 |
| productBarcode | IE-IPA-1 |
| productUnits | л |
| productAmount | 1 |
| productPrice | 3 |
| productSum | 3 |
When the user clicks finish edit link and ends the invoice edition
Then the user checks the product with 'IE-IPA-1' sku has values
| elementName | value |
| productName | IE-IPA-1 |
| productSku | IE-IPA-1 |
| productBarcode | IE-IPA-1 |
| productUnits | л |
| productAmount | 1 |
| productPrice | 3 |
| productSum | 3 |
When the user logs out

Scenario: Invoice product adding and edition

Given there is the product with 'IE-IPA' name, 'IE-IPA' sku, 'IE-IPA' barcode
And there is the product with 'IE-IPA-1' name, 'IE-IPA-1' sku, 'IE-IPA-1' barcode, 'liter' units
And there is the invoice with 'Invoice-IE-IPA1' sku
And the user is on the invoice list page
And the user logs in as 'departmentManager'
When the user open the invoice card with 'Invoice-IE-IPA1' sku
When the user clicks edit button and starts invoice edition
And the user inputs 'IE-IPA-1' in the invoice product 'productName' field
And the user inputs '1' in the invoice product 'productAmount' field
And the user inputs '3' in the invoice product 'invoiceCost' field
And the user clicks the add invoice product button and adds the invoice product
Then the user checks the product with 'IE-IPA-1' sku has values
| elementName | value |
| productName | IE-IPA-1 |
| productSku | IE-IPA-1 |
| productBarcode | IE-IPA-1 |
| productUnits | л |
| productAmount | 1 |
| productPrice | 3 |
| productSum | 3 |
When the user clicks on 'productBarcode' element of invoice product with 'IE-IPA-1' sku
And the user inputs 'IE-IPA' in the invoice 'inline productBarCode' field
And the user clicks OK and accepts changes
Then the user checks the product with 'IE-IPA' sku has values
| elementName | value |
| productName | IE-IPA |
| productSku | IE-IPA |
| productBarcode | IE-IPA |
When the user clicks on 'productAmount' element of invoice product with 'IE-IPA' sku
And the user inputs '2' in the invoice 'inline quantity' field
And the user clicks OK and accepts changes
Then the user checks the product with 'IE-IPA' sku has values
| elementName | value |
| productAmount | 2 |
When the user clicks on 'productPrice' element of invoice product with 'IE-IPA' sku
And the user inputs '3' in the invoice 'inline price' field
And the user clicks OK and accepts changes
Then the user checks the product with 'IE-IPA' sku has values
| elementName | value |
| productAmount | 2 |
When the user clicks finish edit button and ends the invoice edition
Then the user checks the product with 'IE-IPA' sku has values
| elementName | value |
| productName | IE-IPA |
| productSku | IE-IPA |
| productBarcode | IE-IPA |
| productUnits | кг |
| productAmount | 2 |
| productPrice | 3 |
| productSum | 6 |
And the user checks invoice elements values
| elementName | value |
| totalProducts | 1 |
| totalSum | 6 |
When the user logs out

Scenario: issue 9 regresssion

Given there is the invoice 'InvoiceProduct-IPE-Common' with product 'IE-IPE' name, 'IE-IPE' sku, 'IE-IPE' barcode, 'liter' units
And the user is on the invoice list page
And the user logs in as 'departmentManager'
When the user open the invoice card with 'InvoiceProduct-IPE-Common' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'productNameView' element of invoice product with 'IE-IPE' sku to edit
And the user inputs '!неттакоготовара' in the invoice 'inline productName' field
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Такого товара не существует |
When the user clicks Cancel and discard changes
And the user clicks on 'productAmountView' element of invoice product with 'IE-IPE' sku to edit
And the user inputs 'неттакоготовара' in the invoice 'inline quantity' field
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Значение должно быть целым числом |
When the user clicks Cancel and discard changes
And the user clicks finish edit link and ends the invoice edition
When the user logs out

Scenario: issue 8 regresssion

Given there is the product with 'IE-IPE-1' name, 'IE-IPE-1' sku, 'IE-IPE-1' barcode, 'liter' units
And there is the invoice 'InvoiceProduct-IPE-Common-regress' with product 'IE-IPE' name, 'IE-IPE' sku, 'IE-IPE' barcode, 'liter' units
And the user is on the invoice list page
And the user logs in as 'departmentManager'
When the user open the invoice card with 'InvoiceProduct-IPE-Common-regress' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'productNameView' element of invoice product with 'IE-IPE' sku to edit
And the user inputs '!1234567' in the invoice 'inline productName' field
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Такого товара не существует |
When the user inputs 'IE-IPE-1' in the invoice product 'productBarCode' field
And the user inputs '1' in the invoice product 'productAmount' field
And the user inputs '3' in the invoice product 'invoiceCost' field
And the user clicks Cancel and discard changes
And the user clicks the add invoice product button and adds the invoice product
Then the user checks invoice elements values
| elementName | value |
| productName |  |
| productSku |  |
| productBarCode |  |
Then the user checks the product with 'IE-IPE-1' sku has values
| elementName | value |
| productName | IE-IPE-1 |
| productSku | IE-IPE-1 |
| productBarcode | IE-IPE-1 |
When the user clicks finish edit link and ends the invoice edition
When the user logs out

