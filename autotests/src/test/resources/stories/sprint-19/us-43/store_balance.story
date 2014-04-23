Meta:
@sprint_19
@us_43

Narrative:
As a заведующим отделом
I want to работать с остатками своего магазина
In order to определять когда и какого объема требуется размещать заказ у поставщика.

Scenario: Store balance verification

Meta:
@smoke
@ids19u43s1

Given there is the user with name 'departmentManager-BIC', position 'departmentManager-BIC', username 'departmentManager-BIC', password 'lighthouse', role 'departmentManager'
And there is the user with name 'departmentManager-BIC-2', position 'departmentManager-BIC-2', username 'departmentManager-BIC-2', password 'lighthouse', role 'departmentManager'

Given there is the store with number 'BIC-01' managed by department manager named 'departmentManager-BIC'
And there is the store with number 'BIC-02' managed by department manager named 'departmentManager-BIC-2'

Given there is the product with 'SBV-01' name, 'SBV-01' sku, 'SBV-01' barcode

Given the user navigates to the subCategory 'defaultSubCategory', category 'defaultCategory', group 'defaultGroup' product list page
And the user logs in using 'departmentManager-BIC' userName and 'lighthouse' password

When the user opens product balance tab

Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| SBV-01 | SBV-01 | SBV-01 | 0,0 | 0,0 | 0,0 | 123,00 р. | — |

When the user logs out

Given the user navigates to the subCategory 'defaultSubCategory', category 'defaultCategory', group 'defaultGroup' product list page
And the user logs in using 'departmentManager-BIC-2' userName and 'lighthouse' password

When the user opens product balance tab

Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| SBV-01 | SBV-01 | SBV-01 | 0,0 | 0,0 | 0,0 | 123,00 р. | — |

When the user logs out

Given the user creates invoice api object with values
| elementName | value |
| acceptanceDate | todayDateAndTime |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber |
And the user adds the product with data to invoice api object
| elementName | value |
| productName |  SBV-01 |
| quantity | 5 |
| price | 10 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-BIC'

Given the user navigates to the subCategory 'defaultSubCategory', category 'defaultCategory', group 'defaultGroup' product list page
And the user logs in using 'departmentManager-BIC' userName and 'lighthouse' password

When the user opens product balance tab

Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| SBV-01 | SBV-01 | SBV-01 | 5,0 | 0,0 | 0,0 | 10,00 р. | — |

When the user logs out

Given the user navigates to the subCategory 'defaultSubCategory', category 'defaultCategory', group 'defaultGroup' product list page
And the user logs in using 'departmentManager-BIC-2' userName and 'lighthouse' password

When the user opens product balance tab

Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| SBV-01 | SBV-01 | SBV-01 | 0,0 | 0,0 | 0,0 | 123,00 р. | — |

When the user logs out

Given there is the write off with sku 'WriteOff-Bic-01' in the store with number 'BIC-01' ruled by user with name 'departmentManager-BIC'
And the user adds the product to the write off with number 'WriteOff-Bic-01' with sku 'SBV-01', quantity '3', price '5, cause 'Причины нет' in the store ruled by 'departmentManager-BIC'

Given the user navigates to the subCategory 'defaultSubCategory', category 'defaultCategory', group 'defaultGroup' product list page
And the user logs in using 'departmentManager-BIC' userName and 'lighthouse' password

When the user opens product balance tab

Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| SBV-01 | SBV-01 | SBV-01 | 2,0 | 0,0 | 0,0 | 10,00 р. | — |

When the user logs out

Given the user navigates to the subCategory 'defaultSubCategory', category 'defaultCategory', group 'defaultGroup' product list page
And the user logs in using 'departmentManager-BIC-2' userName and 'lighthouse' password

When the user opens product balance tab

Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| SBV-01 | SBV-01 | SBV-01 | 0,0 | 0,0 | 0,0 | 123,00 р. | — |

When the user logs out

Given the user creates invoice api object with values
| elementName | value |
| acceptanceDate | todayDateAndTime |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber |
And the user adds the product with data to invoice api object
| elementName | value |
| productName |  SBV-01 |
| quantity | 3 |
| price | 10 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-BIC-2'

Given the user navigates to the subCategory 'defaultSubCategory', category 'defaultCategory', group 'defaultGroup' product list page
And the user logs in using 'departmentManager-BIC' userName and 'lighthouse' password

When the user opens product balance tab

Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| SBV-01 | SBV-01 | SBV-01 | 2,0 | 0,0 | 0,0 | 10,00 р. | — |

When the user logs out

Given the user navigates to the subCategory 'defaultSubCategory', category 'defaultCategory', group 'defaultGroup' product list page
And the user logs in using 'departmentManager-BIC-2' userName and 'lighthouse' password

When the user opens product balance tab

Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| SBV-01 | SBV-01 | SBV-01 | 3,0 | 0,0 | 0,0 | 10,00 р. | — |

