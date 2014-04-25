14 Списания

Meta:
@sprint_10
@us_14

Scenario: Write off creation

Given there is the product with 'WriteOff-ProductName' name, 'WriteOff-ProductBarCode' barcode, 'liter' units, '15' purchasePrice
And the user opens the write off create page
And the user opens amount list page
And the user logs in as 'departmentManager'
Then the user checks the product with 'WriteOff-ProductSku' sku has 'amounts amount' element equal to '0' on amounts page
Given the user opens the write off create page
When the user inputs 'WriteOff Number-1' in the 'writeOff number' field on the write off page
And the user inputs '24.10.2012' in the 'writeOff date' field on the write off page
And the user continues the write off creation
And the user inputs 'WriteOff-ProductName' in the 'writeOff product name autocomplete' field on the write off page
And the user inputs '10' in the 'writeOff product quantity' field on the write off page
And the user inputs 'Причина сдачи: Истек срок хранения' in the 'writeOff cause' field on the write off page
And the user presses the add product button and add the product to write off
And the user clicks finish edit button and ends the write off edition
Then the user checks write off elements values
| elementName | value |
| writeOff number review | WriteOff Number-1 |
| writeOff date review | 24.10.2012 |
And the user checks the write off product with 'WriteOff-ProductSku' sku is present
And the user checks the product with 'WriteOff-ProductSku' sku has elements on the write off page
| elementName | value |
| writeOff product name review | WriteOff-ProductName |
| writeOff product sku review | WriteOff-ProductSku |
| writeOff product barCode review | WriteOff-ProductBarCode |
| writeOff product quantity review | 10 |
| writeOff product price review | 15 |
| writeOff cause review | Причина сдачи: Истек срок хранения |
Then the user checks write off elements values
| elementName | value |
| totalProducts | 1 |
| totalSum | 150 |
Given the user opens amount list page
Then the user checks the product with 'WriteOff-ProductSku' sku has 'amounts amount' element equal to '-10' on amounts page

Scenario: Write Off product name autocomplete

Given there is the product with 'WriteOff-ProductName' name, 'WriteOff-ProductBarCode' barcode, 'liter' units, '15' purchasePrice
And the user opens the write off create page
And the user logs in as 'departmentManager'
When the user inputs 'WriteOff pna' in the 'writeOff number' field on the write off page
And the user inputs 'todayDate' in the 'writeOff date' field on the write off page
And the user continues the write off creation
When the user inputs 'WriteOff-ProductName' in the 'writeOff product name autocomplete' field on the write off page

Scenario: Write Off product sku autocomplete

Given there is the product with 'WriteOff-ProductName' name, 'WriteOff-ProductBarCode' barcode, 'liter' units, '15' purchasePrice
And the user opens the write off create page
And the user logs in as 'departmentManager'
When the user inputs 'WriteOff psa' in the 'writeOff number' field on the write off page
And the user inputs 'todayDate' in the 'writeOff date' field on the write off page
And the user continues the write off creation
When the user inputs 'WriteOff-ProductSku' in the 'writeOff product sku autocomplete' field on the write off page

Scenario: Write Off product barcode autocomplete

Given there is the product with 'WriteOff-ProductName' name, 'WriteOff-ProductBarCode' barcode, 'liter' units, '15' purchasePrice
And the user opens the write off create page
And the user logs in as 'departmentManager'
When the user inputs 'WriteOff pba' in the 'writeOff number' field on the write off page
And the user inputs 'todayDate' in the 'writeOff date' field on the write off page
And the user continues the write off creation
When the user inputs 'WriteOff-ProductBarCode' in the 'writeOff product barCode autocomplete' field on the write off page

Scenario: Write off price is filled by retail price

