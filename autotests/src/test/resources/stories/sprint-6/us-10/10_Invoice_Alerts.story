Meta:
@sprint 6
@us 10

Scenario: Alert productSku changesVerification stop edit button

Given there is the invoice with 'Invoice-IE-APCVSE' sku
And the user navigates to the invoice page with name 'Invoice-IE-APCVSE'
And the user logs in as 'departmentManager'
When the user inputs '!неттакого' in the invoice product 'productSku' field
And the user clicks finish edit button and ends the invoice edition
Then the user checks alert text is equal to 'У вас есть несохранённые данные'
When the user inputs '!' in the invoice product 'productSku' field
And the user clicks finish edit button and ends the invoice edition
Then the user checks there is no alert on the page

Scenario: Alert productSku changesVerification stop edit link

Given there is the invoice with 'Invoice-IE-APCVSE' sku
And the user navigates to the invoice page with name 'Invoice-IE-APCVSE'
And the user logs in as 'departmentManager'
When the user inputs '!неттакого' in the invoice product 'productSku' field
And the user clicks finish edit link and ends the invoice edition
Then the user checks alert text is equal to 'У вас есть несохранённые данные'
When the user inputs '!' in the invoice product 'productSku' field
When the user clicks finish edit button and ends the invoice edition
Then the user checks there is no alert on the page

Scenario: Alert productName changesVerification stop edit button

Given there is the invoice with 'Invoice-IE-APCVSE' sku
And the user navigates to the invoice page with name 'Invoice-IE-APCVSE'
And the user logs in as 'departmentManager'
When the user inputs '!неттакого' in the invoice product 'productName' field
And the user clicks finish edit button and ends the invoice edition
Then the user checks alert text is equal to 'У вас есть несохранённые данные'
When the user inputs '!' in the invoice product 'productName' field
And the user clicks finish edit button and ends the invoice edition
Then the user checks there is no alert on the page

Scenario: Alert productName changesVerification stop edit link

Given there is the invoice with 'Invoice-IE-APCVSE' sku
And the user navigates to the invoice page with name 'Invoice-IE-APCVSE'
And the user logs in as 'departmentManager'
When the user inputs '!неттакого' in the invoice product 'productName' field
And the user clicks finish edit link and ends the invoice edition
Then the user checks alert text is equal to 'У вас есть несохранённые данные'
When the user inputs '!' in the invoice product 'productName' field
And the user clicks finish edit button and ends the invoice edition
Then the user checks there is no alert on the page

Scenario: Alert productBarCode changesVerification stop edit button

Given there is the invoice with 'Invoice-IE-APCVSE' sku
And the user navigates to the invoice page with name 'Invoice-IE-APCVSE'
And the user logs in as 'departmentManager'
When the user inputs '!неттакого' in the invoice product 'productBarCode' field
And the user clicks finish edit button and ends the invoice edition
Then the user checks alert text is equal to 'У вас есть несохранённые данные'
When the user inputs '!' in the invoice product 'productBarCode' field
And the user clicks finish edit button and ends the invoice edition
Then the user checks there is no alert on the page

Scenario: Alert productBarCode changesVerification stop edit link

Given there is the invoice with 'Invoice-IE-APCVSE' sku
And the user navigates to the invoice page with name 'Invoice-IE-APCVSE'
And the user logs in as 'departmentManager'
When the user inputs '!неттакого' in the invoice product 'productBarCode' field
And the user clicks finish edit link and ends the invoice edition
Then the user checks alert text is equal to 'У вас есть несохранённые данные'
When the user inputs '!' in the invoice product 'productBarCode' field
And the user clicks finish edit button and ends the invoice edition
Then the user checks there is no alert on the page

Scenario: Alert productAmount changesVerification stop edit button

