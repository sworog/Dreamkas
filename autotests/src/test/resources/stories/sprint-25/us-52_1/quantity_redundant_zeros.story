Meta:
@sprint_25
@us_52.1

Narrative:
As a заведущий отделом
I want to видеть значение количества товара без лишних нулей,
In order to мне было легче воспринимать бошльшой объем информации

actual -> expected
12,000 -> 12,0
85,560 -> 85,56
43,196 -> 43,196

Scenario: Product card precision check (12,000)

Meta:
@id_s25u52.1s1
@description 12,000 -> 12,0

GivenStories: precondition/sprint-25/us-52_1/aPreconditionToStoryUs52.1.story

Given there is the product with 'name-25521' name, '25521' barcode, 'unit' units, '124,5' purchasePrice of group named 'defaultGroup-s25u521', category named 'defaultCategory-s25u521', subcategory named 'defaultSubCategory-s25u521'

Given the user creates invoice api object with values
| elementName | value |
| acceptanceDate | 02.04.2013 16:23 |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | 25521 |
| quantity | 12,000 |
| price | 100 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-s25u521'

Given the user navigates to the product with sku '25521'
When the user logs in using 'departmentManager-s25u521' userName and 'lighthouse' password
Then the user checks the elements values
| elementName | value |
| inventory | 12,0 шт. |

Scenario: Product card precision check (85,560)

Meta:
@id_s25u52.1s2
@description 85,560 -> 85,56

GivenStories: precondition/sprint-25/us-52_1/aPreconditionToStoryUs52.1.story

Given there is the product with 'name-255212' name, '255212' barcode, 'unit' units, '124,5' purchasePrice of group named 'defaultGroup-s25u521', category named 'defaultCategory-s25u521', subcategory named 'defaultSubCategory-s25u521'

Given the user creates invoice api object with values
| elementName | value |
| acceptanceDate | 02.04.2013 16:23 |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | 255212 |
| quantity | 85,560 |
| price | 100 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-s25u521'

Given the user navigates to the product with sku '255212'
When the user logs in using 'departmentManager-s25u521' userName and 'lighthouse' password
Then the user checks the elements values
| elementName | value |
| inventory | 85,56 шт. |

Scenario: Product card precision check (43,196)

Meta:
@id_s25u52.1s3
@description 43,196 -> 43,196

GivenStories: precondition/sprint-25/us-52_1/aPreconditionToStoryUs52.1.story

Given there is the product with 'name-255213' name, '255213' barcode, 'unit' units, '124,5' purchasePrice of group named 'defaultGroup-s25u521', category named 'defaultCategory-s25u521', subcategory named 'defaultSubCategory-s25u521'

Given the user creates invoice api object with values
| elementName | value |
| acceptanceDate | 02.04.2013 16:23 |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | 255213 |
| quantity | 43,196 |
| price | 100 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-s25u521'

Given the user navigates to the product with sku '255213'
When the user logs in using 'departmentManager-s25u521' userName and 'lighthouse' password
Then the user checks the elements values
| elementName | value |
| inventory | 43,196 шт. |

Scenario: Balance product precision check (12,000)

Meta:
@id_s25u52.1s4
@description 12,000 -> 12,0

GivenStories: precondition/sprint-25/us-52_1/aPreconditionToStoryUs52.1.story

Given there is the product with 'name-255214' name, '255214' barcode, 'unit' units, '124,5' purchasePrice of group named 'defaultGroup-s25u521', category named 'defaultCategory-s25u521', subcategory named 'defaultSubCategory-s25u521'

Given the user creates invoice api object with values
| elementName | value |
| acceptanceDate | 02.04.2013 16:23 |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | 255214 |
| quantity | 12,000 |
| price | 100 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-s25u521'

Given the user navigates to the subCategory 'defaultSubCategory-s25u521', category 'defaultCategory-s25u521', group 'defaultGroup-s25u521' product list page
When the user logs in using 'departmentManager-s25u521' userName and 'lighthouse' password
And the user opens product balance tab
Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| name-255214 | 255214 | 255214 | 12,0 | 0,0 | 0,0 | 100,00 р. | — |

Scenario: Balance product precision check (85,560)

Meta:
@id_s25u52.1s5
@description 85,560 -> 85,56

