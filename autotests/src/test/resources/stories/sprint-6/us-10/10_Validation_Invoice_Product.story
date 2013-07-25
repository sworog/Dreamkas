Meta:
@sprint 6
@us 10

Scenario: Invoice product edition - Invoice product amount is required

Given there is the invoice 'InvoiceProduct-IPE-Common' with product 'IE-IPE' name, 'IE-IPE' sku, 'IE-IPE' barcode, 'liter' units
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'InvoiceProduct-IPE-Common' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'productAmountView' element of invoice product with 'IE-IPE' sku to edit
And the user inputs '' in the invoice 'inline quantity' field
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Заполните это поле |
When the user clicks Cancel and discard changes
And the user clicks finish edit link and ends the invoice edition
And the user logs out

Scenario: Invoice product edition - Invoice product price is required

Given there is the invoice 'InvoiceProduct-IPE-Common' with product 'IE-IPE' name, 'IE-IPE' sku, 'IE-IPE' barcode, 'liter' units
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'InvoiceProduct-IPE-Common' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'productPriceView' element of invoice product with 'IE-IPE' sku to edit
And the user inputs '' in the invoice 'inline price' field
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Заполните это поле |
When the user clicks Cancel and discard changes
And the user clicks finish edit link and ends the invoice edition
And the user logs out

Scenario: Invoice product edition - Invoice no product name validation

Given there is the invoice 'InvoiceProduct-IPE-Common' with product 'IE-IPE' name, 'IE-IPE' sku, 'IE-IPE' barcode, 'liter' units
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'InvoiceProduct-IPE-Common' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'productNameView' element of invoice product with 'IE-IPE' sku to edit
And the user inputs '!такогонесуществует' in the invoice 'inline productName' field
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Такого товара не существует |
When the user clicks Cancel and discard changes
And the user clicks finish edit link and ends the invoice edition
And the user logs out

Scenario: Invoice product edition - Invoice no product barcode validation

Given there is the invoice 'InvoiceProduct-IPE-Common' with product 'IE-IPE' name, 'IE-IPE' sku, 'IE-IPE' barcode, 'liter' units
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'InvoiceProduct-IPE-Common' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'productBarcodeView' element of invoice product with 'IE-IPE' sku to edit
And the user inputs '!такогонесуществует' in the invoice 'inline productBarCode' field
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Такого товара не существует |
When the user clicks Cancel and discard changes
And the user clicks finish edit link and ends the invoice edition
And the user logs out

Scenario: Invoice product edition - Invoice no product sku validation

Given there is the invoice 'InvoiceProduct-IPE-Common' with product 'IE-IPE' name, 'IE-IPE' sku, 'IE-IPE' barcode, 'liter' units
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'InvoiceProduct-IPE-Common' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'productSkuView' element of invoice product with 'IE-IPE' sku to edit
And the user inputs '!такогонесуществует' in the invoice 'inline productSku' field
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Такого товара не существует |
When the user clicks Cancel and discard changes
And the user clicks finish edit link and ends the invoice edition
And the user logs out

Scenario: Invoice product edition - Invoice product amount validation sub zero

Given there is the invoice 'InvoiceProduct-IPE-Common' with product 'IE-IPE' name, 'IE-IPE' sku, 'IE-IPE' barcode, 'liter' units
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'InvoiceProduct-IPE-Common' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'productAmountView' element of invoice product with 'IE-IPE' sku to edit
And the user inputs '-10' in the invoice 'inline quantity' field
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Значение должно быть больше 0 |
When the user clicks Cancel and discard changes
And the user clicks finish edit link and ends the invoice edition
And the user logs out

Scenario: Invoice product edition - Invoice product amount validation zero

Given there is the invoice 'InvoiceProduct-IPE-Common' with product 'IE-IPE' name, 'IE-IPE' sku, 'IE-IPE' barcode, 'liter' units
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'InvoiceProduct-IPE-Common' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'productAmountView' element of invoice product with 'IE-IPE' sku to edit
And the user inputs '0' in the invoice 'inline quantity' field
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Значение должно быть больше 0 |
When the user clicks Cancel and discard changes
And the user clicks finish edit link and ends the invoice edition
And the user logs out