Given there is the invoice with 'Invoice-IE-APCVSE' sku
And the user navigates to the invoice page with name 'Invoice-IE-APCVSE'
And the user logs in as 'departmentManager'
When the user inputs 'неттакого' in the invoice product 'productAmount' field
And the user clicks finish edit button and ends the invoice edition
Then the user checks alert text is equal to 'У вас есть несохранённые данные'
When the user inputs '' in the invoice product 'productAmount' field
And the user clicks finish edit button and ends the invoice edition
Then the user checks there is no alert on the page

Scenario: Alert productAmount changesVerification stop edit link

Given there is the invoice with 'Invoice-IE-APCVSE' sku
And the user navigates to the invoice page with name 'Invoice-IE-APCVSE'
And the user logs in as 'departmentManager'
When the user inputs 'неттакого' in the invoice product 'productAmount' field
And the user clicks finish edit link and ends the invoice edition
Then the user checks alert text is equal to 'У вас есть несохранённые данные'
When the user inputs '' in the invoice product 'productAmount' field
And the user clicks finish edit button and ends the invoice edition
Then the user checks there is no alert on the page

Scenario: Alert invoice head verification sku stop edit button

Given there is the invoice with 'Invoice-IE-APCVSE' sku
And the user navigates to the invoice page with name 'Invoice-IE-APCVSE'
And the user logs in as 'departmentManager'
When the user clicks on 'sku' element to edit it
And the user inputs 'not a field' in the invoice 'inline sku' field
And the user clicks finish edit button and ends the invoice edition
Then the user checks alert text is equal to 'У вас есть несохранённые данные'
When the user clicks Cancel and discard changes
And the user clicks finish edit button and ends the invoice edition
Then the user checks there is no alert on the page

Scenario: Alert invoice head verification sku stop edit link

Given there is the invoice with 'Invoice-IE-APCVSE' sku
And the user navigates to the invoice page with name 'Invoice-IE-APCVSE'
And the user logs in as 'departmentManager'
When the user clicks on 'sku' element to edit it
And the user inputs 'not a field' in the invoice 'inline sku' field
And the user clicks finish edit link and ends the invoice edition
Then the user checks alert text is equal to 'У вас есть несохранённые данные'
When the user clicks Cancel and discard changes
And the user clicks finish edit button and ends the invoice edition
Then the user checks there is no alert on the page

Scenario: Alert invoice head verification supplier stop edit button

Given there is the invoice with 'Invoice-IE-APCVSE' sku
And the user navigates to the invoice page with name 'Invoice-IE-APCVSE'
And the user logs in as 'departmentManager'
When the user clicks on 'supplier' element to edit it
And the user inputs 'not a field' in the invoice 'inline supplier' field
And the user clicks finish edit button and ends the invoice edition
Then the user checks alert text is equal to 'У вас есть несохранённые данные'
When the user clicks Cancel and discard changes
And the user clicks finish edit button and ends the invoice edition
Then the user checks there is no alert on the page

Scenario: Alert invoice head verification supplier stop edit link

Given there is the invoice with 'Invoice-IE-APCVSE' sku
And the user navigates to the invoice page with name 'Invoice-IE-APCVSE'
And the user logs in as 'departmentManager'
When the user clicks on 'supplier' element to edit it
And the user inputs 'not a field' in the invoice 'inline supplier' field
And the user clicks finish edit link and ends the invoice edition
Then the user checks alert text is equal to 'У вас есть несохранённые данные'
When the user clicks Cancel and discard changes
And the user clicks finish edit button and ends the invoice edition
Then the user checks there is no alert on the page

Scenario: Alert invoice head verification accepter stop edit button

Given there is the invoice with 'Invoice-IE-APCVSE' sku
And the user navigates to the invoice page with name 'Invoice-IE-APCVSE'
And the user logs in as 'departmentManager'
When the user clicks on 'accepter' element to edit it
And the user inputs 'not a field' in the invoice 'inline accepter' field
And the user clicks finish edit button and ends the invoice edition
Then the user checks alert text is equal to 'У вас есть несохранённые данные'
When the user clicks Cancel and discard changes
And the user clicks finish edit button and ends the invoice edition
Then the user checks there is no alert on the page