GivenStories: precondition/sprint-25/us-52_1/aPreconditionToStoryUs52.1.story

Given there is the product with 'name-255215' name, '255215' barcode, 'unit' units, '124,5' purchasePrice of group named 'defaultGroup-s25u521', category named 'defaultCategory-s25u521', subcategory named 'defaultSubCategory-s25u521'

Given the user creates invoice api object with values
| elementName | value |
| acceptanceDate | 02.04.2013 16:23 |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | 255215 |
| quantity | 85,560 |
| price | 100 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-s25u521'

Given the user navigates to the subCategory 'defaultSubCategory-s25u521', category 'defaultCategory-s25u521', group 'defaultGroup-s25u521' product list page
When the user logs in using 'departmentManager-s25u521' userName and 'lighthouse' password
And the user opens product balance tab
Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| name-255215 | 255215 | 255215 | 85,56 | 0,0 | 0,0 | 100,00 р. | — |

Scenario: Balance product precision check (43,196)

Meta:
@id_s25u52.1s6
@description 43,196 -> 43,196

GivenStories: precondition/sprint-25/us-52_1/aPreconditionToStoryUs52.1.story

Given there is the product with 'name-255216' name, '255216' barcode, 'unit' units, '124,5' purchasePrice of group named 'defaultGroup-s25u521', category named 'defaultCategory-s25u521', subcategory named 'defaultSubCategory-s25u521'

Given the user creates invoice api object with values
| elementName | value |
| acceptanceDate | 02.04.2013 16:23 |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | 255216 |
| quantity | 43,196 |
| price | 100 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-s25u521'

Given the user navigates to the subCategory 'defaultSubCategory-s25u521', category 'defaultCategory-s25u521', group 'defaultGroup-s25u521' product list page
When the user logs in using 'departmentManager-s25u521' userName and 'lighthouse' password
And the user opens product balance tab
Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| name-255216 | 255216 | 255216 | 43,196 | 0,0 | 0,0 | 100,00 р. | — |

Scenario: Invoice product precision check (12,000)

Meta:
@id_s25u52.1s7
@description 12,000 -> 12,0

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/sprint-25/us-52_1/aPreconditionToStoryUs52.1.story

Given there is the product with 'name-255217' name, '255217' barcode, 'unit' units, '124,5' purchasePrice of group named 'defaultGroup-s25u521', category named 'defaultCategory-s25u521', subcategory named 'defaultSubCategory-s25u521'

Given the user creates invoice api object with values
| elementName | value |
| acceptanceDate | 02.04.2013 16:23 |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | 255217 |
| quantity | 12,000 |
| price | 100 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-s25u521'

Given the user opens last created invoice page
And the user logs in using 'departmentManager-s25u521' userName and 'lighthouse' password

Then the user checks the invoice products list contains exact entries
| name | units | quantity | price | totalSum | vatSum |
| name-255217 | шт. | 12,0 | 100,00 |  1 200,00 руб. | 0,00 руб. |

Scenario: Invoice product precision check (85,560)

Meta:
@id_s25u52.1s8
@description 85,560 -> 85,56

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/sprint-25/us-52_1/aPreconditionToStoryUs52.1.story

Given there is the product with 'name-255218' name, '255218' barcode, 'unit' units, '124,5' purchasePrice of group named 'defaultGroup-s25u521', category named 'defaultCategory-s25u521', subcategory named 'defaultSubCategory-s25u521'

Given the user creates invoice api object with values
| elementName | value |
| acceptanceDate | 02.04.2013 16:23 |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | 255218 |
| quantity | 85,560 |
| price | 100 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-s25u521'

Given the user opens last created invoice page
And the user logs in using 'departmentManager-s25u521' userName and 'lighthouse' password

Then the user checks the invoice products list contains exact entries
| name | units | quantity | price | totalSum | vatSum |
| name-255218 | шт. | 85,56 | 100,00 | 8 556,00 руб. | 0,00 руб. |

Scenario: Invoice product precision check (43,196)

Meta:
@id_s25u52.1s9
@description 43,196 -> 43,196

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/sprint-25/us-52_1/aPreconditionToStoryUs52.1.story