Scenario: Invoice product edition - Invoice product amount validation String en small register

Given there is the invoice 'InvoiceProduct-IPE-Common' with product 'IE-IPE' name, 'IE-IPE' sku, 'IE-IPE' barcode, 'liter' units
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'InvoiceProduct-IPE-Common' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'productAmountView' element of invoice product with 'IE-IPE' sku to edit
And the user inputs 'asdd' in the invoice 'inline quantity' field
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Значение должно быть целым числом |
When the user clicks Cancel and discard changes
And the user clicks finish edit link and ends the invoice edition
And the user logs out

Scenario: Invoice product edition - Invoice product amount validation String en big register

Given there is the invoice 'InvoiceProduct-IPE-Common' with product 'IE-IPE' name, 'IE-IPE' sku, 'IE-IPE' barcode, 'liter' units
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'InvoiceProduct-IPE-Common' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'productAmountView' element of invoice product with 'IE-IPE' sku to edit
And the user inputs 'ADHF' in the invoice 'inline quantity' field
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Значение должно быть целым числом |
When the user clicks Cancel and discard changes
And the user clicks finish edit link and ends the invoice edition
And the user logs out

Scenario: Invoice product edition - Invoice product amount validation String rus small register

Given there is the invoice 'InvoiceProduct-IPE-Common' with product 'IE-IPE' name, 'IE-IPE' sku, 'IE-IPE' barcode, 'liter' units
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'InvoiceProduct-IPE-Common' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'productAmountView' element of invoice product with 'IE-IPE' sku to edit
And the user inputs 'домик' in the invoice 'inline quantity' field
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Значение должно быть целым числом |
When the user clicks Cancel and discard changes
And the user clicks finish edit link and ends the invoice edition
And the user logs out

Scenario: Invoice product edition - Invoice product amount validation String rus big register

Given there is the invoice 'InvoiceProduct-IPE-Common' with product 'IE-IPE' name, 'IE-IPE' sku, 'IE-IPE' barcode, 'liter' units
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'InvoiceProduct-IPE-Common' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'productAmountView' element of invoice product with 'IE-IPE' sku to edit
And the user inputs 'Домище' in the invoice 'inline quantity' field
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Значение должно быть целым числом |
When the user clicks Cancel and discard changes
And the user clicks finish edit link and ends the invoice edition
And the user logs out

Scenario: Invoice product edition - Invoice product amount validation symbols

Given there is the invoice 'InvoiceProduct-IPE-Common' with product 'IE-IPE' name, 'IE-IPE' sku, 'IE-IPE' barcode, 'liter' units
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'InvoiceProduct-IPE-Common' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'productAmountView' element of invoice product with 'IE-IPE' sku to edit
And the user inputs '^%#$)&' in the invoice 'inline quantity' field
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Значение должно быть целым числом |
When the user clicks Cancel and discard changes
And the user clicks finish edit link and ends the invoice edition
And the user logs out

Scenario: Invoice product edition - Invoice product Amount positive validation

Given there is the invoice 'InvoiceProduct-IPE-Common' with product 'IE-IPE' name, 'IE-IPE' sku, 'IE-IPE' barcode, 'liter' units
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'InvoiceProduct-IPE-Common' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'productAmountView' element of invoice product with 'IE-IPE' sku to edit
And the user inputs '1' in the invoice 'inline quantity' field
And the user clicks OK and accepts changes
Then the user sees no error messages
When the user clicks finish edit link and ends the invoice edition
And the user logs out

Scenario: Invoice product price validation commma

Given there is the invoice 'InvoiceProduct-IPE-Common' with product 'IE-IPE' name, 'IE-IPE' sku, 'IE-IPE' barcode, 'liter' units
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'InvoiceProduct-IPE-Common' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'productPriceView' element of invoice product with 'IE-IPE' sku to edit
And the user inputs ',78' in the invoice 'inline price' field
And the user clicks OK and accepts changes
Then the user sees no error messages
When the user clicks finish edit link and ends the invoice edition
And the user logs out

