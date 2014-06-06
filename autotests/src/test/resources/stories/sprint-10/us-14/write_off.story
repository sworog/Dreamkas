﻿14 Списания

Meta:
@sprint_10
@us_14
@s10u14s01

Scenario: Write off creation

Meta:
@s10u14s01e01
@smoke

Given there is the product with 'WriteOff-ProductName' name, 'WriteOff-ProductBarCode' barcode, 'unit' type, '15' purchasePrice
And the user navigates to the default subCategory product list page
And the user logs in as 'departmentManager'

When the user opens product balance tab

Then the user checks the product with name 'WriteOff-ProductName' has inventory equals to '0,0'

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
And the user checks the write off product list contains entries
| productName | productSku | productBarcode | productAmount | productUnits | productPrice | productCause
| WriteOff-ProductName | #sku:WriteOff-ProductName | WriteOff-ProductBarCode | 10,0 | шт. | 15,00 | Причина сдачи: Истек срок хранения |

Then the user checks write off elements values
| elementName | value |
| totalProducts | 1 |
| totalSum | 150 |

Given the user navigates to the default subCategory product list page

When the user opens product balance tab

Then the user checks the product with name 'WriteOff-ProductName' has inventory equals to '-10,0'

Scenario: Write Off product name autocomplete

Meta:
@s10u14s01e02

Given there is the product with 'WriteOff-ProductName' name, 'WriteOff-ProductBarCode' barcode, 'unit' type, '15' purchasePrice
And the user opens the write off create page
And the user logs in as 'departmentManager'
When the user inputs 'WriteOff pna' in the 'writeOff number' field on the write off page
And the user inputs 'todayDate' in the 'writeOff date' field on the write off page
And the user continues the write off creation
When the user inputs 'WriteOff-ProductName' in the 'writeOff product name autocomplete' field on the write off page

Scenario: Write Off product sku autocomplete

Meta:
@s10u14s01e03

Given there is the product with 'WriteOff-ProductName' name, 'WriteOff-ProductBarCode' barcode, 'unit' type, '15' purchasePrice
And the user opens the write off create page
And the user logs in as 'departmentManager'
When the user inputs 'WriteOff psa' in the 'writeOff number' field on the write off page
And the user inputs 'todayDate' in the 'writeOff date' field on the write off page
And the user continues the write off creation
When the user inputs the sku of product with name 'WriteOff-ProductName' in the 'writeOff product sku autocomplete' field on the write off page

Scenario: Write Off product barcode autocomplete

Meta:
@s10u14s01e04

Given there is the product with 'WriteOff-ProductName' name, 'WriteOff-ProductBarCode' barcode, 'unit' type, '15' purchasePrice
And the user opens the write off create page
And the user logs in as 'departmentManager'
When the user inputs 'WriteOff pba' in the 'writeOff number' field on the write off page
And the user inputs 'todayDate' in the 'writeOff date' field on the write off page
And the user continues the write off creation
When the user inputs 'WriteOff-ProductBarCode' in the 'writeOff product barCode autocomplete' field on the write off page

Scenario: Write off price is filled by purchase price

Meta:
@s10u14s01e06
@smoke

Given there is the product with 'WriteOff-ProductName' name, 'WriteOff-ProductBarCode' barcode, 'unit' type, '15' purchasePrice
And the user opens the write off create page
And the user logs in as 'departmentManager'
When the user inputs 'WriteOff ifbpp' in the 'writeOff number' field on the write off page
And the user inputs 'todayDate' in the 'writeOff date' field on the write off page
And the user continues the write off creation
When the user inputs 'WriteOff-ProductName' in the 'writeOff product name autocomplete' field on the write off page
Then the user checks write off elements values
| elementName | value |
| writeOff product price | 15 |

Scenario: Write off review weight type

Meta:
@s10u14s01e07

Given there is the product with 'WriteOff-ProductName-review-weight' name, 'WriteOff-ProductBarCode-review-weight' barcode, 'weight' type, '15' purchasePrice
And the user navigates to the default subCategory product list page
And the user logs in as 'departmentManager'

When the user opens product balance tab

Then the user checks the product with name 'WriteOff-ProductName-review-weight' has inventory equals to '0,0'