Given skipped test
Given the user is on the product list page
And the user logs in as 'commercialManager'
When the user creates new product from product list page
And the user inputs 'WriteOff-ProductName-1' in 'name' field
And the user inputs '112' in 'purchasePrice' field
And the user selects 'unit' in 'unit' dropdown
And the user selects '10' in 'vat' dropdown
And the user inputs 'Retail price - WO-PCWRPF' in 'sku' field
And the user clicks 'retailPriceHint' to make it avalaible
When the user inputs '140' in 'retailPriceMax' field
And the user inputs '140' in 'retailPriceMin' field
And the user clicks the create button
And the user logs out
Given the user opens the write off create page
And the user logs in as 'departmentManager'
When the user inputs 'WriteOff ifbrp' in the 'writeOff number' field on the write off page
And the user inputs 'todayDate' in the 'writeOff date' field on the write off page
And the user continues the write off creation
When the user inputs 'WriteOff-ProductName-1' in the 'writeOff product name autocomplete' field on the write off page
Then the user checks write off elements values
| elementName | value |
| writeOff product price | 140 |

Scenario: Write off price is filled by purchase price

Given there is the product with 'WriteOff-ProductName' name, 'WriteOff-ProductBarCode' barcode, 'liter' units, '15' purchasePrice
And the user opens the write off create page
And the user logs in as 'departmentManager'
When the user inputs 'WriteOff ifbpp' in the 'writeOff number' field on the write off page
And the user inputs 'todayDate' in the 'writeOff date' field on the write off page
And the user continues the write off creation
When the user inputs 'WriteOff-ProductName' in the 'writeOff product name autocomplete' field on the write off page
Then the user checks write off elements values
| elementName | value |
| writeOff product price | 15 |

Scenario: Write off review kg

Given there is the product with 'WriteOff-ProductName-review1' name, 'WriteOff-ProductBarCode-review1' barcode, 'kg' units, '15' purchasePrice
And the user opens amount list page
And the user logs in as 'departmentManager'
Then the user checks the product with 'WriteOff-ProductSku-review1' sku has 'amounts amount' element equal to '0' on amounts page
Given the user opens the write off create page
When the user inputs 'WriteOff Number-1' in the 'writeOff number' field on the write off page
And the user inputs '24.10.2012' in the 'writeOff date' field on the write off page
And the user continues the write off creation
And the user inputs 'WriteOff-ProductName-review1' in the 'writeOff product name autocomplete' field on the write off page
And the user inputs '10' in the 'writeOff product quantity' field on the write off page
And the user inputs 'Причина сдачи: Истек срок хранения' in the 'writeOff cause' field on the write off page
And the user presses the add product button and add the product to write off
And the user clicks finish edit button and ends the write off edition
Then the user checks write off elements values
| elementName | value |
| writeOff number review | WriteOff Number-1 |
| writeOff date review | 24.10.2012 |
And the user checks the write off product with 'WriteOff-ProductSku-review1' sku is present
And the user checks the product with 'WriteOff-ProductSku-review1' sku has elements on the write off page
| elementName | value |
| writeOff product sku review | WriteOff-ProductSku-review1 |
| writeOff product barCode review | WriteOff-ProductBarCode-review1 |
| writeOff product quantity review | 10 |
| writeOff product price review | 15 |
| writeOff cause review | Причина сдачи: Истек срок хранения |
| writeOff product units | кг |
Then the user checks write off elements values
| elementName | value |
| totalProducts | 1 |
| totalSum | 150 |
Given the user opens amount list page
Then the user checks the product with 'WriteOff-ProductSku-review1' sku has 'amounts amount' element equal to '-10' on amounts page

Scenario: Write off Review liter