Scenario: Invoice product price validation dott

Given there is the invoice 'InvoiceProduct-IPE-Common' with product 'IE-IPE' name, 'IE-IPE' sku, 'IE-IPE' barcode, 'liter' units
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'InvoiceProduct-IPE-Common' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'productPriceView' element of invoice product with 'IE-IPE' sku to edit
And the user inputs '.78' in the invoice 'inline price' field
And the user clicks OK and accepts changes
Then the user sees no error messages
When the user clicks finish edit link and ends the invoice edition
And the user logs out

Scenario: Invoice product price validation comma

Given there is the invoice 'InvoiceProduct-IPE-Common' with product 'IE-IPE' name, 'IE-IPE' sku, 'IE-IPE' barcode, 'liter' units
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'InvoiceProduct-IPE-Common' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'productPriceView' element of invoice product with 'IE-IPE' sku to edit
And the user inputs '123,25' in the invoice 'inline price' field
And the user clicks OK and accepts changes
Then the user sees no error messages
When the user clicks finish edit link and ends the invoice edition
And the user logs out

Scenario: Invoice product price validation dot

Given there is the invoice 'InvoiceProduct-IPE-Common' with product 'IE-IPE' name, 'IE-IPE' sku, 'IE-IPE' barcode, 'liter' units
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'InvoiceProduct-IPE-Common' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'productPriceView' element of invoice product with 'IE-IPE' sku to edit
And the user inputs '12.56' in the invoice 'inline price' field
And the user clicks OK and accepts changes
Then the user sees no error messages
When the user clicks finish edit link and ends the invoice edition
And the user logs out

Scenario: Invoice product price validation one digit

Given there is the invoice 'InvoiceProduct-IPE-Common' with product 'IE-IPE' name, 'IE-IPE' sku, 'IE-IPE' barcode, 'liter' units
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'InvoiceProduct-IPE-Common' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'productPriceView' element of invoice product with 'IE-IPE' sku to edit
And the user inputs '2' in the invoice 'inline price' field
And the user clicks OK and accepts changes
Then the user sees no error messages
When the user clicks finish edit link and ends the invoice edition
And the user logs out

Scenario: Invoice product price validation two digits

Given there is the invoice 'InvoiceProduct-IPE-Common' with product 'IE-IPE' name, 'IE-IPE' sku, 'IE-IPE' barcode, 'liter' units
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'InvoiceProduct-IPE-Common' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'productPriceView' element of invoice product with 'IE-IPE' sku to edit
And the user inputs '99' in the invoice 'inline price' field
And the user clicks OK and accepts changes
Then the user sees no error messages
When the user clicks finish edit link and ends the invoice edition
And the user logs out

Scenario: Invoice product price validation three digits

Given there is the invoice 'InvoiceProduct-IPE-Common' with product 'IE-IPE' name, 'IE-IPE' sku, 'IE-IPE' barcode, 'liter' units
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'InvoiceProduct-IPE-Common' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'productPriceView' element of invoice product with 'IE-IPE' sku to edit
And the user inputs '12,123' in the invoice 'inline price' field
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Цена не должна содержать больше 2 цифр после запятой. |
When the user clicks Cancel and discard changes
And the user clicks finish edit link and ends the invoice edition
And the user logs out

Scenario: Invoice product price validation sub zero

Given there is the invoice 'InvoiceProduct-IPE-Common' with product 'IE-IPE' name, 'IE-IPE' sku, 'IE-IPE' barcode, 'liter' units
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'InvoiceProduct-IPE-Common' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'productPriceView' element of invoice product with 'IE-IPE' sku to edit
And the user inputs '-1' in the invoice 'inline price' field
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю. |
When the user clicks Cancel and discard changes
And the user clicks finish edit link and ends the invoice edition
And the user logs out

Scenario: Invoice product price validation zero

