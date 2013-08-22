Meta:
@sprint 6
@us 10

Scenario: Invoice edition - Invoice sku validation is required

Given there is the invoice with 'Invoice-IE-ISVIR' sku
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'Invoice-IE-ISVIR' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'sku' element to edit it
And the user inputs '' in the invoice 'inline sku' field
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Заполните это поле |
When the user clicks Cancel and discard changes
And the user clicks finish edit link and ends the invoice edition

Scenario: Invoice edition - Invoice sku validation good

Given there is the invoice with 'Invoice-IE-ISVG' sku
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'Invoice-IE-ISVG' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'sku' element to edit it
And the user generates charData with '100' number in the 'inline sku' invoice field
Then the user checks 'inline sku' invoice field contains only '100' symbols
When the user clicks OK and accepts changes
Then the user sees no error messages

Scenario: Invoice edition - Invoice sku negative length validation

Given there is the invoice with 'Invoice-IE-ISVIR' sku
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'Invoice-IE-ISVIR' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'sku' element to edit it
And the user generates charData with '101' number in the 'inline sku' invoice field
Then the user checks 'inline sku' invoice field contains only '101' symbols
When the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Не более 100 символов |
When the user clicks Cancel and discard changes
And the user clicks finish edit link and ends the invoice edition

Scenario: Invoice edition - Invoice Supplier validation is required

Given there is the invoice with 'Invoice-IE-ISVIR' sku
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'Invoice-IE-ISVIR' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'supplier' element to edit it
And the user inputs '' in the invoice 'inline supplier' field
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Заполните это поле |
When the user clicks Cancel and discard changes
And the user clicks finish edit link and ends the invoice edition

Scenario: Invoice edition - Invoice Supplier validation good

Given there is the invoice with 'Invoice-IE-ISVIR' sku
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'Invoice-IE-ISVIR' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'supplier' element to edit it
And the user generates charData with '300' number in the 'inline supplier' invoice field
Then the user checks 'inline supplier' invoice field contains only '300' symbols
When the user clicks OK and accepts changes
Then the user sees no error messages

Scenario: Invoice edition - Invoice Supplier negative length validation

Given there is the invoice with 'Invoice-IE-ISVIR' sku
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'Invoice-IE-ISVIR' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'supplier' element to edit it
And the user generates charData with '301' number in the 'inline supplier' invoice field
Then the user checks 'inline supplier' invoice field contains only '301' symbols
When the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Не более 300 символов |
When the user clicks Cancel and discard changes
And the user clicks finish edit link and ends the invoice edition

Scenario: Invoice edition - Invoice acceptanceDate validation is required

Given there is the invoice with 'Invoice-IE-ISVIR' sku
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'Invoice-IE-ISVIR' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'acceptanceDate' element to edit it
And the user inputs '!' in the invoice 'inline acceptanceDate' field
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Заполните это поле |
When the user clicks Cancel and discard changes
And the user clicks finish edit link and ends the invoice edition

Scenario: Invoice edition - Invoice acceptanceDate validation good manual

Given there is the invoice with 'Invoice-IE-ISVIR' sku
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'Invoice-IE-ISVIR' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'acceptanceDate' element to edit it
And the user inputs '!03.12.2012 10:45' in the invoice 'inline acceptanceDate' field
When the user clicks OK and accepts changes
Then the user sees no error messages
When the user clicks finish edit link and ends the invoice edition
And the user logs out

Scenario: Invoice edition - Invoice acceptanceDate validation manual negative1 numbers

Given there is the invoice with 'Invoice-IE-ISVIR' sku
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'Invoice-IE-ISVIR' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'acceptanceDate' element to edit it
And the user inputs '!123454567890' in the invoice 'inline acceptanceDate' field
And the user clicks OK and accepts changes
Then the user sees no error messages
When the user clicks finish edit link and ends the invoice edition
And the user logs out

Scenario: Invoice edition - Invoice acceptanceDate validation manual negative1 numbers 2

Given there is the invoice with 'Invoice-IE-ISVIR' sku
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'Invoice-IE-ISVIR' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'acceptanceDate' element to edit it
And the user inputs '!12345456789' in the invoice 'inline acceptanceDate' field
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Заполните это поле |
When the user clicks Cancel and discard changes
And the user clicks finish edit link and ends the invoice edition

Scenario: Invoice edition - Invoice acceptanceDate validation manual negative2 eng symbols

Given there is the invoice with 'Invoice-IE-ISVIR' sku
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'Invoice-IE-ISVIR' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'acceptanceDate' element to edit it
And the user inputs '!HAasdfsfsfsf' in the invoice 'inline acceptanceDate' field
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Заполните это поле |
When the user clicks Cancel and discard changes
And the user clicks finish edit link and ends the invoice edition

Scenario: Invoice edition - Invoice acceptanceDate validation manual negative3 rus symbols

Given there is the invoice with 'Invoice-IE-ISVIR' sku
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'Invoice-IE-ISVIR' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'acceptanceDate' element to edit it
And the user inputs '!Русский набор' in the invoice 'inline acceptanceDate' field
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Заполните это поле |
When the user clicks Cancel and discard changes
And the user clicks finish edit link and ends the invoice edition