Scenario: Alert invoice head verification accepter stop edit link

Given there is the invoice with 'Invoice-IE-APCVSE' sku
And the user navigates to the invoice page with name 'Invoice-IE-APCVSE'
And the user logs in as 'departmentManager'
When the user clicks on 'accepter' element to edit it
And the user inputs 'not a field' in the invoice 'inline accepter' field
And the user clicks finish edit link and ends the invoice edition
Then the user checks alert text is equal to 'У вас есть несохранённые данные'
When the user clicks Cancel and discard changes
And the user clicks finish edit button and ends the invoice edition
Then the user checks there is no alert on the page

Scenario: Alert invoice head verification legalEntity stop edit button

Given there is the invoice with 'Invoice-IE-APCVSE' sku
And the user navigates to the invoice page with name 'Invoice-IE-APCVSE'
And the user logs in as 'departmentManager'
When the user clicks on 'legalEntity' element to edit it
And the user inputs 'not a field' in the invoice 'inline legalEntity' field
And the user clicks finish edit button and ends the invoice edition
Then the user checks alert text is equal to 'У вас есть несохранённые данные'
When the user clicks Cancel and discard changes
And the user clicks finish edit button and ends the invoice edition
Then the user checks there is no alert on the page

Scenario: Alert invoice head verification legalEntity stop edit link

Given there is the invoice with 'Invoice-IE-APCVSE' sku
And the user navigates to the invoice page with name 'Invoice-IE-APCVSE'
And the user logs in as 'departmentManager'
When the user clicks on 'legalEntity' element to edit it
And the user inputs 'not a field' in the invoice 'inline legalEntity' field
And the user clicks finish edit link and ends the invoice edition
Then the user checks alert text is equal to 'У вас есть несохранённые данные'
When the user clicks Cancel and discard changes
And the user clicks finish edit button and ends the invoice edition
Then the user checks there is no alert on the page

Scenario: Alert invoice head verification acceptanceDate stop edit button

Given there is the invoice with 'Invoice-IE-APCVSE' sku
And the user navigates to the invoice page with name 'Invoice-IE-APCVSE'
And the user logs in as 'departmentManager'
When the user clicks on 'acceptanceDate' element to edit it
And the user inputs '!03.03.2012 14:56' in the invoice 'inline acceptanceDate' field
And the user clicks finish edit button and ends the invoice edition
Then the user checks alert text is equal to 'У вас есть несохранённые данные'
When the user clicks Cancel and discard changes
And the user clicks finish edit button and ends the invoice edition
Then the user checks there is no alert on the page

Scenario: Alert invoice head verification acceptanceDate stop edit link

Given there is the invoice with 'Invoice-IE-APCVSE' sku
And the user navigates to the invoice page with name 'Invoice-IE-APCVSE'
And the user logs in as 'departmentManager'
When the user clicks on 'acceptanceDate' element to edit it
And the user inputs '!03.03.2012 14:56' in the invoice 'inline acceptanceDate' field
And the user clicks finish edit link and ends the invoice edition
Then the user checks alert text is equal to 'У вас есть несохранённые данные'
When the user clicks Cancel and discard changes
And the user clicks finish edit button and ends the invoice edition
Then the user checks there is no alert on the page

Scenario: Alert invoice head verification supplierInvoiceDate stop edit button

Given there is the invoice with 'Invoice-IE-APCVSE' sku
And the user navigates to the invoice page with name 'Invoice-IE-APCVSE'
And the user logs in as 'departmentManager'
When the user clicks on 'supplierInvoiceDate' element to edit it
And the user inputs '!02.03.2012' in the invoice 'inline supplierInvoiceDate' field
And the user clicks finish edit button and ends the invoice edition
Then the user checks alert text is equal to 'У вас есть несохранённые данные'
When the user clicks Cancel and discard changes
And the user clicks finish edit button and ends the invoice edition
Then the user checks there is no alert on the page