Given there is the product with 'name-255219' name, '255219' barcode, 'unit' units, '124,5' purchasePrice of group named 'defaultGroup-s25u521', category named 'defaultCategory-s25u521', subcategory named 'defaultSubCategory-s25u521'

Given the user creates invoice api object with values
| elementName | value |
| acceptanceDate | 02.04.2013 16:23 |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | 255219 |
| quantity | 43,196 |
| price | 100 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-s25u521'

Given the user opens last created invoice page
And the user logs in using 'departmentManager-s25u521' userName and 'lighthouse' password

Then the user checks the invoice products list contains exact entries
| name | units | quantity | price | totalSum | vatSum |
| name-255219 | шт. | 43,196 | 100,00 | 4 319,60 руб. | 0,00 руб. |

Scenario: Product invoices precision check (12,000)

Meta:
@id_s25u52.1s10
@description 12,000 -> 12,0

GivenStories: precondition/sprint-25/us-52_1/aPreconditionToStoryUs52.1.story

Given there is the product with 'name-2552120' name, '2552120' barcode, 'unit' units, '124,5' purchasePrice of group named 'defaultGroup-s25u521', category named 'defaultCategory-s25u521', subcategory named 'defaultSubCategory-s25u521'

Given the user creates invoice api object with values
| elementName | value |
| acceptanceDate | 02.04.2013 16:23 |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | 2552120 |
| quantity | 12,000 |
| price | 100 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-s25u521'

Given the user navigates to the product with sku '2552120'
When the user logs in using 'departmentManager-s25u521' userName and 'lighthouse' password
And the user clicks the product local navigation invoices link
Then the user checks the product invoices list contains entry
| acceptanceDateFormatted | quantity | priceFormatted | totalPriceFormatted |
| 02.04.2013 | 12,0 | 100,00 | 1 200,00 |

Scenario: Product invoices precision check (85,560)

Meta:
@id_s25u52.1s11
@description 85,560 -> 85,56

GivenStories: precondition/sprint-25/us-52_1/aPreconditionToStoryUs52.1.story

Given there is the product with 'name-2552121' name, '255218' barcode, 'unit' units, '124,5' purchasePrice of group named 'defaultGroup-s25u521', category named 'defaultCategory-s25u521', subcategory named 'defaultSubCategory-s25u521'

Given the user creates invoice api object with values
| elementName | value |
| acceptanceDate | 02.04.2013 16:23 |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | 2552121 |
| quantity | 85,560 |
| price | 100 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-s25u521'

Given the user navigates to the product with sku '2552121'
When the user logs in using 'departmentManager-s25u521' userName and 'lighthouse' password
And the user clicks the product local navigation invoices link
Then the user checks the product invoices list contains entry
| acceptanceDateFormatted | quantity | priceFormatted | totalPriceFormatted |
| 02.04.2013 | 85,56 | 100,00 | 8 556,00 |

Scenario: Product invoices precision check (43,196)

Meta:
@id_s25u52.1s12
@description 43,196 -> 43,196

GivenStories: precondition/sprint-25/us-52_1/aPreconditionToStoryUs52.1.story

Given there is the product with 'name-2552122' name, '2552122' barcode, 'unit' units, '124,5' purchasePrice of group named 'defaultGroup-s25u521', category named 'defaultCategory-s25u521', subcategory named 'defaultSubCategory-s25u521'

Given the user creates invoice api object with values
| elementName | value |
| acceptanceDate | 02.04.2013 16:23 |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber |
And the user adds the product with data to invoice api object
| elementName | value |
| productName | 2552122 |
| quantity | 43,196 |
| price | 100 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-s25u521'

Given the user navigates to the product with sku '2552122'
When the user logs in using 'departmentManager-s25u521' userName and 'lighthouse' password
And the user clicks the product local navigation invoices link
Then the user checks the product invoices list contains entry
| acceptanceDateFormatted | quantity | priceFormatted | totalPriceFormatted |
| 02.04.2013 | 43,196 | 100,00 | 4 319,60 |

Scenario: WriteOff product precision check (12,000)

Meta:
@id_s25u52.1s13
@description 12,000 -> 12,0

GivenStories: precondition/sprint-25/us-52_1/aPreconditionToStoryUs52.1.story