Given the user opens the write off create page
When the user inputs 'WriteOff Number-1' in the 'writeOff number' field on the write off page
And the user inputs '24.10.2012' in the 'writeOff date' field on the write off page
And the user continues the write off creation
And the user inputs 'WriteOff-ProductName-review-weight' in the 'writeOff product name autocomplete' field on the write off page
And the user inputs '10' in the 'writeOff product quantity' field on the write off page
And the user inputs 'Причина сдачи: Истек срок хранения' in the 'writeOff cause' field on the write off page
And the user presses the add product button and add the product to write off
And the user clicks finish edit button and ends the write off edition
Then the user checks write off elements values
| elementName | value |
| writeOff number review | WriteOff Number-1 |
| writeOff date review | 24.10.2012 |
And the user checks the write off product list contains entries
| productName | productSku | productBarcode | productAmount | productUnits | productPrice | productCause
| WriteOff-ProductName-review-weight | #sku:WriteOff-ProductName-review-weight | WriteOff-ProductBarCode-review-weight | 10,0 | кг | 15,00 | Причина сдачи: Истек срок хранения |

Then the user checks write off elements values
| elementName | value |
| totalProducts | 1 |
| totalSum | 150 |

Given the user navigates to the default subCategory product list page

When the user opens product balance tab

Then the user checks the product with name 'WriteOff-ProductName-review-weight' has inventory equals to '-10,0'

Scenario: Write off review unit type

Meta:
@s10u14s01e09

Given there is the product with 'WriteOff-ProductName-review-unit' name, 'WriteOff-ProductBarCode-review-unit' barcode, 'unit' type, '15' purchasePrice
And the user navigates to the default subCategory product list page
And the user logs in as 'departmentManager'

When the user opens product balance tab

Then the user checks the product with name 'WriteOff-ProductName-review-unit' has inventory equals to '0,0'
Given the user opens the write off create page
When the user inputs 'WriteOff Number-1' in the 'writeOff number' field on the write off page
And the user inputs '24.10.2012' in the 'writeOff date' field on the write off page
And the user continues the write off creation
And the user inputs 'WriteOff-ProductName-review-unit' in the 'writeOff product name autocomplete' field on the write off page
And the user inputs '10' in the 'writeOff product quantity' field on the write off page
And the user inputs 'Причина сдачи: Истек срок хранения' in the 'writeOff cause' field on the write off page
And the user presses the add product button and add the product to write off
And the user clicks finish edit button and ends the write off edition
Then the user checks write off elements values
| elementName | value |
| writeOff number review | WriteOff Number-1 |
| writeOff date review | 24.10.2012 |
And the user checks the write off product list contains entries
| productName | productSku | productBarcode | productAmount | productUnits | productPrice | productCause
| WriteOff-ProductName-review-unit | #sku:WriteOff-ProductName-review-unit | WriteOff-ProductBarCode-review-unit | 10,0 | шт. | 15,00 | Причина сдачи: Истек срок хранения |

Then the user checks write off elements values
| elementName | value |
| totalProducts | 1 |
| totalSum | 150 |

Given the user navigates to the default subCategory product list page

When the user opens product balance tab
Then the user checks the product with name 'WriteOff-ProductName-review-unit' has inventory equals to '-10,0'

Scenario: Review different products types in one write off

Meta:
@s10u14s01e10

Given there is the product with 'WriteOff-ProductName-review-weight' name, 'WriteOff-ProductBarCode-review-weight' barcode, 'weight' type, '15' purchasePrice
Given there is the product with 'WriteOff-ProductName-review-unit' name, 'WriteOff-ProductBarCode-review-unit' barcode, 'unit' type, '15' purchasePrice
And the user navigates to the default subCategory product list page
And the user logs in as 'departmentManager'

When the user opens product balance tab

Then the user checks the product with name 'WriteOff-ProductName-review-weight' has inventory equals to '-10,0'
Then the user checks the product with name 'WriteOff-ProductName-review-unit' has inventory equals to '-10,0'
Given the user opens the write off create page
When the user inputs 'WriteOff Number-1' in the 'writeOff number' field on the write off page
And the user inputs '24.10.2012' in the 'writeOff date' field on the write off page
And the user continues the write off creation
And the user inputs 'WriteOff-ProductName-review-weight' in the 'writeOff product name autocomplete' field on the write off page
And the user inputs '10' in the 'writeOff product quantity' field on the write off page
And the user inputs 'Причина сдачи: Истек срок хранения' in the 'writeOff cause' field on the write off page
And the user presses the add product button and add the product to write off
When the user inputs 'WriteOff-ProductName-review-unit' in the 'writeOff product name autocomplete' field on the write off page
And the user inputs '10' in the 'writeOff product quantity' field on the write off page
And the user inputs 'Причина сдачи: Истек срок хранения' in the 'writeOff cause' field on the write off page
And the user presses the add product button and add the product to write off
And the user clicks finish edit button and ends the write off edition

