Meta:
@sprint 21
@us 46

Narrative:
As a Когда накладная в LH не соответствует реальной,
I want to я хочу найти эту накладную в LH и исправить ошибку,
In order to данные в LH стали правильными

Scenario: Nothing found

Given there is the user with name 'departmentManager-UIBS-NF', position 'departmentManager-UIBS-NF', username 'departmentManager-UIBS-NF', password 'lighthouse', role 'departmentManager'
And there is the store with number 'UIBS-NF' managed by department manager named 'departmentManager-UIBS-NF'
And the user is on the invoice list page
When the user logs in using 'departmentManager-UIBS-NF' userName and 'lighthouse' password
And the user clicks the local navigation invoice search link
And the user searches invoice by sku or supplier sku 'Янехочуискатьнакладныеяхочуплатье'
And the user clicks the invoice search buttton and starts the search
Then the user checks the form results text is 'Мы не смогли найти накладную с номером Янехочуискатьнакладныеяхочуплатье'

Scenario: Invoice results found by sku

Given there is the user with name 'departmentManager-UIBS-NF', position 'departmentManager-UIBS-NF', username 'departmentManager-UIBS-NF', password 'lighthouse', role 'departmentManager'
And there is the store with number 'UIBS-NF' managed by department manager named 'departmentManager-UIBS-NF'
And there is the invoice in the store with number 'UIBS-NF' ruled by department manager with name 'departmentManager-UIBS-NF' with values
| elementName | elementValue |
| sku | Invoice-UIBS-NF-1 |
| acceptanceDate | 02.04.2013 16:23 |
| supplier | supplier |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceSku | supplierInvoiceSku |
| supplierInvoiceDate | 02.04.2013 |
And the user is on the invoice list page
When the user logs in using 'departmentManager-UIBS-NF' userName and 'lighthouse' password
And the user clicks the local navigation invoice search link
And the user searches invoice by sku or supplier sku 'Invoice-UIBS-NF-1'
And the user clicks the invoice search buttton and starts the search
Then the user checks the form results text is 'Накладная нашлась!'
And the user checks the invoice with sku 'Invoice-UIBS-NF-1' in search results
And the user checks the invoice with sku 'Invoice-UIBS-NF-1' in search results with stored values
And the user checks the highlighted text is 'Накладная № Invoice-UIBS-NF-1 от 02.04.2013 16:23'

Scenario: Invoice results found by supplier sku

Given there is the user with name 'departmentManager-UIBS-NF', position 'departmentManager-UIBS-NF', username 'departmentManager-UIBS-NF', password 'lighthouse', role 'departmentManager'
And there is the store with number 'UIBS-NF' managed by department manager named 'departmentManager-UIBS-NF'
And there is the invoice in the store with number 'UIBS-NF' ruled by department manager with name 'departmentManager-UIBS-NF' with values
| elementName | elementValue |
| sku | Invoice-UIBS-NF-2 |
| acceptanceDate | 02.04.2013 16:23 |
| supplier | supplier |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceSku | Invoice-SS-UIBS-NF-2 |
| supplierInvoiceDate | 02.04.2013 |
And the user is on the invoice list page
When the user logs in using 'departmentManager-UIBS-NF' userName and 'lighthouse' password
And the user clicks the local navigation invoice search link
And the user searches invoice by sku or supplier sku 'Invoice-SS-UIBS-NF-2'
And the user clicks the invoice search buttton and starts the search
Then the user checks the form results text is 'Накладная нашлась!'
Then the user checks the invoice with sku 'Invoice-UIBS-NF-2' in search results
And the user checks the invoice with sku 'Invoice-UIBS-NF-2' in search results with stored values
And the user checks the highlighted text is 'Входящая накладная № Invoice-SS-UIBS-NF-2 от 02.04.2013'

Scenario: Invoice results found by sku and supplier sku

