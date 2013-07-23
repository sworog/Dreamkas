Meta:
@sprint 3
@us 4.1

Scenario: Edit product validation - Name field length validation
Given there is created product with sku 'ED-NMLV'
And the user is on the product list page
And the user logs in as 'commercialManager'
When the user open the product card with 'ED-NMLV' sku
And the user clicks the edit button on product card view page
And the user generates charData with '300' number in the 'name' field
And the user inputs 'EPNFLV-879' in 'sku' field
And the user inputs '58967' in 'purchasePrice' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user clicks the create button
Then the user sees no error messages
Given the user is on the product list page
Then the user checks the product with 'EPNFLV-879' sku is present
When the user logs out

Scenario: Edit product validation - Name field length validation negative
Given there is created product with sku 'ED-NMLVN'
And the user is on the product list page
And the user logs in as 'commercialManager'
When the user open the product card with 'ED-NMLVN' sku
And the user clicks the edit button on product card view page
And the user inputs 'EPNFLVN-01' in 'sku' field
And the user inputs '58967' in 'purchasePrice' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user generates charData with '301' number in the 'name' field
Then the user checks 'name' field contains only '301' symbols
When the user clicks the create button
Then the user sees error messages
| error message |
| Не более 300 символов |
When the user logs out

Scenario: Edit product validation - Name field length validation negative 2
Given there is created product with sku 'ED-NMLVN2'
And the user is on the product list page
And the user logs in as 'commercialManager'
When the user open the product card with 'ED-NMLVN2' sku
And the user clicks the edit button on product card view page
And the user inputs 'EPNFLVN-01' in 'sku' field
And the user inputs '58967' in 'purchasePrice' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user generates charData with '356' number in the 'name' field
Then the user checks 'name' field contains only '356' symbols
When the user clicks the create button
Then the user sees error messages
| error message |
| Не более 300 символов |
When the user logs out

Scenario: Edit product validation - Name field is required
Given there is created product with sku 'ED-NFIR'
And the user is on the product list page
And the user logs in as 'commercialManager'
When the user open the product card with 'ED-NFIR' sku
And the user clicks the edit button on product card view page
And the user inputs 'EPIFV-01' in 'sku' field
And the user inputs '58967' in 'purchasePrice' field
And the user inputs '' in 'name' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user clicks the create button
Then the user sees error messages
| error message |
| Заполните это поле |
When the user logs out


Scenario: Edit product validation - Barcode field length validation
Given there is created product with sku 'ED-BFLV'
And the user is on the product list page
And the user logs in as 'commercialManager'
When the user open the product card with 'ED-BFLV' sku
And the user clicks the edit button on product card view page
And the user generates charData with '200' number in the 'barcode' field
And the user inputs 'Barcode field length validation' in 'name' field
And the user inputs 'EPFTY6456789' in 'sku' field
And the user inputs '58967' in 'purchasePrice' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user clicks the create button
Then the user sees no error messages
Given the user is on the product list page
Then the user checks the product with 'EPFTY6456789' sku is present
When the user logs out

Scenario: Edit product validation - Barcode field length validation negative
Given there is created product with sku 'ED-BFLVN'
And the user is on the product list page
And the user logs in as 'commercialManager'
When the user open the product card with 'ED-BFLVN' sku
And the user clicks the edit button on product card view page
And the user inputs 'Barcode field length validation' in 'name' field
And the user inputs 'FTY6456789123' in 'sku' field
And the user inputs '58967' in 'purchasePrice' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user generates charData with '201' number in the 'barcode' field
Then the user checks 'barcode' field contains only '201' symbols
When the user clicks the create button
Then the user sees error messages
| error message |
| Не более 200 символов |
When the user logs out


Scenario: Edit product validation - Sku field validation good
Given the user is on the product list page
And the user logs in as 'commercialManager'
When the user creates new product from product list page
And the user inputs 'Sku field validation good' in 'name' field
And the user inputs '1001DS8' in 'sku' field
And the user inputs '58967' in 'purchasePrice' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user clicks the create button
Given there is created product with sku 'ED-SKVG'
And the user is on the product list page
When the user open the product card with 'ED-SKVG' sku
And the user clicks the edit button on product card view page
And the user inputs 'Sku field validation good' in 'name' field
And the user inputs '1001DS8' in 'sku' field
And the user inputs '58967' in 'purchasePrice' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user clicks the create button
Then the user sees error messages
| error message |
| Такой артикул уже есть |
When the user logs out

Scenario: Edit product validation - Sku field negative
Given there is created product with sku 'ED-SFN'
And the user is on the product list page
And the user logs in as 'commercialManager'
When the user open the product card with 'ED-SFN' sku
And the user clicks the edit button on product card view page
And the user generates charData with '101' number in the 'sku' field
Then the user checks 'sku' field contains only '101' symbols
When the user clicks the create button
Then the user sees error messages
| error message |
| Не более 100 символов |
When the user logs out