Given there is the invoice 'InvoiceProduct-IPE-Common' with product 'IE-IPE' name, 'IE-IPE' sku, 'IE-IPE' barcode, 'liter' units
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'InvoiceProduct-IPE-Common' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'productPriceView' element of invoice product with 'IE-IPE' sku to edit
And the user inputs '0' in the invoice 'inline price' field
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю. |
When the user clicks Cancel and discard changes
And the user clicks finish edit link and ends the invoice edition
And the user logs out

Scenario: Invoice product price validation String en small register

Given there is the invoice 'InvoiceProduct-IPE-Common' with product 'IE-IPE' name, 'IE-IPE' sku, 'IE-IPE' barcode, 'liter' units
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'InvoiceProduct-IPE-Common' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'productPriceView' element of invoice product with 'IE-IPE' sku to edit
And the user inputs 'harry' in the invoice 'inline price' field
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю. |
When the user clicks Cancel and discard changes
And the user clicks finish edit link and ends the invoice edition
And the user logs out

Scenario: Invoice product price validation String en big register

Given there is the invoice 'InvoiceProduct-IPE-Common' with product 'IE-IPE' name, 'IE-IPE' sku, 'IE-IPE' barcode, 'liter' units
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'InvoiceProduct-IPE-Common' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'productPriceView' element of invoice product with 'IE-IPE' sku to edit
And the user inputs 'HARRY' in the invoice 'inline price' field
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю. |
When the user clicks Cancel and discard changes
And the user clicks finish edit link and ends the invoice edition
And the user logs out

Scenario: Invoice product price validation String rus small register

Given there is the invoice 'InvoiceProduct-IPE-Common' with product 'IE-IPE' name, 'IE-IPE' sku, 'IE-IPE' barcode, 'liter' units
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'InvoiceProduct-IPE-Common' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'productPriceView' element of invoice product with 'IE-IPE' sku to edit
And the user inputs 'цена' in the invoice 'inline price' field
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю. |
When the user clicks Cancel and discard changes
And the user clicks finish edit link and ends the invoice edition
And the user logs out

Scenario: Invoice product price validation String rus big register

Given there is the invoice 'InvoiceProduct-IPE-Common' with product 'IE-IPE' name, 'IE-IPE' sku, 'IE-IPE' barcode, 'liter' units
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'InvoiceProduct-IPE-Common' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'productPriceView' element of invoice product with 'IE-IPE' sku to edit
And the user inputs 'ЦЕНА' in the invoice 'inline price' field
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю. |
When the user clicks Cancel and discard changes
And the user clicks finish edit link and ends the invoice edition
And the user logs out

Scenario: Invoice product price validation symbols

Given there is the invoice 'InvoiceProduct-IPE-Common' with product 'IE-IPE' name, 'IE-IPE' sku, 'IE-IPE' barcode, 'liter' units
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'InvoiceProduct-IPE-Common' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'productPriceView' element of invoice product with 'IE-IPE' sku to edit
And the user inputs '"#$#$#' in the invoice 'inline price' field
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю. |
When the user clicks Cancel and discard changes
And the user clicks finish edit link and ends the invoice edition
And the user logs out

Scenario: Invoice product price validation length good

Given there is the invoice 'InvoiceProduct-IPE-Common' with product 'IE-IPE' name, 'IE-IPE' sku, 'IE-IPE' barcode, 'liter' units
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'InvoiceProduct-IPE-Common' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'productPriceView' element of invoice product with 'IE-IPE' sku to edit
And the user inputs '10000000' in the invoice 'inline price' field
And the user clicks OK and accepts changes
Then the user sees no error messages
When the user clicks finish edit link and ends the invoice edition
And the user logs out

Scenario: Invoice product price validation length negative

Given there is the invoice 'InvoiceProduct-IPE-Common' with product 'IE-IPE' name, 'IE-IPE' sku, 'IE-IPE' barcode, 'liter' units
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'InvoiceProduct-IPE-Common' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'productPriceView' element of invoice product with 'IE-IPE' sku to edit
And the user inputs '10000001' in the invoice 'inline price' field
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Цена не должна быть больше 10000000 |
When the user clicks Cancel and discard changes
And the user clicks finish edit link and ends the invoice edition
And the user logs out