Given there is the product with 'name-2552123' name, '2552123' barcode, 'unit' units, '124,5' purchasePrice of group named 'defaultGroup-s25u521', category named 'defaultCategory-s25u521', subcategory named 'defaultSubCategory-s25u521'
And the user adds the product to the write off with number '25521-01' with sku '2552123', quantity '12,000', price '100, cause 'cause' in the store ruled by 'departmentManager-s25u521'

Given the user navigates to the write off with number '25521-01'
When the user logs in using 'departmentManager-s25u521' userName and 'lighthouse' password
Then the user checks the writeOff products list contains entry
| productName | productSku | productBarcode | productAmount | productUnits | productPrice | productCause |
| name-2552123 | 2552123 | 2552123 | 12,0 | шт. | 100,00 | cause |


Scenario: WriteOff product precision check (85,560)

Meta:
@id_s25u52.1s14
@description 85,560 -> 85,56

GivenStories: precondition/sprint-25/us-52_1/aPreconditionToStoryUs52.1.story

Given there is the product with 'name-2552124' name, '2552124' barcode, 'unit' units, '124,5' purchasePrice of group named 'defaultGroup-s25u521', category named 'defaultCategory-s25u521', subcategory named 'defaultSubCategory-s25u521'
And the user adds the product to the write off with number '25521-01' with sku '2552124', quantity '85,560', price '100, cause 'cause' in the store ruled by 'departmentManager-s25u521'

Given the user navigates to the write off with number '25521-01'
When the user logs in using 'departmentManager-s25u521' userName and 'lighthouse' password
Then the user checks the writeOff products list contains entry
| productName | productSku | productBarcode | productAmount | productUnits | productPrice | productCause |
| name-2552124 | 2552124 | 2552124 | 85,56 | шт. | 100,00 | cause |

Scenario: WriteOff product precision check (43,196)

Meta:
@id_s25u52.1s15
@description 43,196 -> 43,196

GivenStories: precondition/sprint-25/us-52_1/aPreconditionToStoryUs52.1.story

Given there is the product with 'name-2552125' name, '2552125' barcode, 'unit' units, '124,5' purchasePrice of group named 'defaultGroup-s25u521', category named 'defaultCategory-s25u521', subcategory named 'defaultSubCategory-s25u521'
And the user adds the product to the write off with number '25521-01' with sku '2552125', quantity '43,196', price '100, cause 'cause' in the store ruled by 'departmentManager-s25u521'

Given the user navigates to the write off with number '25521-01'
When the user logs in using 'departmentManager-s25u521' userName and 'lighthouse' password
Then the user checks the writeOff products list contains entry
| productName | productSku | productBarcode | productAmount | productUnits | productPrice | productCause |
| name-2552125 | 2552125 | 2552125 | 43,196 | шт. | 100,00 | cause |

Scenario: Product writeoff list precision check (12,000)

Meta:
@id_s25u52.1s16
@description 12,000 -> 12,0

GivenStories: precondition/sprint-25/us-52_1/aPreconditionToStoryUs52.1.story

Given there is the product with 'name-2552126' name, '2552126' barcode, 'unit' units, '124,5' purchasePrice of group named 'defaultGroup-s25u521', category named 'defaultCategory-s25u521', subcategory named 'defaultSubCategory-s25u521'
And the user adds the product to the write off with number '25521-01' with sku '2552126', quantity '12,000', price '100, cause 'cause' in the store ruled by 'departmentManager-s25u521'

Given the user navigates to the product with sku '2552126'
When the user logs in using 'departmentManager-s25u521' userName and 'lighthouse' password
And the user clicks the product local navigation writeoffs link
Then the user checks the product writeOff list contains entry
| createdDateFormatted | quantity | priceFormatted | totalPriceFormatted |
| 02.04.2013 | 12,0 | 100,00 | 1 200,00 |

Scenario: Product writeoff list precision check (85,560)

Meta:
@id_s25u52.1s17
@description 85,560 -> 85,56

GivenStories: precondition/sprint-25/us-52_1/aPreconditionToStoryUs52.1.story