Scenario: Edit product validation - Sku field is required
Given there is created product with sku 'ED-SFIR'
And the user is on the product list page
And the user logs in as 'commercialManager'
When the user open the product card with 'ED-SFIR' sku
And the user clicks the edit button on product card view page
And the user inputs 'Sku field is required' in 'name' field
And the user inputs '58967' in 'purchasePrice' field
And the user inputs '' in 'sku' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user clicks the create button
Then the user sees error messages
| error message |
| Заполните это поле |
When the user logs out


Scenario: Edit product validation - Vendor,Barcode,VendorCountryInfo fields are not required
Given there is created product with sku 'ED-VBVCF'
And the user is on the product list page
And the user logs in as 'commercialManager'
When the user open the product card with 'ED-VBVCF' sku
And the user clicks the edit button on product card view page
And the user inputs 'Vendor,Barcode,VendorCountryInfo fields are not required' in 'name' field
And the user inputs 'EPVBVCF678' in 'sku' field
And the user inputs '58967' in 'purchasePrice' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user clicks the create button
Then the user sees no error messages
Given the user is on the product list page
Then the user checks the product with 'EPVBVCF678' sku is present
When the user logs out


Scenario: Edit product validation - Vendor field validation
Given there is created product with sku 'ED-VFV'
And the user is on the product list page
And the user logs in as 'commercialManager'
When the user open the product card with 'ED-VFV' sku
And the user clicks the edit button on product card view page
And the user inputs 'Vendor field validation' in 'name' field
And the user inputs 'EPVFV-01' in 'sku' field
And the user inputs '58967' in 'purchasePrice' field
And the user generates charData with '300' number in the 'vendor' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user clicks the create button
Then the user sees no error messages
Given the user is on the product list page
Then the user checks the product with 'EPVFV-01' sku is present
When the user logs out

Scenario: Edit product validation - Vendor field validation lenght negative
Given there is created product with sku 'ED-VFVLN'
And the user is on the product list page
And the user logs in as 'commercialManager'
When the user open the product card with 'ED-VFVLN' sku
And the user clicks the edit button on product card view page
And the user inputs 'Vendor field validation lenght negative' in 'name' field
And the user inputs 'EPFTY64567891235' in 'sku' field
And the user inputs '58967' in 'purchasePrice' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user generates charData with '301' number in the 'vendor' field
Then the user checks 'vendor' field contains only '301' symbols
When the user clicks the create button
Then the user sees error messages
| error message |
| Не более 300 символов |
When the user logs out


Scenario: Edit product validation - VendorCountry field validation
Given there is created product with sku 'ED-VCFV'
And the user is on the product list page
And the user logs in as 'commercialManager'
When the user open the product card with 'ED-VCFV' sku
And the user clicks the edit button on product card view page
And the user inputs 'VendorCountry field validation' in 'name' field
And the user inputs 'EPVCFV-01' in 'sku' field
And the user inputs '58967' in 'purchasePrice' field
And the user generates charData with '100' number in the 'vendorCountry' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user clicks the create button
Then the user sees no error messages
Given the user is on the product list page
Then the user checks the product with 'EPVCFV-01' sku is present
When the user logs out

Scenario: Edit product validation - VendorCountry field validation lenght negative
Given there is created product with sku 'ED-VCFVN'
And the user is on the product list page
And the user logs in as 'commercialManager'
When the user open the product card with 'ED-VCFVN' sku
And the user clicks the edit button on product card view page
And the user inputs 'VendorCountry field validation lenght negative' in 'name' field
And the user inputs 'EPFTY64123' in 'sku' field
And the user inputs '58967' in 'purchasePrice' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user generates charData with '101' number in the 'vendorCountry' field
Then the user checks 'vendorCountry' field contains only '101' symbols
When the user clicks the create button
Then the user sees error messages
| error message |
| Не более 100 символов |
When the user logs out

Scenario: Edit product validation - Info field validation
Given there is created product with sku 'ED-IFV'
And the user is on the product list page
And the user logs in as 'commercialManager'
When the user open the product card with 'ED-IFV' sku
And the user clicks the edit button on product card view page
And the user inputs 'Info field validation' in 'name' field
And the user inputs 'EPIFV-01' in 'sku' field
And the user inputs '58967' in 'purchasePrice' field
And the user generates charData with '2000' number in the 'info' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user clicks the create button
Then the user sees no error messages
Given the user is on the product list page
Then the user checks the product with 'EPIFV-01' sku is present
When the user logs out