Given there is the product with 'WriteOff-ProductName-review2' name, 'WriteOff-ProductBarCode-review2' barcode, 'liter' units, '15' purchasePrice
And the user opens amount list page
And the user logs in as 'departmentManager'
Then the user checks the product with 'WriteOff-ProductSku-review2' sku has 'amounts amount' element equal to '0' on amounts page
Given the user opens the write off create page
When the user inputs 'WriteOff Number-1' in the 'writeOff number' field on the write off page
And the user inputs '24.10.2012' in the 'writeOff date' field on the write off page
And the user continues the write off creation
And the user inputs 'WriteOff-ProductName-review2' in the 'writeOff product name autocomplete' field on the write off page
And the user inputs '10' in the 'writeOff product quantity' field on the write off page
And the user inputs 'Причина сдачи: Истек срок хранения' in the 'writeOff cause' field on the write off page
And the user presses the add product button and add the product to write off
And the user clicks finish edit button and ends the write off edition
Then the user checks write off elements values
| elementName | value |
| writeOff number review | WriteOff Number-1 |
| writeOff date review | 24.10.2012 |
And the user checks the write off product with 'WriteOff-ProductSku-review2' sku is present
And the user checks the product with 'WriteOff-ProductSku-review2' sku has elements on the write off page
| elementName | value |
| writeOff product sku review | WriteOff-ProductSku-review2 |
| writeOff product barCode review | WriteOff-ProductBarCode-review2 |
| writeOff product quantity review | 10 |
| writeOff product price review | 15 |
| writeOff cause review | Причина сдачи: Истек срок хранения |
| writeOff product units | л |
Then the user checks write off elements values
| elementName | value |
| totalProducts | 1 |
| totalSum | 150 |
Given the user opens amount list page
Then the user checks the product with 'WriteOff-ProductSku-review2' sku has 'amounts amount' element equal to '-10' on amounts page

Scenario: Write off Review units

Given there is the product with 'WriteOff-ProductName-review3' name, 'WriteOff-ProductBarCode-review3' barcode, 'unit' units, '15' purchasePrice
And the user opens amount list page
And the user logs in as 'departmentManager'
Then the user checks the product with 'WriteOff-ProductSku-review3' sku has 'amounts amount' element equal to '0' on amounts page
Given the user opens the write off create page
When the user inputs 'WriteOff Number-1' in the 'writeOff number' field on the write off page
And the user inputs '24.10.2012' in the 'writeOff date' field on the write off page
And the user continues the write off creation
And the user inputs 'WriteOff-ProductName-review3' in the 'writeOff product name autocomplete' field on the write off page
And the user inputs '10' in the 'writeOff product quantity' field on the write off page
And the user inputs 'Причина сдачи: Истек срок хранения' in the 'writeOff cause' field on the write off page
And the user presses the add product button and add the product to write off
And the user clicks finish edit button and ends the write off edition
Then the user checks write off elements values
| elementName | value |
| writeOff number review | WriteOff Number-1 |
| writeOff date review | 24.10.2012 |
And the user checks the write off product with 'WriteOff-ProductSku-review3' sku is present
And the user checks the product with 'WriteOff-ProductSku-review3' sku has elements on the write off page
| elementName | value |
| writeOff product sku review | WriteOff-ProductSku-review3 |
| writeOff product barCode review | WriteOff-ProductBarCode-review3 |
| writeOff product quantity review | 10 |
| writeOff product price review | 15 |
| writeOff cause review | Причина сдачи: Истек срок хранения |
| writeOff product units | шт |
Then the user checks write off elements values
| elementName | value |
| totalProducts | 1 |
| totalSum | 150 |
Given the user opens amount list page
Then the user checks the product with 'WriteOff-ProductSku-review3' sku has 'amounts amount' element equal to '-10' on amounts page

Scenario: Review 3 different products in one write off

