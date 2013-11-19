Meta:
@sprint 23
@us 52

Narrative:
As a заведующий отделом
I want to чтобы при продаже, приемке, списании товара учетная система корректно обрабатывала дробные значения количества
In order была возможность работать с реальными количествами товара

GivenStories: precondition/aPreconditionToStoryUs52.story

Scenario: Adding invoice product with fractional quantity

Meta:
@id s23u52s1
@description invoice have product with fractional quantity
@smoke

Given the user navigates to the subCategory 'defaultSubCategory-s23u52', category 'defaultCategory-s23u52', group 'defaultGroup-s23u52' product list page
When the user logs in using 'departmentManager-s23u52' userName and 'lighthouse' password
And the user opens product balance tab
Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| name-2352 | sku-2352 | barcode-2352 | 3,675 | 0,00 | 0,0 | 126,99 р. | — |

Scenario: Adding writeOff product with fractional quantity

Meta:
@id s23u52s2
@description writeOff have product with fractional quantity
@smoke

Given the user navigates to the subCategory 'defaultSubCategory-s23u52', category 'defaultCategory-s23u52', group 'defaultGroup-s23u52' product list page
When the user logs in using 'departmentManager-s23u52' userName and 'lighthouse' password
And the user opens product balance tab
Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| name-2352-1 | sku-2352-1 | barcode-2352-1 | -4,671 | 0,00 | 0,0 | 134,80 р. | — |

Scenario: Making sale product with fractional quantity

Meta:
@id s23u52s3
@description import sale with fractional quantity
@smoke

Given the robot prepares import sales data for story 52
And the robot waits the import folder become empty
Given the user navigates to the subCategory 'defaultSubCategory-s23u52', category 'defaultCategory-s23u52', group 'defaultGroup-s23u52' product list page
When the user logs in using 'departmentManager-s23u52' userName and 'lighthouse' password
And the user opens product balance tab
Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| Черемша | 235212345 | 235212345 | -2,363 | 0,00 | 0,0 | 252,99 р. | — |

Scenario: Invoice quantity validation negative - 0,0003

Meta:
@id s23u52s4
@description negative product quantity input 0,0003, see the validation message

Given the user navigates to the invoice page with name 'invoice-2352-1'
When the user logs in using 'departmentManager-s23u52' userName and 'lighthouse' password
And the user inputs '0,0003' in the invoice product 'productAmount' field
And the user clicks the add more product button
Then the user sees error messages
| error message |
| Значение не должно содержать больше 3 цифр после запятой |

Scenario: Invoice edit quantity validation negative - 6,7689

Meta:
@id s23u52s5
@description negative product quantity input 6,7689, see the validation message

Given the user navigates to the invoice page with name 'invoice-2352-2'
When the user logs in using 'departmentManager-s23u52' userName and 'lighthouse' password
And the user clicks on property named 'productAmount' of invoice product named 'sku-2352-2'
And the user inputs the value '6,7689' in property named 'productAmount' of invoice product named 'sku-2352-2'
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Значение не должно содержать больше 3 цифр после запятой |

Scenario: WriteOff quantity validation negative - 0,6789

Meta:
@id s23u52s6
@description negative product quantity input 0,6789, see the validation message

Given the user navigates to the write off with number 'writeOff-2352-2'
When the user logs in using 'departmentManager-s23u52' userName and 'lighthouse' password
And the user clicks edit button and starts write off edition
And the user inputs 'name-2352-2' in the 'writeOff product name autocomplete' field on the write off page
When the user inputs '0,6789' in the write off product 'writeOff product quantity' field
And the user presses the add product button and add the product to write off
Then the user sees error messages
| error message |
| Значение не должно содержать больше 3 цифр после запятой |

Scenario: WriteOff edit quantity validation negative - 0,0003

Meta:
@id s23u52s7
@description negative product quantity input 0,6789, see the validation message

Given the user navigates to the write off with number 'writeOff-2352-3'
When the user logs in using 'departmentManager-s23u52' userName and 'lighthouse' password
When the user clicks edit button and starts write off edition
And the user clicks on property named 'productAmount' of writeOff product named 'sku-2352-2'
And the user inputs the value '0,0003' in property named 'productAmount' of writeOff product named 'sku-2352-2'
And the user clicks OK and accepts changes
Then the user sees error messages
| error message |
| Значение не должно содержать больше 3 цифр после запятой |