Scenario: Invoice edition - Invoice acceptanceDate validation manual negative symbols

Given there is the invoice with 'Invoice-IE-ISVIR' sku
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'Invoice-IE-ISVIR' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'acceptanceDate' element to edit it
And the user inputs '!"№;%:?*()_+' in the invoice 'inline acceptanceDate' field
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Заполните это поле |
When the user clicks Cancel and discard changes
And the user clicks finish edit link and ends the invoice edition

Scenario: Invoice edition - Invoice acceptanceDate validation manual negative symbols mix

Given there is the invoice with 'Invoice-IE-ISVIR' sku
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'Invoice-IE-ISVIR' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'acceptanceDate' element to edit it
And the user inputs '!"56gfЛВ' in the invoice 'inline acceptanceDate' field
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Заполните это поле |
When the user clicks Cancel and discard changes
And the user clicks finish edit link and ends the invoice edition

Scenario: Invoice edition - Invoice acceptanceDate validation through datepicker good

Given there is the invoice with 'Invoice-IE-ISVIR' sku
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'Invoice-IE-ISVIR' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'acceptanceDate' element to edit it
And the user inputs 'todayDateAndTime' in the invoice 'inline acceptanceDate' field
And the user clicks OK and accepts changes
Then the user sees no error messages
When the user clicks finish edit link and ends the invoice edition

Scenario: Invoice edition - Invoice acceptanceDate validation through datepicker negative1

Given there is the invoice with 'Invoice-IE-ISVIR' sku
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'Invoice-IE-ISVIR' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'acceptanceDate' element to edit it
And the user inputs '27.03.2013 9999:9999' in the invoice 'inline acceptanceDate' field
And the user clicks OK and accepts changes
Then the user sees no error messages
When the user clicks finish edit link and ends the invoice edition
And the user logs out

Scenario: Invoice edition - Invoice acceptanceDate validation through datepicker negative2

Given there is the invoice with 'Invoice-IE-ISVIR' sku
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'Invoice-IE-ISVIR' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'acceptanceDate' element to edit it
And the user inputs '27.03.2013 1155:222255' in the invoice 'inline acceptanceDate' field
And the user clicks OK and accepts changes
Then the user sees no error messages
When the user clicks finish edit link and ends the invoice edition
And the user logs out

Scenario: Invoice edition - Invoice accepter validation is required

Given there is the invoice with 'Invoice-IE-ISVIR' sku
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'Invoice-IE-ISVIR' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'accepter' element to edit it
And the user inputs '' in the invoice 'inline accepter' field
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Заполните это поле |
When the user clicks Cancel and discard changes
And the user clicks finish edit link and ends the invoice edition

Scenario: Invoice edition - Invoice accepter validation good

Given there is the invoice with 'Invoice-IE-ISVIR' sku
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'Invoice-IE-ISVIR' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'accepter' element to edit it
And the user generates charData with '100' number in the 'inline accepter' invoice field
Then the user checks 'inline accepter' invoice field contains only '100' symbols
When the user clicks OK and accepts changes
Then the user sees no error messages

Scenario: Invoice edition - Invoice accepter negative length validation

Given there is the invoice with 'Invoice-IE-ISVIR' sku
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'Invoice-IE-ISVIR' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'accepter' element to edit it
And the user generates charData with '101' number in the 'inline accepter' invoice field
Then the user checks 'inline accepter' invoice field contains only '101' symbols
When the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Не более 100 символов |
When the user clicks Cancel and discard changes
And the user clicks finish edit link and ends the invoice edition

Scenario: Invoice edition - Invoice legalEntity validation is required

Given there is the invoice with 'Invoice-IE-ISVIR' sku
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'Invoice-IE-ISVIR' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'legalEntity' element to edit it
And the user inputs '' in the invoice 'inline legalEntity' field
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Заполните это поле |
When the user clicks Cancel and discard changes
And the user clicks finish edit link and ends the invoice edition

Scenario: Invoice edition - Invoice legalEntity validation good

Given there is the invoice with 'Invoice-IE-ISVIR' sku
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'Invoice-IE-ISVIR' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'legalEntity' element to edit it
And the user generates charData with '300' number in the 'inline legalEntity' invoice field
Then the user checks 'inline legalEntity' invoice field contains only '300' symbols
When the user clicks OK and accepts changes
Then the user sees no error messages

Scenario: Invoice edition - Invoice legalEntity negative length validation

Given there is the invoice with 'Invoice-IE-ISVIR' sku
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'Invoice-IE-ISVIR' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'legalEntity' element to edit it
And the user generates charData with '301' number in the 'inline legalEntity' invoice field
Then the user checks 'inline legalEntity' invoice field contains only '301' symbols
When the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Не более 300 символов |
When the user clicks Cancel and discard changes
And the user clicks finish edit link and ends the invoice edition

Scenario: Invoice edition - Invoice supplierInvoiceSku validation good