Given there is the product with 'WriteOff-ProductName-review1' name, 'WriteOff-ProductBarCode-review1' barcode, 'kg' units, '15' purchasePrice
Given there is the product with 'WriteOff-ProductName-review2' name, 'WriteOff-ProductBarCode-review2' barcode, 'liter' units, '15' purchasePrice
Given there is the product with 'WriteOff-ProductName-review3' name, 'WriteOff-ProductBarCode-review3' barcode, 'unit' units, '15' purchasePrice
And the user opens amount list page
And the user logs in as 'departmentManager'
Then the user checks the product with 'WriteOff-ProductSku-review1' sku has 'amounts amount' element equal to '-10' on amounts page
Then the user checks the product with 'WriteOff-ProductSku-review2' sku has 'amounts amount' element equal to '-10' on amounts page
Then the user checks the product with 'WriteOff-ProductSku-review3' sku has 'amounts amount' element equal to '-10' on amounts page
Given the user opens the write off create page
When the user inputs 'WriteOff Number-1' in the 'writeOff number' field on the write off page
And the user inputs '24.10.2012' in the 'writeOff date' field on the write off page
And the user continues the write off creation
And the user inputs 'WriteOff-ProductName-review1' in the 'writeOff product name autocomplete' field on the write off page
And the user inputs '10' in the 'writeOff product quantity' field on the write off page
And the user inputs 'Причина сдачи: Истек срок хранения' in the 'writeOff cause' field on the write off page
And the user presses the add product button and add the product to write off
When the user inputs 'WriteOff-ProductName-review2' in the 'writeOff product name autocomplete' field on the write off page
And the user inputs '10' in the 'writeOff product quantity' field on the write off page
And the user inputs 'Причина сдачи: Истек срок хранения' in the 'writeOff cause' field on the write off page
And the user presses the add product button and add the product to write off
When the user inputs 'WriteOff-ProductName-review3' in the 'writeOff product name autocomplete' field on the write off page
And the user inputs '10' in the 'writeOff product quantity' field on the write off page
And the user inputs 'Причина сдачи: Истек срок хранения' in the 'writeOff cause' field on the write off page
And the user presses the add product button and add the product to write off
And the user clicks finish edit button and ends the write off edition
Then the user checks write off elements values
| elementName | value |
| writeOff number review | WriteOff Number-1 |
| writeOff date review | 24.10.2012 |
And the user checks the write off product with 'WriteOff-ProductSku-review1' sku is present
And the user checks the write off product with 'WriteOff-ProductSku-review2' sku is present
And the user checks the write off product with 'WriteOff-ProductSku-review3' sku is present
And the user checks the product with 'WriteOff-ProductSku-review1' sku has elements on the write off page
| elementName | value |
| writeOff product sku review | WriteOff-ProductSku-review1 |
| writeOff product barCode review | WriteOff-ProductBarCode-review1 |
| writeOff product quantity review | 10 |
| writeOff product price review | 15 |
| writeOff cause review | Причина сдачи: Истек срок хранения |
| writeOff product units | кг |
And the user checks the product with 'WriteOff-ProductSku-review2' sku has elements on the write off page
| elementName | value |
| writeOff product sku review | WriteOff-ProductSku-review2 |
| writeOff product barCode review | WriteOff-ProductBarCode-review2 |
| writeOff product quantity review | 10 |
| writeOff product price review | 15 |
| writeOff cause review | Причина сдачи: Истек срок хранения |
| writeOff product units | л |
And the user checks the product with 'WriteOff-ProductSku-review3' sku has elements on the write off page
| elementName | value |
| writeOff product sku review | WriteOff-ProductSku-review3 |
| writeOff product barCode review | WriteOff-ProductBarCode-review3 |
| writeOff product quantity review | 10 |
| writeOff product price review | 15 |
| writeOff cause review | Причина сдачи: Истек срок хранения |
| writeOff product units | шт |
Then the user checks write off elements values
| elementName | value |
| totalProducts | 3 |
| totalSum | 450 |
Given the user opens amount list page
Then the user checks the product with 'WriteOff-ProductSku-review1' sku has 'amounts amount' element equal to '-20' on amounts page
Then the user checks the product with 'WriteOff-ProductSku-review2' sku has 'amounts amount' element equal to '-20' on amounts page
Then the user checks the product with 'WriteOff-ProductSku-review3' sku has 'amounts amount' element equal to '-20' on amounts page

Scenario: Write off edition - number

Given navigate to new write off with 'WriteOff-Edit-1' number
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff number review' write off element to edit it
And the user inputs 'WriteOff Number-1 edited' in the 'inline writeOff number' field on the write off page
And the user clicks OK and accepts changes
Then the user checks write off elements values
| elementName | value |
| writeOff number review | WriteOff Number-1 edited |
When the user clicks finish edit button and ends the write off edition
Then the user checks write off elements values
| elementName | value |
| writeOff number review | WriteOff Number-1 edited |