Then the user checks write off elements values
| elementName | value |
| writeOff number review | WriteOff Number-1 |
| writeOff date review | 24.10.2012 |
And the user checks the write off product list contains entries
| productName | productSku | productBarcode | productAmount | productUnits | productPrice | productCause
| WriteOff-ProductName-review-weight | #sku:WriteOff-ProductName-review-weight | WriteOff-ProductBarCode-review-weight | 10,0 | кг | 15,00 | Причина сдачи: Истек срок хранения |
And the user checks the write off product list contains entries
| productName | productSku | productBarcode | productAmount | productUnits | productPrice | productCause
| WriteOff-ProductName-review-unit | #sku:WriteOff-ProductName-review-unit | WriteOff-ProductBarCode-review-unit | 10,0 | шт. | 15,00 | Причина сдачи: Истек срок хранения |

Then the user checks write off elements values
| elementName | value |
| totalProducts | 2 |
| totalSum | 300 |

Given the user navigates to the default subCategory product list page

When the user opens product balance tab

Then the user checks the product with name 'WriteOff-ProductName-review-weight' has inventory equals to '-20,0'
Then the user checks the product with name 'WriteOff-ProductName-review-unit' has inventory equals to '-20,0'

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

Given there is the product with 'WriteOff-WOE-PN' name, 'WriteOff-WOE-PN' barcode, 'unit' type, '15' purchasePrice
And there is the write off with 'WriteOff-Edit-productName' number with product 'WriteOff-ProductName-autocomplete' with quantity '10', price '15' and cause 'Причина сдачи: Истек срок хранения'
And the user navigates to the default subCategory product list page
And the user logs in as 'departmentManager'

When the user opens product balance tab

Then the user checks the product with name 'WriteOff-WOE-PN' has inventory equals to '0,0'
And the user checks the product with name 'WriteOff-ProductName-autocomplete' has inventory equals to '-10,0'
Given the user navigates to the write off with number 'WriteOff-Edit-productName'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff product name review' write off element to edit it
And the user inputs 'WriteOff-WOE-PN' in the 'inline writeOff product name autocomplete' field on the write off page
And the user clicks OK and accepts changes
Then the user checks the write off product list contains entries
 | productName | productSku | productBarcode | productAmount | productUnits | productPrice | productCause
 | WriteOff-WOE-PN | #sku:WriteOff-WOE-PN | WriteOff-WOE-PN | 10,0 | шт. | 15,00 | Причина сдачи: Истек срок хранения |
When the user clicks finish edit button and ends the write off edition
Then the user checks the write off product list contains entries
| productName | productSku | productBarcode | productAmount | productUnits | productPrice | productCause
| WriteOff-WOE-PN | #sku:WriteOff-WOE-PN | WriteOff-WOE-PN | 10,0 | шт. | 15,00 | Причина сдачи: Истек срок хранения |

Given the user navigates to the default subCategory product list page

When the user opens product balance tab

Then the user checks the product with name 'WriteOff-WOE-PN' has inventory equals to '-10,0'
And the user checks the product with name 'WriteOff-ProductName-autocomplete' has inventory equals to '0,0'

Scenario: Write off edition - product sku

Given there is the product with 'WriteOff-WOE-PS' name, 'WriteOff-WOE-PS' barcode, 'unit' type, '15' purchasePrice
And there is the write off with 'WriteOff-Edit-productSku' number with product 'WriteOff-ProductSku-autocomplete' with quantity '10', price '15' and cause 'Причина сдачи: Истек срок хранения'
And the user navigates to the default subCategory product list page
And the user logs in as 'departmentManager'

When the user opens product balance tab

Then the user checks the product with name 'WriteOff-WOE-PS' has inventory equals to '0,0'
And the user checks the product with name 'WriteOff-ProductSku-autocomplete' has inventory equals to '-10,0'

Given the user navigates to the write off with number 'WriteOff-Edit-productSku'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff product sku review' write off element to edit it
And the user inputs the sku of product with name 'WriteOff-WOE-PS' in the 'inline writeOff product sku autocomplete' field on the write off page
And the user clicks OK and accepts changes
Then the user checks the write off product list contains entries
| productName | productSku | productBarcode | productAmount | productUnits | productPrice | productCause
| WriteOff-WOE-PS | #sku:WriteOff-WOE-PS | WriteOff-WOE-PS | 10,0 | шт. | 15,00 | Причина сдачи: Истек срок хранения |
When the user clicks finish edit button and ends the write off edition
Then the user checks the write off product list contains entries
| productName | productSku | productBarcode | productAmount | productUnits | productPrice | productCause
| WriteOff-WOE-PS | #sku:WriteOff-WOE-PS | WriteOff-WOE-PS | 10,0 | шт. | 15,00 | Причина сдачи: Истек срок хранения |