Scenario: Alert invoice head verification supplierInvoiceDate stop edit link

Given there is the invoice with 'Invoice-IE-APCVSE' sku
And the user navigates to the invoice page with name 'Invoice-IE-APCVSE'
And the user logs in as 'departmentManager'
When the user clicks on 'supplierInvoiceDate' element to edit it
And the user inputs '!02.03.2012' in the invoice 'inline supplierInvoiceDate' field
And the user clicks finish edit link and ends the invoice edition
Then the user checks alert text is equal to 'У вас есть несохранённые данные'
When the user clicks Cancel and discard changes
And the user clicks finish edit button and ends the invoice edition
Then the user checks there is no alert on the page

Scenario: Alert invoice head verification supplierInvoiceSku stop edit button

Given there is the invoice with 'Invoice-IE-APCVSE' sku
And the user navigates to the invoice page with name 'Invoice-IE-APCVSE'
And the user logs in as 'departmentManager'
When the user clicks on 'supplierInvoiceSku' element to edit it
And the user inputs 'not a field' in the invoice 'inline supplierInvoiceSku' field
And the user clicks finish edit button and ends the invoice edition
Then the user checks alert text is equal to 'У вас есть несохранённые данные'
When the user clicks Cancel and discard changes
And the user clicks finish edit button and ends the invoice edition
Then the user checks there is no alert on the page

Scenario: Alert invoice head verification supplierInvoiceSku stop edit link

Given there is the invoice with 'Invoice-IE-APCVSE' sku
And the user navigates to the invoice page with name 'Invoice-IE-APCVSE'
And the user logs in as 'departmentManager'
When the user clicks on 'supplierInvoiceSku' element to edit it
And the user inputs 'not a field' in the invoice 'inline supplierInvoiceSku' field
And the user clicks finish edit link and ends the invoice edition
Then the user checks alert text is equal to 'У вас есть несохранённые данные'
When the user clicks Cancel and discard changes
And the user clicks finish edit button and ends the invoice edition
Then the user checks there is no alert on the page

Scenario: Alert invoice product verification ProductBarCode stop edit button

Given there is the product with 'IE-IPA-1' name, 'IE-IPA-1' sku, 'IE-IPA-1' barcode, 'liter' units
And there is the invoice 'Invoice-IE-AIOVOSSEEE1' with product 'IE-IPE' name, 'IE-IPE' sku, 'IE-IPE' barcode, 'liter' units
And the user navigates to the invoice page with name 'Invoice-IE-AIOVOSSEEE1'
And the user logs in as 'departmentManager'
When the user clicks on 'productBarcode' element of invoice product with 'IE-IPE' sku
And the user inputs 'IE-IPA-1' in the invoice 'inline productBarCode' field
And the user clicks finish edit button and ends the invoice edition
Then the user checks alert text is equal to 'У вас есть несохранённые данные'
When the user clicks Cancel and discard changes
And the user clicks finish edit button and ends the invoice edition
Then the user checks there is no alert on the page

Scenario: Alert invoice product verification ProductName stop edit button

Given there is the product with 'IE-IPA-1' name, 'IE-IPA-1' sku, 'IE-IPA-1' barcode, 'liter' units
And there is the invoice 'Invoice-IE-AIOVOSSEEE2' with product 'IE-IPE' name, 'IE-IPE' sku, 'IE-IPE' barcode, 'liter' units
And the user navigates to the invoice page with name 'Invoice-IE-AIOVOSSEEE2'
And the user logs in as 'departmentManager'
When the user clicks on 'productName' element of invoice product with 'IE-IPE' sku
And the user inputs 'IE-IPA-1' in the invoice 'inline productName' field
And the user clicks finish edit button and ends the invoice edition
Then the user checks alert text is equal to 'У вас есть несохранённые данные'
When the user clicks Cancel and discard changes
And the user clicks finish edit button and ends the invoice edition
Then the user checks there is no alert on the page