Scenario: Write off edition - date

Given navigate to new write off with 'WriteOff-Edit-2' number
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff date review' write off element to edit it
And the user inputs '24.11.2012' in the 'inline writeOff date' field on the write off page
And the user clicks OK and accepts changes
Then the user checks write off elements values
| elementName | value |
| writeOff date review | 24.11.2012 |
When the user clicks finish edit button and ends the write off edition
Then the user checks write off elements values
| elementName | value |
| writeOff date review | 24.11.2012 |

Scenario: Write off edition - product name

Given there is the product with 'WriteOff-WOE-PN' name, 'WriteOff-WOE-PN' barcode, 'liter' units, '15' purchasePrice
And there is the write off with 'WriteOff-Edit-productName' number with product 'WriteOff-ProductName-autocomplete' with quantity '10', price '15' and cause 'Причина сдачи: Истек срок хранения'
And the user opens amount list page
And the user logs in as 'departmentManager'
Then the user checks the product with 'WriteOff-WOE-PN' sku has 'amounts amount' element equal to '0' on amounts page
And the user checks the product with 'WriteOff-ProductName-autocomplete' sku has 'amounts amount' element equal to '-10' on amounts page
Given the user navigates to the write off with number 'WriteOff-Edit-productName'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff product name review' write off element to edit it
And the user inputs 'WriteOff-WOE-PN' in the 'inline writeOff product name autocomplete' field on the write off page
And the user clicks OK and accepts changes
Then the user checks the product with 'WriteOff-WOE-PN' sku has elements on the write off page
| elementName | value |
| writeOff product name review | WriteOff-WOE-PN |
When the user clicks finish edit button and ends the write off edition
Then the user checks the product with 'WriteOff-WOE-PN' sku has elements on the write off page
| elementName | value |
| writeOff product name review | WriteOff-WOE-PN |
Given the user opens amount list page
Then the user checks the product with 'WriteOff-WOE-PN' sku has 'amounts amount' element equal to '-10' on amounts page
And the user checks the product with 'WriteOff-ProductName-autocomplete' sku has 'amounts amount' element equal to '0' on amounts page

Scenario: Write off edition - product sku

Given there is the product with 'WriteOff-WOE-PS' name, 'WriteOff-WOE-PS' barcode, 'liter' units, '15' purchasePrice
And there is the write off with 'WriteOff-Edit-productSku' number with product 'WriteOff-ProductSku-autocomplete' with quantity '10', price '15' and cause 'Причина сдачи: Истек срок хранения'
And the user opens amount list page
And the user logs in as 'departmentManager'
Then the user checks the product with 'WriteOff-WOE-PS' sku has 'amounts amount' element equal to '0' on amounts page
And the user checks the product with 'WriteOff-ProductSku-autocomplete' sku has 'amounts amount' element equal to '-10' on amounts page
Given the user navigates to the write off with number 'WriteOff-Edit-productSku'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff product sku review' write off element to edit it
And the user inputs 'WriteOff-WOE-PS' in the 'inline writeOff product sku autocomplete' field on the write off page
And the user clicks OK and accepts changes
Then the user checks the product with 'WriteOff-WOE-PS' sku has elements on the write off page
| elementName | value |
| writeOff product sku review | WriteOff-WOE-PS |
When the user clicks finish edit button and ends the write off edition
Then the user checks the product with 'WriteOff-WOE-PS' sku has elements on the write off page
| elementName | value |
| writeOff product sku review | WriteOff-WOE-PS |
Given the user opens amount list page
Then the user checks the product with 'WriteOff-WOE-PS' sku has 'amounts amount' element equal to '-10' on amounts page
And the user checks the product with 'WriteOff-ProductSku-autocomplete' sku has 'amounts amount' element equal to '0' on amounts page

Scenario: Write off edition - product barcode