Given there is the product with 'name-2552127' name, '2552127' barcode, 'unit' units, '124,5' purchasePrice of group named 'defaultGroup-s25u521', category named 'defaultCategory-s25u521', subcategory named 'defaultSubCategory-s25u521'
And the user adds the product to the write off with number '25521-01' with sku '2552127', quantity '85,560', price '100, cause 'cause' in the store ruled by 'departmentManager-s25u521'

Given the user navigates to the product with sku '2552127'
When the user logs in using 'departmentManager-s25u521' userName and 'lighthouse' password
And the user clicks the product local navigation writeoffs link
Then the user checks the product writeOff list contains entry
| createdDateFormatted | quantity | priceFormatted | totalPriceFormatted |
| 02.04.2013 | 85,56 | 100,00 | 8 556,00 |

Scenario: Product writeoff list precision check (43,196)

Meta:
@id_s25u52.1s18
@description 43,196 -> 43,196

GivenStories: precondition/sprint-25/us-52_1/aPreconditionToStoryUs52.1.story

Given there is the product with 'name-2552128' name, '2552128' barcode, 'unit' units, '124,5' purchasePrice of group named 'defaultGroup-s25u521', category named 'defaultCategory-s25u521', subcategory named 'defaultSubCategory-s25u521'
And the user adds the product to the write off with number '25521-01' with sku '2552128', quantity '43,196', price '100, cause 'cause' in the store ruled by 'departmentManager-s25u521'

Given the user navigates to the product with sku '2552128'
When the user logs in using 'departmentManager-s25u521' userName and 'lighthouse' password
And the user clicks the product local navigation writeoffs link
Then the user checks the product writeOff list contains entry
| createdDateFormatted | quantity | priceFormatted | totalPriceFormatted |
| 02.04.2013 | 43,196 | 100,00 | 4 319,60 |


Scenario: Product return list precision check (12,000)

Meta:
@id_s25u52.1s19
@description 12,000 -> 12,0

GivenStories: precondition/sprint-25/us-52_1/aPreconditionToStoryUs52.1.story

Given there is the product with 'name-2552129' name, '2552129' barcode, 'unit' units, '124,5' purchasePrice of group named 'defaultGroup-s25u521', category named 'defaultCategory-s25u521', subcategory named 'defaultSubCategory-s25u521'
And the user prepares fixture for story 52.1 precision 1

Given the user navigates to the product with sku '2552129'
When the user logs in using 'departmentManager-s25u521' userName and 'lighthouse' password
When the user clicks the product local navigation returns link
Then the user checks the product return list contains entry
| date | quantity | price | totalPrice |
| 03.10.2013 | 12,0 | 100,00 | 1 200,00 |


Scenario: Product return list precision check (85,560)

Meta:
@id_s25u52.1s20
@description 85,560 -> 85,56

GivenStories: precondition/sprint-25/us-52_1/aPreconditionToStoryUs52.1.story

Given there is the product with 'name-2552130' name, '2552130' barcode, 'unit' units, '124,5' purchasePrice of group named 'defaultGroup-s25u521', category named 'defaultCategory-s25u521', subcategory named 'defaultSubCategory-s25u521'
And the user prepares fixture for story 52.1 precision 2

Given the user navigates to the product with sku '2552130'
When the user logs in using 'departmentManager-s25u521' userName and 'lighthouse' password
When the user clicks the product local navigation returns link
Then the user checks the product return list contains entry
| date | quantity | price | totalPrice |
| 03.10.2013 | 85,56 | 100,00 | 8 556,00 |

Scenario: Product return list precision check (43,196)

Meta:
@id_s25u52.1s21
@description 43,196 -> 43,196

GivenStories: precondition/sprint-25/us-52_1/aPreconditionToStoryUs52.1.story

Given there is the product with 'name-2552131' name, '2552131' barcode, 'unit' units, '124,5' purchasePrice of group named 'defaultGroup-s25u521', category named 'defaultCategory-s25u521', subcategory named 'defaultSubCategory-s25u521'
And the user prepares fixture for story 52.1 precision 3

Given the user navigates to the product with sku '2552131'
When the user logs in using 'departmentManager-s25u521' userName and 'lighthouse' password
When the user clicks the product local navigation returns link
Then the user checks the product return list contains entry
| date | quantity | price | totalPrice |
| 03.10.2013 | 43,196 | 100,00 | 4 319,60 |