Scenario: Edit product validation - Info field validation lenght negative
Given there is created product with sku 'ED-IFVN'
And the user is on the product list page
And the user logs in as 'commercialManager'
When the user open the product card with 'ED-IFVN' sku
And the user clicks the edit button on product card view page
And the user inputs 'Info field validation lenght negative' in 'name' field
And the user inputs 'EPFTY64123DS' in 'sku' field
And the user inputs '58967' in 'purchasePrice' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user generates charData with '2001' number in the 'info' field
Then the user checks 'info' field contains only '2001' symbols
When the user clicks the create button
Then the user sees error messages
| error message |
| Не более 2000 символов |
When the user logs out


Scenario: Edit product validation - Purchase price validation String+Symbols+Num
Given there is created product with sku 'ED-PRVSSN'
And the user is on the product list page
And the user logs in as 'commercialManager'
When the user open the product card with 'ED-PRVSSN' sku
And the user clicks the edit button on product card view page
And the user inputs 'EPPPV-06' in 'name' field
And the user inputs 'EPPPV-06' in 'sku' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs '%^#$Fgbdf345)' in 'purchasePrice' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю. |
When the user logs out

Scenario: Edit product validation - Purchase price validation commma
Given there is created product with sku 'ED-PPVC'
And the user is on the product list page
And the user logs in as 'commercialManager'
When the user open the product card with 'ED-PPVC' sku
And the user clicks the edit button on product card view page
And the user inputs 'EPPPV-07' in 'name' field
And the user inputs 'EPPPV-07' in 'sku' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs ',78' in 'purchasePrice' field
And the user clicks the create button
Then the user sees no error messages
Given the user is on the product list page
Then the user checks the product with 'EPPPV-07' sku has 'purchasePrice' equal to '0,78'
When the user logs out

Scenario: Edit product validation - Purchase price validation dott
Given there is created product with sku 'ED-PPVD'
And the user is on the product list page
And the user logs in as 'commercialManager'
When the user open the product card with 'ED-PPVD' sku
And the user clicks the edit button on product card view page
And the user inputs 'EPPPV-08' in 'name' field
And the user inputs 'EPPPV-08' in 'sku' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs ',78' in 'purchasePrice' field
And the user clicks the create button
Then the user sees no error messages
Given the user is on the product list page
Then the user checks the product with 'EPPPV-08' sku has 'purchasePrice' equal to '0,78'
When the user logs out

Scenario: Edit product validation - Purchase price validation comma
Given there is created product with sku 'ED-PPVCC'
And the user is on the product list page
And the user logs in as 'commercialManager'
When the user open the product card with 'ED-PPVCC' sku
And the user clicks the edit button on product card view page
And the user inputs 'purchase price comma' in 'name' field
And the user inputs 'EPJFGE89075' in 'sku' field
And the user inputs '123.25' in 'purchasePrice' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user clicks the create button
Then the user sees no error messages
Given the user is on the product list page
Then the user checks the product with 'EPJFGE89075' sku has 'purchasePrice' equal to '123,25'
When the user logs out

Scenario: Edit product validation - Purchase price validation dot
Given there is created product with sku 'ED-PPVDD'
And the user is on the product list page
And the user logs in as 'commercialManager'
When the user open the product card with 'ED-PPVDD' sku
And the user clicks the edit button on product card view page
And the user inputs 'purchase price dot' in 'name' field
And the user inputs 'EPJFGE89078' in 'sku' field
And the user inputs '125,26' in 'purchasePrice' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user clicks the create button
Then the user sees no error messages
Given the user is on the product list page
Then the user checks the product with 'EPJFGE89078' sku has 'purchasePrice' equal to '125,26'
When the user logs out

Scenario: Edit product validation - Purchase price validation one digit
Given there is created product with sku 'ED-PPCOD'
And the user is on the product list page
And the user logs in as 'commercialManager'
When the user open the product card with 'ED-PPCOD' sku
And the user clicks the edit button on product card view page
And the user inputs 'purchase price one digit' in 'name' field
And the user inputs 'EPFTY64' in 'sku' field
And the user inputs '789,6' in 'purchasePrice' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user clicks the create button
Then the user sees no error messages
Given the user is on the product list page
Then the user checks the product with 'EPFTY64' sku has 'purchasePrice' equal to '789,6'
When the user logs out

Scenario: Edit product validation - Purchase price validation two digits
Given there is created product with sku 'ED-PPCTD'
And the user is on the product list page
And the user logs in as 'commercialManager'
When the user open the product card with 'ED-PPCTD' sku
And the user clicks the edit button on product card view page
And the user inputs 'purchase price two digits' in 'name' field
And the user inputs 'EPFTY645' in 'sku' field
And the user inputs '739,67' in 'purchasePrice' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user clicks the create button
Then the user sees no error messages
Given the user is on the product list page
Then the user checks the product with 'EPFTY645' sku has 'purchasePrice' equal to '739,67'
When the user logs out