Given there is the product with 'WriteOff-WOE-Pb' name, 'WriteOff-WOE-Pb' barcode, 'liter' units, '15' purchasePrice
And there is the write off with 'WriteOff-Edit-productBarCode' number with product 'WriteOff-ProductBarCode-autocomplete' with quantity '10', price '15' and cause 'Причина сдачи: Истек срок хранения'
And the user opens amount list page
And the user logs in as 'departmentManager'
Then the user checks the product with 'WriteOff-WOE-Pb' sku has 'amounts amount' element equal to '0' on amounts page
And the user checks the product with 'WriteOff-ProductBarCode-autocomplete' sku has 'amounts amount' element equal to '-10' on amounts page
Given the user navigates to the write off with number 'WriteOff-Edit-productBarCode'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff product barCode review' write off element to edit it
And the user inputs 'WriteOff-WOE-Pb' in the 'inline writeOff product barCode autocomplete' field on the write off page
And the user clicks OK and accepts changes
Then the user checks the product with 'WriteOff-WOE-Pb' sku has elements on the write off page
| elementName | value |
| writeOff product barCode review | WriteOff-WOE-Pb |
When the user clicks finish edit button and ends the write off edition
Then the user checks the product with 'WriteOff-WOE-Pb' sku has elements on the write off page
| elementName | value |
| writeOff product barCode review | WriteOff-WOE-Pb |
Given the user opens amount list page
Then the user checks the product with 'WriteOff-WOE-Pb' sku has 'amounts amount' element equal to '-10' on amounts page
And the user checks the product with 'WriteOff-ProductBarCode-autocomplete' sku has 'amounts amount' element equal to '0' on amounts page

Scenario: Write off edition - product quantity

Given there is the write off with 'WriteOff-Edit-productQuantity' number with product 'WriteOff-ProductQuantity' with quantity '50', price '15' and cause 'Причина сдачи: Истек срок хранения'
And the user opens amount list page
And the user logs in as 'departmentManager'
Then the user checks the product with 'WriteOff-ProductQuantity' sku has 'amounts amount' element equal to '-50' on amounts page
Given the user navigates to the write off with number 'WriteOff-Edit-productQuantity'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff product quantity review' write off element to edit it
And the user inputs '12' in the 'inline writeOff product quantity' field on the write off page
And the user clicks OK and accepts changes
Then the user checks the product with 'WriteOff-ProductQuantity' sku has elements on the write off page
| elementName | value |
| writeOff product quantity review | 12 |
When the user clicks finish edit button and ends the write off edition
Then the user checks the product with 'WriteOff-ProductQuantity' sku has elements on the write off page
| elementName | value |
| writeOff product quantity review | 12 |
Given the user opens amount list page
Then the user checks the product with 'WriteOff-ProductQuantity' sku has 'amounts amount' element equal to '-12' on amounts page

Scenario: Write off edition - product price

Given there is the write off with 'WriteOff-Edit-productPrice' number with product 'WriteOff-ProductPrice' with quantity '10', price '15' and cause 'Причина сдачи: Истек срок хранения'
And the user navigates to the write off with number 'WriteOff-Edit-productPrice'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff product price review' write off element to edit it
And the user inputs '6,25' in the 'inline writeOff product price' field on the write off page
And the user clicks OK and accepts changes
Then the user checks the product with 'WriteOff-ProductPrice' sku has elements on the write off page
| elementName | value |
| writeOff product price review | 6,25 |
When the user clicks finish edit button and ends the write off edition
Then the user checks the product with 'WriteOff-ProductPrice' sku has elements on the write off page
| elementName | value |
| writeOff product price review | 6,25 |

Scenario: Write off edition - product cause

Given there is the write off with 'WriteOff-Edit-productCause' number with product 'WriteOff-ProductCause' with quantity '10', price '15' and cause 'Причина сдачи: Истек срок хранения'
And the user navigates to the write off with number 'WriteOff-Edit-productCause'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff cause review' write off element to edit it
And the user inputs 'Новая причина' in the 'inline writeOff cause' field on the write off page
And the user clicks OK and accepts changes
Then the user checks the product with 'WriteOff-ProductCause' sku has elements on the write off page
| elementName | value |
| writeOff cause review | Новая причина |
When the user clicks finish edit button and ends the write off edition
Then the user checks the product with 'WriteOff-ProductCause' sku has elements on the write off page
| elementName | value |
| writeOff cause review | Новая причина |