Scenario: Alert invoice product verification ProductSku stop edit button

Given there is the product with 'IE-IPA-1' name, 'IE-IPA-1' sku, 'IE-IPA-1' barcode, 'liter' units
And there is the invoice 'Invoice-IE-AIOVOSSEEE3' with product 'IE-IPE' name, 'IE-IPE' sku, 'IE-IPE' barcode, 'liter' units
And the user navigates to the invoice page with name 'Invoice-IE-AIOVOSSEEE3'
And the user logs in as 'departmentManager'
When the user clicks on 'productSku' element of invoice product with 'IE-IPE' sku
And the user inputs 'IE-IPA-1' in the invoice 'inline productSku' field
And the user clicks finish edit button and ends the invoice edition
Then the user checks alert text is equal to 'У вас есть несохранённые данные'
When the user clicks Cancel and discard changes
And the user clicks finish edit button and ends the invoice edition
Then the user checks there is no alert on the page

Scenario: Alert invoice product verification productAmount stop edit button

Given there is the product with 'IE-IPA-1' name, 'IE-IPA-1' sku, 'IE-IPA-1' barcode, 'liter' units
And there is the invoice 'Invoice-IE-AIOVOSSEEE4' with product 'IE-IPE' name, 'IE-IPE' sku, 'IE-IPE' barcode, 'liter' units
And the user navigates to the invoice page with name 'Invoice-IE-AIOVOSSEEE4'
And the user logs in as 'departmentManager'
When the user clicks on 'productAmount' element of invoice product with 'IE-IPE' sku
And the user inputs '2' in the invoice 'inline quantity' field
And the user clicks finish edit button and ends the invoice edition
Then the user checks alert text is equal to 'У вас есть несохранённые данные'
When the user clicks Cancel and discard changes
And the user clicks finish edit button and ends the invoice edition
Then the user checks there is no alert on the page

Scenario: Alert invoice product verification ProductPrice stop edit button

Given there is the product with 'IE-IPA-1' name, 'IE-IPA-1' sku, 'IE-IPA-1' barcode, 'liter' units
And there is the invoice 'Invoice-IE-AIOVOSSEEE5' with product 'IE-IPE' name, 'IE-IPE' sku, 'IE-IPE' barcode, 'liter' units
And the user navigates to the invoice page with name 'Invoice-IE-AIOVOSSEEE5'
And the user logs in as 'departmentManager'
When the user clicks on 'productPrice' element of invoice product with 'IE-IPE' sku
And the user inputs '3' in the invoice 'inline price' field
And the user clicks finish edit button and ends the invoice edition
Then the user checks alert text is equal to 'У вас есть несохранённые данные'
When the user clicks Cancel and discard changes
And the user clicks finish edit button and ends the invoice edition
Then the user checks there is no alert on the page

Scenario: Alert invoice product verification ProductBarCode stop edit link

Given there is the product with 'IE-IPA-1' name, 'IE-IPA-1' sku, 'IE-IPA-1' barcode, 'liter' units
And there is the invoice 'Invoice-IE-AIOVOSSEEE6' with product 'IE-IPE' name, 'IE-IPE' sku, 'IE-IPE' barcode, 'liter' units
And the user navigates to the invoice page with name 'Invoice-IE-AIOVOSSEEE6'
And the user logs in as 'departmentManager'
When the user clicks on 'productBarcode' element of invoice product with 'IE-IPE' sku
And the user inputs 'IE-IPA-1' in the invoice 'inline productBarCode' field
And the user clicks finish edit link and ends the invoice edition
Then the user checks alert text is equal to 'У вас есть несохранённые данные'
When the user clicks Cancel and discard changes
And the user clicks finish edit link and ends the invoice edition
Then the user checks there is no alert on the page