Given there is the user with name 'departmentManager-UIBS-NF', position 'departmentManager-UIBS-NF', username 'departmentManager-UIBS-NF', password 'lighthouse', role 'departmentManager'
And there is the store with number 'UIBS-NF' managed by department manager named 'departmentManager-UIBS-NF'
And there is the invoice in the store with number 'UIBS-NF' ruled by department manager with name 'departmentManager-UIBS-NF' with values
| elementName | elementValue |
| sku | Invoice-UIBS-NF-2 |
| acceptanceDate | 02.04.2013 16:23 |
| supplier | supplier |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceSku | supplierInvoiceSku |
| supplierInvoiceDate | 02.04.2013 |
And there is the invoice in the store with number 'UIBS-NF' ruled by department manager with name 'departmentManager-UIBS-NF' with values
| elementName | elementValue |
| sku | Invoice-UIBS-NF-3 |
| acceptanceDate | 02.04.2013 16:23 |
| supplier | supplier |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceSku | Invoice-UIBS-NF-2 |
| supplierInvoiceDate | 02.04.2013 |
And the user is on the invoice list page
When the user logs in using 'departmentManager-UIBS-NF' userName and 'lighthouse' password
And the user clicks the local navigation invoice search link
And the user searches invoice by sku or supplier sku 'Invoice-UIBS-NF-2'
And the user clicks the invoice search buttton and starts the search
Then the user checks the form results text is 'Нашлось 2 накладных!'
Then the user checks the invoice with sku 'Invoice-UIBS-NF-2' in search results
Then the user checks the invoice with sku 'Invoice-UIBS-NF-3' in search results
And the user checks the highlighted text is 'Накладная № Invoice-UIBS-NF-2 от 02.04.2013 16:23'
And the user checks the highlighted text is 'Входящая накладная № Invoice-UIBS-NF-2 от 02.04.2013'

Scenario: Invoice results found by duplicated sku (case where sku is duplicated)

Given there is the user with name 'departmentManager-UIBS-NF', position 'departmentManager-UIBS-NF', username 'departmentManager-UIBS-NF', password 'lighthouse', role 'departmentManager'
And there is the store with number 'UIBS-NF' managed by department manager named 'departmentManager-UIBS-NF'
And there is the invoice in the store with number 'UIBS-NF' ruled by department manager with name 'departmentManager-UIBS-NF' with values
| elementName | elementValue |
| sku | Invoice-UIBS-NF-4 |
| acceptanceDate | 02.04.2013 16:23 |
| supplier | supplier |
| accepter | accepter |
| legalEntity | legalEntity |
And the user is on the invoice list page
When the user logs in using 'departmentManager-UIBS-NF' userName and 'lighthouse' password
When the user clicks the create button on the invoice list page
And the user inputs 'Invoice-UIBS-NF-4' in the invoice 'sku' field
And the user inputs '02.04.2013 16:23' in the invoice 'acceptanceDate' field
And the user inputs 'ОАЭ Поставщик в квадрате' in the invoice 'supplier' field
And the user inputs 'Иван Петрович Петрович' in the invoice 'accepter' field
And the user inputs 'ООО Компания' in the invoice 'legalEntity' field
And the user navigates to invoice product addition
When the user clicks finish edit button and ends the invoice edition
And the user clicks the local navigation invoice search link
And the user searches invoice by sku or supplier sku 'Invoice-UIBS-NF-4'
And the user clicks the invoice search buttton and starts the search
Then the user checks the form results text is 'Нашлось 2 накладных!'

Scenario: Invoice results found by supplier sku (case where supplier sku is duplicated)