Scenario: Write off edition - adding new product

Given there is the product with 'WriteOff-WOE-ANP' name, 'WriteOff-WOE-ANP' barcode, 'liter' units, '15' purchasePrice
And navigate to new write off with 'WriteOff-Edit-productAdd' number
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user inputs 'WriteOff-WOE-ANP' in the 'writeOff product name autocomplete' field on the write off page
And the user inputs '10' in the 'writeOff product quantity' field on the write off page
And the user inputs '10' in the 'writeOff product price' field on the write off page
And the user inputs 'Причина сдачи: Истек срок хранения' in the 'writeOff cause' field on the write off page
And the user presses the add product button and add the product to write off
And the user clicks finish edit button and ends the write off edition
Then the user checks the write off product with 'WriteOff-WOE-ANP' sku is present
And the user checks the product with 'WriteOff-WOE-ANP' sku has elements on the write off page
| elementName | value |
| writeOff product sku review | WriteOff-WOE-ANP |
| writeOff product barCode review | WriteOff-WOE-ANP |
| writeOff product quantity review | 10 |
| writeOff product price review | 10 |
| writeOff cause review | Причина сдачи: Истек срок хранения |
Then the user checks the write off product with 'WriteOff-WOE-ANP' sku is present
And the user checks the product with 'WriteOff-WOE-ANP' sku has elements on the write off page
| elementName | value |
| writeOff product sku review | WriteOff-WOE-ANP |
| writeOff product barCode review | WriteOff-WOE-ANP |
| writeOff product quantity review | 10 |
| writeOff product price review | 10 |
| writeOff cause review | Причина сдачи: Истек срок хранения |

Scenario: Write off edition - deleting product

Given there is the write off with 'WriteOff-Edit-productDelete' number with product 'WriteOff-WOE-ANP-1' with quantity '10', price '15' and cause 'Причина сдачи: Истек срок хранения'
And the user opens amount list page
And the user logs in as 'departmentManager'
Then the user checks the product with 'WriteOff-WOE-ANP-1' sku has 'amounts amount' element equal to '-10' on amounts page
Given the user navigates to the write off with number 'WriteOff-Edit-productDelete'
When the user clicks edit button and starts write off edition
And the user deletes the write off product with 'WriteOff-WOE-ANP-1' sku
And the user clicks OK and accepts deletion
Then the user checks the write off product with 'WriteOff-WOE-ANP-1' sku is not present
When the user clicks finish edit button and ends the write off edition
Then the user checks the write off product with 'WriteOff-WOE-ANP-1' sku is not present
Given the user opens amount list page
Then the user checks the product with 'WriteOff-WOE-ANP-1' sku has 'amounts amount' element equal to '0' on amounts page

Scenario: Write off edition - deleting product cancel

Given there is the write off with 'WriteOff-Edit-productDelete-2' number with product 'WriteOff-WOE-ANP-2' with quantity '10', price '15' and cause 'Причина сдачи: Истек срок хранения'
And the user opens amount list page
And the user logs in as 'departmentManager'
Then the user checks the product with 'WriteOff-WOE-ANP-2' sku has 'amounts amount' element equal to '-10' on amounts page
Given the user navigates to the write off with number 'WriteOff-Edit-productDelete-2'
When the user clicks edit button and starts write off edition
And the user deletes the write off product with 'WriteOff-WOE-ANP-2' sku
And the user clicks Cancel and discard deletion
Then the user checks the write off product with 'WriteOff-WOE-ANP-2' sku is present
When the user clicks finish edit button and ends the write off edition
Then the user checks the write off product with 'WriteOff-WOE-ANP-2' sku is present
