When the user logs out

Given there is the write off with sku 'WriteOff-Bic-02' in the store with number 'BIC-02' ruled by user with name 'departmentManager-BIC-2'
And the user adds the product to the write off with number 'WriteOff-Bic-02' with sku 'SBV-01', quantity '2', price '5, cause 'Причины нет' in the store ruled by 'departmentManager-BIC-2'

Given the user navigates to the subCategory 'defaultSubCategory', category 'defaultCategory', group 'defaultGroup' product list page
And the user logs in using 'departmentManager-BIC' userName and 'lighthouse' password

When the user opens product balance tab

Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| SBV-01 | SBV-01 | SBV-01 | 2,0 | 0,0 | 0,0 | 10,00 р. | — |

When the user logs out

Given the user navigates to the subCategory 'defaultSubCategory', category 'defaultCategory', group 'defaultGroup' product list page
And the user logs in using 'departmentManager-BIC-2' userName and 'lighthouse' password

When the user opens product balance tab

Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| SBV-01 | SBV-01 | SBV-01 | 1,0 | 0,0 | 0,0 | 10,00 р. | — |

Scenario: Store last purchase price

Meta:
@smoke
@ids19u43s2

Given there is the user with name 'departmentManager-BIC', position 'departmentManager-BIC', username 'departmentManager-BIC', password 'lighthouse', role 'departmentManager'
And there is the user with name 'departmentManager-BIC-2', position 'departmentManager-BIC-2', username 'departmentManager-BIC-2', password 'lighthouse', role 'departmentManager'

Given there is the store with number 'BIC-01' managed by department manager named 'departmentManager-BIC'
And there is the store with number 'BIC-02' managed by department manager named 'departmentManager-BIC-2'
Given there is the product with 'SBV-02' name, 'SBV-02' sku, 'SBV-02' barcode

Given the user navigates to the subCategory 'defaultSubCategory', category 'defaultCategory', group 'defaultGroup' product list page
And the user logs in using 'departmentManager-BIC' userName and 'lighthouse' password

When the user opens product balance tab

Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| SBV-02 | SBV-02 | SBV-02 | 0,0 | 0,0 | 0,0 | 123,00 р. | — |

When the user logs out

Given the user navigates to the subCategory 'defaultSubCategory', category 'defaultCategory', group 'defaultGroup' product list page
And the user logs in using 'departmentManager-BIC-2' userName and 'lighthouse' password

When the user opens product balance tab

Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| SBV-02 | SBV-02 | SBV-02 | 0,0 | 0,0 | 0,0 | 123,00 р. | — |

When the user logs out

Given the user creates invoice api object with values
| elementName | value |
| acceptanceDate | todayDateAndTime |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber |
And the user adds the product with data to invoice api object
| elementName | value |
| productName |  SBV-02 |
| quantity | 5 |
| price | 101 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-BIC'

Given the user navigates to the subCategory 'defaultSubCategory', category 'defaultCategory', group 'defaultGroup' product list page
And the user logs in using 'departmentManager-BIC' userName and 'lighthouse' password

When the user opens product balance tab

Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| SBV-02 | SBV-02 | SBV-02 | 5,0 | 0,0 | 0,0 | 101,00 р. | — |

When the user logs out

Given the user navigates to the subCategory 'defaultSubCategory', category 'defaultCategory', group 'defaultGroup' product list page
And the user logs in using 'departmentManager-BIC-2' userName and 'lighthouse' password

When the user opens product balance tab

Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| SBV-02 | SBV-02 | SBV-02 | 0,0 | 0,0 | 0,0 | 123,00 р. | — |

When the user logs out

Given the user creates invoice api object with values
| elementName | value |
| acceptanceDate | todayDateAndTime |
| accepter | accepter |
| legalEntity | legalEntity |
| supplierInvoiceNumber | supplierInvoiceNumber |
And the user adds the product with data to invoice api object
| elementName | value |
| productName |  SBV-02 |
| quantity | 5 |
| price | 156 |
And there is the invoice created with invoice builder steps by userName 'departmentManager-BIC-2'

Given the user navigates to the subCategory 'defaultSubCategory', category 'defaultCategory', group 'defaultGroup' product list page
And the user logs in using 'departmentManager-BIC' userName and 'lighthouse' password

When the user opens product balance tab

Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| SBV-02 | SBV-02 | SBV-02 | 5,0 | 0,0 | 0,0 | 101,00 р. | — |

When the user logs out

Given the user navigates to the subCategory 'defaultSubCategory', category 'defaultCategory', group 'defaultGroup' product list page
And the user logs in using 'departmentManager-BIC-2' userName and 'lighthouse' password

When the user opens product balance tab

Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| SBV-02 | SBV-02 | SBV-02 | 5,0 | 0,0 | 0,0 | 156,00 р. | — |