Given there is the user with name 'departmentManager-UIBS-NF', position 'departmentManager-UIBS-NF', username 'departmentManager-UIBS-NF', password 'lighthouse', role 'departmentManager'
And there is the store with number 'UIBS-NF' managed by department manager named 'departmentManager-UIBS-NF'
And there is the invoice in the store with number 'UIBS-NF' ruled by department manager with name 'departmentManager-UIBS-NF' with values
| elementName | elementValue |
| sku | Invoice-UIBS-NF-5 |
| acceptanceDate | 02.04.2013 16:23 |
| supplier | supplier |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceSku | IRFBSK-supplierSku-10 |
And there is the invoice in the store with number 'UIBS-NF' ruled by department manager with name 'departmentManager-UIBS-NF' with values
| elementName | elementValue |
| sku | Invoice-UIBS-NF-6 |
| acceptanceDate | 02.04.2013 16:23 |
| supplier | supplier |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceSku | IRFBSK-supplierSku-10 |
And the user is on the invoice list page
When the user logs in using 'departmentManager-UIBS-NF' userName and 'lighthouse' password
And the user clicks the local navigation invoice search link
And the user searches invoice by sku or supplier sku 'IRFBSK-supplierSku-10'
And the user clicks the invoice search buttton and starts the search
Then the user checks the form results text is 'Нашлось 2 накладных!'
Then the user checks the invoice with sku 'Invoice-UIBS-NF-5' in search results
Then the user checks the invoice with sku 'Invoice-UIBS-NF-6' in search results

Scenario: Invoice results found by sku and supplier sku (case where supplier sku is duplicated)

Given there is the user with name 'departmentManager-UIBS-NF', position 'departmentManager-UIBS-NF', username 'departmentManager-UIBS-NF', password 'lighthouse', role 'departmentManager'
And there is the store with number 'UIBS-NF' managed by department manager named 'departmentManager-UIBS-NF'
And there is the invoice in the store with number 'UIBS-NF' ruled by department manager with name 'departmentManager-UIBS-NF' with values
| elementName | elementValue |
| sku | IRFBSK-supplierSku-11 |
| acceptanceDate | 02.04.2013 16:23 |
| supplier | supplier |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceSku | IRFBSK-supplierSku-11 |
And there is the invoice in the store with number 'UIBS-NF' ruled by department manager with name 'departmentManager-UIBS-NF' with values
| elementName | elementValue |
| sku | Invoice-UIBS-NF-7 |
| acceptanceDate | 02.04.2013 16:23 |
| supplier | supplier |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceSku | IRFBSK-supplierSku-11 |
And there is the invoice in the store with number 'UIBS-NF' ruled by department manager with name 'departmentManager-UIBS-NF' with values
| elementName | elementValue |
| sku | Invoice-UIBS-NF-8 |
| acceptanceDate | 02.04.2013 16:23 |
| supplier | supplier |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceSku | IRFBSK-supplierSku-11 |
And the user is on the invoice list page
When the user logs in using 'departmentManager-UIBS-NF' userName and 'lighthouse' password
And the user clicks the local navigation invoice search link
And the user searches invoice by sku or supplier sku 'IRFBSK-supplierSku-11'
And the user clicks the invoice search buttton and starts the search
Then the user checks the form results text is 'Нашлось 3 накладных!'
Then the user checks the invoice with sku 'IRFBSK-supplierSku-11' in search results
Then the user checks the invoice with sku 'Invoice-UIBS-NF-7' in search results
Then the user checks the invoice with sku 'Invoice-UIBS-NF-8' in search results

Scenario: Invoice full scenario edition from search result page