Given the user navigates to the default subCategory product list page

When the user opens product balance tab
Then the user checks the product with name 'WriteOff-WOE-PS' has inventory equals to '-10,0'
And the user checks the product with name 'WriteOff-ProductSku-autocomplete' has inventory equals to '0,0'

Scenario: Write off edition - product barcode

Given there is the product with 'WriteOff-WOE-Pb' name, 'WriteOff-WOE-Pb' barcode, 'unit' type, '15' purchasePrice
And there is the write off with 'WriteOff-Edit-productBarCode' number with product 'WriteOff-ProductBarCode-autocomplete' with quantity '10', price '15' and cause 'Причина сдачи: Истек срок хранения'
And the user navigates to the default subCategory product list page
And the user logs in as 'departmentManager'

When the user opens product balance tab

Then the user checks the product with name 'WriteOff-WOE-Pb' has inventory equals to '0,0'
And the user checks the product with name 'WriteOff-ProductBarCode-autocomplete' has inventory equals to '-10,0'

Given the user navigates to the write off with number 'WriteOff-Edit-productBarCode'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff product barCode review' write off element to edit it
And the user inputs 'WriteOff-WOE-Pb' in the 'inline writeOff product barCode autocomplete' field on the write off page
And the user clicks OK and accepts changes

Then the user checks the write off product list contains entries
| productName | productSku | productBarcode | productAmount | productUnits | productPrice | productCause
| WriteOff-WOE-Pb | #sku:WriteOff-WOE-Pb | WriteOff-WOE-Pb | 10,0 | шт. | 15,00 | Причина сдачи: Истек срок хранения |
When the user clicks finish edit button and ends the write off edition
Then the user checks the write off product list contains entries
| productName | productSku | productBarcode | productAmount | productUnits | productPrice | productCause
| WriteOff-WOE-Pb | #sku:WriteOff-WOE-Pb | WriteOff-WOE-Pb | 10,0 | шт. | 15,00 | Причина сдачи: Истек срок хранения |

Given the user navigates to the default subCategory product list page

When the user opens product balance tab

Then the user checks the product with name 'WriteOff-WOE-Pb' has inventory equals to '-10,0'
And the user checks the product with name 'WriteOff-ProductBarCode-autocomplete' has inventory equals to '0,0'

Scenario: Write off edition - product quantity

Given there is the write off with 'WriteOff-Edit-productQuantity' number with product 'WriteOff-ProductQuantity' with quantity '50', price '15' and cause 'Причина сдачи: Истек срок хранения'
And the user navigates to the default subCategory product list page
And the user logs in as 'departmentManager'

When the user opens product balance tab

Then the user checks the product with name 'WriteOff-ProductQuantity' has inventory equals to '-50,0'

Given the user navigates to the write off with number 'WriteOff-Edit-productQuantity'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff product quantity review' write off element to edit it
And the user inputs '12' in the 'inline writeOff product quantity' field on the write off page
And the user clicks OK and accepts changes
Then the user checks the write off product list contains entries
| productName | productSku | productBarcode | productAmount | productUnits | productPrice | productCause
| WriteOff-ProductQuantity | #sku:WriteOff-ProductQuantity | WriteOff-ProductQuantity | 12,0 | кг | 15,00 | Причина сдачи: Истек срок хранения |
When the user clicks finish edit button and ends the write off edition
Then the user checks the write off product list contains entries
| productName | productSku | productBarcode | productAmount | productUnits | productPrice | productCause
| WriteOff-ProductQuantity | #sku:WriteOff-ProductQuantity | WriteOff-ProductQuantity | 12,0 | кг | 15,00 | Причина сдачи: Истек срок хранения |

Given the user navigates to the default subCategory product list page

When the user opens product balance tab

Then the user checks the product with name 'WriteOff-ProductQuantity' has inventory equals to '-12,0'

Scenario: Write off edition - product price

Given there is the write off with 'WriteOff-Edit-productPrice' number with product 'WriteOff-ProductPrice' with quantity '10', price '15' and cause 'Причина сдачи: Истек срок хранения'
And the user navigates to the write off with number 'WriteOff-Edit-productPrice'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff product price review' write off element to edit it
And the user inputs '6,25' in the 'inline writeOff product price' field on the write off page
And the user clicks OK and accepts changes
Then the user checks the write off product list contains entries
| productName | productSku | productBarcode | productAmount | productUnits | productPrice | productCause
| WriteOff-ProductPrice | #sku:WriteOff-ProductPrice | WriteOff-ProductPrice | 10,0 | кг | 6,25 | Причина сдачи: Истек срок хранения |
When the user clicks finish edit button and ends the write off edition
Then the user checks the write off product list contains entries
| productName | productSku | productBarcode | productAmount | productUnits | productPrice | productCause
| WriteOff-ProductPrice | #sku:WriteOff-ProductPrice | WriteOff-ProductPrice | 10,0 | кг | 6,25 | Причина сдачи: Истек срок хранения |