Scenario: Edit product validation - Purchase price validation three digits
Given there is created product with sku 'ED-PPC3D'
And the user is on the product list page
And the user logs in as 'commercialManager'
When the user open the product card with 'ED-PPC3D' sku
And the user clicks the edit button on product card view page
And the user inputs 'purchase price three digits' in 'name' field
And the user inputs 'EPFTY6456' in 'sku' field
And the user inputs '739,678' in 'purchasePrice' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user clicks the create button
Then the user sees error messages
| error message |
| Цена не должна содержать больше 2 цифр после запятой. |
When the user logs out

Scenario: Edit product validation - Purchase price field is required
Given there is created product with sku 'ED-PPFIR'
And the user is on the product list page
And the user logs in as 'commercialManager'
When the user open the product card with 'ED-PPFIR' sku
And the user clicks the edit button on product card view page
And the user inputs 'Unit fiels is required' in 'name' field
And the user inputs '' in 'purchasePrice' field
And the user inputs 'EPIFV-01' in 'sku' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user clicks the create button
Then the user sees error messages
| error message |
| Заполните это поле |
When the user logs out

Scenario: Edit product validation - Purchase price validation sub zero
Given there is created product with sku 'ED-PPVSB'
And the user is on the product list page
And the user logs in as 'commercialManager'
When the user open the product card with 'ED-PPVSB' sku
And the user clicks the edit button on product card view page
And the user inputs 'PPV-01' in 'name' field
And the user inputs 'EPPPV-01' in 'sku' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs '-152' in 'purchasePrice' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю. |
When the user logs out

Scenario: Edit product validation - Purhase prise validation zero
Given there is created product with sku 'ED-PPVZ'
And the user is on the product list page
And the user logs in as 'commercialManager'
When the user open the product card with 'ED-PPVZ' sku
And the user clicks the edit button on product card view page
And the user inputs 'EPPPV-02' in 'name' field
And the user inputs 'EPPPV-02' in 'sku' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs '0' in 'purchasePrice' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю. |
When the user logs out

Scenario: Edit product validation - Purchase price validation String en
Given there is created product with sku 'ED-PPVSR'
And the user is on the product list page
And the user logs in as 'commercialManager'
When the user open the product card with 'ED-PPVSR' sku
And the user clicks the edit button on product card view page
And the user inputs 'PPV-03' in 'name' field
And the user inputs 'EPPPV-03' in 'sku' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs 'Big price' in 'purchasePrice' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю. |
When the user logs out

Scenario: Edit product validation - Purchase price validation String rus
Given there is created product with sku 'EDD-PPVSR'
And the user is on the product list page
And the user logs in as 'commercialManager'
When the user open the product card with 'EDD-PPVSR' sku
And the user clicks the edit button on product card view page
And the user inputs 'EPPPV-04' in 'name' field
And the user inputs 'EPPPV-04' in 'sku' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs 'Большая цена' in 'purchasePrice' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю. |
When the user logs out

Scenario: Edit product validation - Purchase price validation symbols
Given there is created product with sku 'ED-PPCS'
And the user is on the product list page
And the user logs in as 'commercialManager'
When the user open the product card with 'ED-PPCS' sku
And the user clicks the edit button on product card view page
And the user inputs 'EPPPV-05' in 'name' field
And the user inputs 'EPPPV-05' in 'sku' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs '!@#$%^&*()' in 'purchasePrice' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Цена не должна быть меньше или равна нулю. |
When the user logs out

Scenario: Edit product validation - Purchase price validation length good
Given there is created product with sku 'ED-PPVLG'
And the user is on the product list page
And the user logs in as 'commercialManager'
When the user open the product card with 'ED-PPVLG' sku
And the user clicks the edit button on product card view page
And the user inputs 'PPV-090' in 'name' field
And the user inputs 'EPPPV-090' in 'sku' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs '10000000' in 'purchasePrice' field
And the user clicks the create button
Given the user is on the product list page
Then the user checks the product with 'EPPPV-090' sku has 'purchasePrice' equal to '10000000'
When the user logs out

Scenario: Edit product validation - Purchase price validation length negative
Given there is created product with sku 'ED-PPVLN'
And the user is on the product list page
And the user logs in as 'commercialManager'
When the user open the product card with 'ED-PPVLN' sku
And the user clicks the edit button on product card view page
And the user inputs 'PPV-0941' in 'name' field
And the user inputs 'EPPPV-0941' in 'sku' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs '10000001' in 'purchasePrice' field
And the user clicks the create button
Then the user sees error messages
| error message |
| Цена не должна быть больше 10000000 |
When the user logs out