Given there is the invoice with 'Invoice-IE-ISVIR' sku
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'Invoice-IE-ISVIR' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'supplierInvoiceSku' element to edit it
And the user generates charData with '100' number in the 'inline supplierInvoiceSku' invoice field
Then the user checks 'inline supplierInvoiceSku' invoice field contains only '100' symbols
When the user clicks OK and accepts changes
Then the user sees no error messages

Scenario: Invoice edition - Invoice supplierInvoiceSku negative length validation

Given there is the invoice with 'Invoice-IE-ISVIR' sku
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'Invoice-IE-ISVIR' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'supplierInvoiceSku' element to edit it
And the user generates charData with '101' number in the 'inline supplierInvoiceSku' invoice field
Then the user checks 'inline supplierInvoiceSku' invoice field contains only '101' symbols
When the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Не более 100 символов |
When the user clicks Cancel and discard changes
And the user clicks finish edit link and ends the invoice edition

Scenario: Invoice edition - Invoice supplierInvoiceDate validation good manual

Given there is the invoice with 'Invoice-IE-ISVIR' sku
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'Invoice-IE-ISVIR' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'supplierInvoiceDate' element to edit it
And the user inputs '!03.03.2012' in the invoice 'inline supplierInvoiceDate' field
When the user clicks OK and accepts changes
Then the user sees no error messages
When the user clicks finish edit link and ends the invoice edition
And the user logs out

Scenario: Invoice edition - Invoice supplierInvoiceDate validation manual negative1 numbers

Given there is the invoice with 'Invoice-IE-ISVIR' sku
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'Invoice-IE-ISVIR' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'supplierInvoiceDate' element to edit it
And the user inputs '!12345456789' in the invoice 'inline supplierInvoiceDate' field
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Дата накладной не должна быть старше даты приемки |
When the user clicks Cancel and discard changes
And the user clicks finish edit link and ends the invoice edition

Scenario: Invoice edition - Invoice supplierInvoiceDate validation manual negative2 eng symbols

Given there is the invoice with 'Invoice-IE-ISVIR' sku
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'Invoice-IE-ISVIR' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'supplierInvoiceDate' element to edit it
And the user inputs '!HAasdfsfsfsf' in the invoice 'inline supplierInvoiceDate' field
And the user clicks OK and accepts changes
Then the user sees no error messages
When the user clicks finish edit link and ends the invoice edition
And the user logs out

Scenario: Invoice edition - Invoice supplierInvoiceDate validation manual negative3 rus symbols

Given there is the invoice with 'Invoice-IE-ISVIR' sku
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'Invoice-IE-ISVIR' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'supplierInvoiceDate' element to edit it
And the user inputs '!Русский набор' in the invoice 'inline supplierInvoiceDate' field
And the user clicks OK and accepts changes
Then the user sees no error messages
When the user clicks finish edit link and ends the invoice edition
And the user logs out

Scenario: Invoice edition - Invoice supplierInvoiceDate validation manual negative symbols

Given there is the invoice with 'Invoice-IE-ISVIR' sku
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'Invoice-IE-ISVIR' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'supplierInvoiceDate' element to edit it
And the user inputs '!"№;%:?*()_+' in the invoice 'inline supplierInvoiceDate' field
And the user clicks OK and accepts changes
Then the user sees no error messages
When the user clicks finish edit link and ends the invoice edition
And the user logs out

Scenario: Invoice edition - Invoice supplierInvoiceDate validation manual negative symbols mix

Given there is the invoice with 'Invoice-IE-ISVIR' sku
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'Invoice-IE-ISVIR' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'supplierInvoiceDate' element to edit it
And the user inputs '!"56gfЛВ' in the invoice 'inline supplierInvoiceDate' field
And the user clicks OK and accepts changes
Then the user sees no error messages
When the user clicks finish edit link and ends the invoice edition
And the user logs out

Scenario: Invoice edition - Invoice supplierInvoiceDate validation through datepicker good

Given there is the invoice with 'Invoice-IE-ISVIR' sku
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'Invoice-IE-ISVIR' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'supplierInvoiceDate' element to edit it
And the user inputs '01.01.2012' in the invoice 'inline supplierInvoiceDate' field
And the user clicks OK and accepts changes
Then the user sees no error messages
When the user clicks finish edit link and ends the invoice edition
And the user logs out

Scenario: Invoice edition - Invoice supplier date cantbe older then acceptance date

Given there is the invoice with 'Invoice-IE-ISVIR' sku
And the user logs in as 'departmentManager'
And the user is on the invoice list page
When the user open the invoice card with 'Invoice-IE-ISVIR' sku
And the user clicks edit button and starts invoice edition
And the user clicks on 'acceptanceDate' element to edit it
And the user inputs '!!27.03.2013 10:11' in the invoice 'inline acceptanceDate' field
And the user clicks OK and accepts changes
And the user clicks on 'supplierInvoiceDate' element to edit it
And the user inputs '!28.03.2013' in the invoice 'inline supplierInvoiceDate' field
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Дата накладной не должна быть старше даты приемки |
When the user clicks Cancel and discard changes
And the user clicks finish edit link and ends the invoice edition