Scenario: Write off edition - product cause

Given there is the write off with 'WriteOff-Edit-productCause' number with product 'WriteOff-ProductCause' with quantity '10', price '15' and cause 'Причина сдачи: Истек срок хранения'
And the user navigates to the write off with number 'WriteOff-Edit-productCause'
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff cause review' write off element to edit it
And the user inputs 'Новая причина' in the 'inline writeOff cause' field on the write off page
And the user clicks OK and accepts changes
Then the user checks the write off product list contains entries
| productName | productSku | productBarcode | productAmount | productUnits | productPrice | productCause
| WriteOff-ProductCause | #sku:WriteOff-ProductCause | WriteOff-ProductCause | 10,0 | кг | 15,00 | Новая причина |
When the user clicks finish edit button and ends the write off edition
Then the user checks the write off product list contains entries
| productName | productSku | productBarcode | productAmount | productUnits | productPrice | productCause
| WriteOff-ProductCause | #sku:WriteOff-ProductCause | WriteOff-ProductCause | 10,0 | кг | 15,00 | Новая причина |

Scenario: Write off edition - adding new product

Given there is the product with 'WriteOff-WOE-ANP' name, 'WriteOff-WOE-ANP' barcode, 'unit' type, '15' purchasePrice
And navigate to new write off with 'WriteOff-Edit-productAdd' number
And the user logs in as 'departmentManager'
When the user clicks edit button and starts write off edition
And the user inputs 'WriteOff-WOE-ANP' in the 'writeOff product name autocomplete' field on the write off page
And the user inputs '10' in the 'writeOff product quantity' field on the write off page
And the user inputs '10' in the 'writeOff product price' field on the write off page
And the user inputs 'Причина сдачи: Истек срок хранения' in the 'writeOff cause' field on the write off page
And the user presses the add product button and add the product to write off
And the user clicks finish edit button and ends the write off edition

Then the user checks the write off product list contains entries
| productName | productSku | productBarcode | productAmount | productUnits | productPrice | productCause |
| WriteOff-WOE-ANP | #sku:WriteOff-WOE-ANP | WriteOff-WOE-ANP | 10,0 | шт. | 10,00 | Причина сдачи: Истек срок хранения |

Scenario: Write off edition - deleting product

Given there is the write off with 'WriteOff-Edit-productDelete' number with product 'WriteOff-WOE-ANP-1' with quantity '10', price '15' and cause 'Причина сдачи: Истек срок хранения'
And the user navigates to the default subCategory product list page
And the user logs in as 'departmentManager'

When the user opens product balance tab

Then the user checks the product with name 'WriteOff-WOE-ANP-1' has inventory equals to '-10,0'

Given the user navigates to the write off with number 'WriteOff-Edit-productDelete'
When the user clicks edit button and starts write off edition
And the user deletes the write off product with 'WriteOff-WOE-ANP-1' name
And the user clicks OK and accepts deletion
Then the user checks the write off product with 'WriteOff-WOE-ANP-1' name is not present
When the user clicks finish edit button and ends the write off edition
Then the user checks the write off product with 'WriteOff-WOE-ANP-1' name is not present

Given the user navigates to the default subCategory product list page

When the user opens product balance tab

Then the user checks the product with name 'WriteOff-WOE-ANP-1' has inventory equals to '0,0'

Scenario: Write off edition - deleting product cancel

Given there is the write off with 'WriteOff-Edit-productDelete-2' number with product 'WriteOff-WOE-ANP-2' with quantity '10', price '15' and cause 'Причина сдачи: Истек срок хранения'
And the user navigates to the default subCategory product list page
And the user logs in as 'departmentManager'

When the user opens product balance tab

Then the user checks the product with name 'WriteOff-WOE-ANP-2' has inventory equals to '-10,0'

Given the user navigates to the write off with number 'WriteOff-Edit-productDelete-2'
When the user clicks edit button and starts write off edition
And the user deletes the write off product with 'WriteOff-WOE-ANP-2' name
And the user clicks Cancel and discard deletion
Then the user checks the write off product with 'WriteOff-WOE-ANP-2' name is present
When the user clicks finish edit button and ends the write off edition
Then the user checks the write off product with 'WriteOff-WOE-ANP-2' name is present
