Scenario: Alert invoice product verification ProductName stop edit link

Given there is the product with 'IE-IPA-1' name, 'IE-IPA-1' sku, 'IE-IPA-1' barcode, 'liter' units
And there is the invoice 'Invoice-IE-AIOVOSSEEE7' with product 'IE-IPE' name, 'IE-IPE' sku, 'IE-IPE' barcode, 'liter' units
And the user navigates to the invoice page with name 'Invoice-IE-AIOVOSSEEE7'
And the user logs in as 'departmentManager'
When the user clicks on 'productName' element of invoice product with 'IE-IPE' sku
And the user inputs 'IE-IPA-1' in the invoice 'inline productName' field
And the user clicks finish edit link and ends the invoice edition
Then the user checks alert text is equal to 'У вас есть несохранённые данные'
When the user clicks Cancel and discard changes
And the user clicks finish edit link and ends the invoice edition
Then the user checks there is no alert on the page

Scenario: Alert invoice product verification ProductSku stop edit link

Given there is the product with 'IE-IPA-1' name, 'IE-IPA-1' sku, 'IE-IPA-1' barcode, 'liter' units
And there is the invoice 'Invoice-IE-AIOVOSSEEE8' with product 'IE-IPE' name, 'IE-IPE' sku, 'IE-IPE' barcode, 'liter' units
And the user navigates to the invoice page with name 'Invoice-IE-AIOVOSSEEE8'
And the user logs in as 'departmentManager'
When the user clicks on 'productSku' element of invoice product with 'IE-IPE' sku
And the user inputs 'IE-IPA-1' in the invoice 'inline productSku' field
And the user clicks finish edit link and ends the invoice edition
Then the user checks alert text is equal to 'У вас есть несохранённые данные'
When the user clicks Cancel and discard changes
And the user clicks finish edit link and ends the invoice edition
Then the user checks there is no alert on the page

Scenario: Alert invoice product verification productAmount stop edit link

Given there is the product with 'IE-IPA-1' name, 'IE-IPA-1' sku, 'IE-IPA-1' barcode, 'liter' units
And there is the invoice 'Invoice-IE-AIOVOSSEEE9' with product 'IE-IPE' name, 'IE-IPE' sku, 'IE-IPE' barcode, 'liter' units
And the user navigates to the invoice page with name 'Invoice-IE-AIOVOSSEEE9'
And the user logs in as 'departmentManager'
When the user clicks on 'productAmount' element of invoice product with 'IE-IPE' sku
And the user inputs '2' in the invoice 'inline quantity' field
And the user clicks finish edit link and ends the invoice edition
Then the user checks alert text is equal to 'У вас есть несохранённые данные'
When the user clicks Cancel and discard changes
And the user clicks finish edit link and ends the invoice edition
Then the user checks there is no alert on the page

Scenario: Alert invoice product verification ProductPrice stop edit link

Given there is the product with 'IE-IPA-1' name, 'IE-IPA-1' sku, 'IE-IPA-1' barcode, 'liter' units
And there is the invoice 'Invoice-IE-AIOVOSSEEE10' with product 'IE-IPE' name, 'IE-IPE' sku, 'IE-IPE' barcode, 'liter' units
And the user navigates to the invoice page with name 'Invoice-IE-AIOVOSSEEE10'
And the user logs in as 'departmentManager'
When the user clicks on 'productPrice' element of invoice product with 'IE-IPE' sku
And the user inputs '3' in the invoice 'inline price' field
And the user clicks finish edit link and ends the invoice edition
Then the user checks alert text is equal to 'У вас есть несохранённые данные'
When the user clicks Cancel and discard changes
And the user clicks finish edit link and ends the invoice edition
Then the user checks there is no alert on the page