Given there is the user with name 'departmentManager-UIBS-NF', position 'departmentManager-UIBS-NF', username 'departmentManager-UIBS-NF', password 'lighthouse', role 'departmentManager'
And there is the store with number 'UIBS-NF' managed by department manager named 'departmentManager-UIBS-NF'
And there is the invoice in the store with number 'UIBS-NF' ruled by department manager with name 'departmentManager-UIBS-NF' with values
| elementName | elementValue |
| sku | Invoice-UIBS-NF-9 |
| acceptanceDate | 02.04.2013 16:23 |
| supplier | supplier |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceSku | IRFBSK-supplierSku-110 |
| supplierInvoiceDate | 02.04.2013 |
And the user is on the invoice list page
When the user logs in using 'departmentManager-UIBS-NF' userName and 'lighthouse' password
And the user clicks the local navigation invoice search link
And the user searches invoice by sku or supplier sku 'Invoice-UIBS-NF-9'
And the user clicks the invoice search buttton and starts the search
Then the user checks the form results text is 'Накладная нашлась!'
Then the user checks the invoice with sku 'Invoice-UIBS-NF-9' in search results
And the user checks the invoice with sku 'Invoice-UIBS-NF-9' in search results with stored values
When the user clicks on the search result invoice with sku 'Invoice-UIBS-NF-9'
When the user clicks edit button and starts invoice edition
When the user edits 'sku' element with new value 'Invoice-UIBS-NF-9-Edited' and verify the 'head' changes
When the user edits 'supplierInvoiceDate' element with new value '02.03.2012' and verify the 'head' changes
When the user edits 'supplier' element with new value 'Другой поставщик' and verify the 'head' changes
When the user edits 'accepter' element with new value 'Сидоров Иван Сидорович' and verify the 'head' changes
When the user edits 'legalEntity' element with new value 'Другая компания' and verify the 'head' changes
When the user edits 'supplierInvoiceSku' element with new value 'IRFBSK-supplierSku-111' and verify the 'head' changes
When the user edits 'acceptanceDate' element with new value '03.03.2012 14:56' and verify the 'head' changes
When the user clicks finish edit button and ends the invoice edition
Then the user checks invoice 'head' elements  values
| elementName | value |
| sku | Invoice-UIBS-NF-9-Edited |
| supplier | Другой поставщик |
| accepter | Сидоров Иван Сидорович |
| legalEntity | Другая компания |
| acceptanceDate | 03.03.2012 14:56 |
| supplierInvoiceDate | 02.03.2012 |
| supplierInvoiceSku | IRFBSK-supplierSku-111 |

Scenario: Invoice with product found

Given there is the user with name 'departmentManager-UIBS-NF', position 'departmentManager-UIBS-NF', username 'departmentManager-UIBS-NF', password 'lighthouse', role 'departmentManager'
And there is the store with number 'UIBS-NF' managed by department manager named 'departmentManager-UIBS-NF'
Given there is the subCategory with name 'ProductsUpdateInvoiceSubCategory' related to group named 'ProductsUpdateInvoiceGroup' and category named 'ProductsUpdateInvoiceCategory'
And there is the product with 'Корм Баффет д/кошек мясн.кус.в желе Морской коктейль 375г' name, '7300330094019' sku, '7300330094025' barcode, 'unit' units, '97,60' purchasePrice of group named 'ProductsUpdateInvoiceGroup', category named 'ProductsUpdateInvoiceCategory', subcategory named 'ProductsUpdateInvoiceSubCategory'
And there is the invoice in the store with number 'UIBS-NF' ruled by department manager with name 'departmentManager-UIBS-NF' with values
| elementName | elementValue |
| sku | Invoice-UIBS-NF-10 |
| acceptanceDate | 02.04.2013 16:23 |
| supplier | supplier |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceSku | supplierInvoiceSku |
| supplierInvoiceDate | 02.04.2013 |
And the user adds the product to the invoice with name 'Invoice-UIBS-NF-10' with sku '7300330094019', quantity '2', price '12,51' in the store ruled by 'departmentManager-UIBS-NF'
And the user is on the invoice list page
When the user logs in using 'departmentManager-UIBS-NF' userName and 'lighthouse' password
And the user clicks the local navigation invoice search link
And the user searches invoice by sku or supplier sku 'Invoice-UIBS-NF-10'
And the user clicks the invoice search buttton and starts the search
Then the user checks the form results text is 'Накладная нашлась!'
And the user checks the invoice with sku 'Invoice-UIBS-NF-10' in search results
And the user checks the invoice with sku 'Invoice-UIBS-NF-10' in search results with stored values
Then the user checks the product with '7300330094019' sku has values
| elementName | value |
| productName | Корм Баффет д/кошек мясн.кус.в желе Морской коктейль 375г |
| productSku | 7300330094019 |
| productBarcode | 7300330094025 |
| productUnits | шт. |
| productAmount | 2 |
| productPrice | 12,51 |
| productSum | 25,02 |
And the user checks invoice elements values
| elementName | value |
| totalProducts | 1 |
| totalSum | 25,02 |
And the user checks the highlighted text is 'Накладная № Invoice-UIBS-NF-10 от 02.04.2013 16:23'

