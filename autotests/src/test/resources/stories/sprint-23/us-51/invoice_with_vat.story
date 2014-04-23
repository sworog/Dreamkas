Meta:
@sprint_23
@us_51

Narrative:
As a заведующий отделом,
I want to при приемке товара видеть ставку НДС и сумму НДС каждого принимаего товара, сумму НДС регистрируемой накладной, а также содержит ли цена приемки НДС,
In order to не допустить ошибок в учете налогов при приемке

Scenario: The checkbox is active by default

Meta:
@id_s23u51s1
@description_
@smoke

GivenStories: precondition/sprint-23/us-51/aPreconditionToStoryUs51.story

Given the user is on the store '2351' invoice list page
And the user logs in using 'departmentManager-s23u51' userName and 'lighthouse' password

When the user clicks the create invoice link on invoice page menu navigation

Then the user checks the include vat checkbox is 'checked'

Scenario: The invoice with/without vat 10%

Meta:
@id_s23u51s3
@description_
@smoke

GivenStories: precondition/sprint-23/us-51/aPreconditionToStoryUs51.story,
              precondition/sprint-23/us-51/aPreconditionToScenarioS3.story

Given the user opens last created invoice page
And the user logs in using 'departmentManager-s23u51' userName and 'lighthouse' password

Then the user checks the invoice products list contains exact entries
| name | units | quantity | price | totalSum | vatSum |
| name-2351 | шт. | 7,0 | 100,00 | 700,00 руб. | 63,63 руб. |
| name-2351-01 | шт. | 15,0 | 120,00 | 1 800,00 руб. | 274,65 руб. |

Then the user checks the invoice total sum is 'Итого: 2 500,00 руб.'
And the user checks the invoice vat sum is 'НДС: 338,28 руб.'

Then the user checks the include vat checkbox is 'checked'

When the user clicks on the include vat checkbox

Then the user waits for checkBoxPreLoader finish

Then the user checks the include vat checkbox is 'unChecked'

Then the user checks the invoice products list contains exact entries
| name | units | quantity | price | totalSum | vatSum |
| name-2351 | шт. | 7,0 | 100,00 | 770,00 руб. | 70,00 руб. |
| name-2351-01 | шт. | 15,0 | 120,00 | 2 124,00 руб. |324,00 руб. |

Then the user checks the invoice total sum is 'Итого: 2 894,00 руб.'
And the user checks the invoice vat sum is 'НДС: 394,00 руб.'

Scenario: The invoice with/without vat 0%

Meta:
@id_s23u51s4
@description

GivenStories: precondition/sprint-23/us-51/aPreconditionToStoryUs51.story,
              precondition/sprint-23/us-51/aPreconditionToScenarioS4.story

Given the user opens last created invoice page
And the user logs in using 'departmentManager-s23u51' userName and 'lighthouse' password

Then the user checks the invoice products list contains exact entries
| name | units | quantity | price | totalSum | vatSum |
| name-2351-1 | шт. | 4,555 | 145,50 | 662,75 руб. | 0,00 руб. |

Then the user checks the include vat checkbox is 'checked'

When the user clicks on the include vat checkbox

Then the user waits for checkBoxPreLoader finish

Then the user checks the include vat checkbox is 'unChecked'

Then the user checks the invoice products list contains exact entries
| name | units | quantity | price | totalSum | vatSum |
| name-2351-1 | шт. | 4,555 | 145,50 | 662,75 руб. | 0,00 руб. |

Scenario: Average and last price are not changed if the price with/without vat

Meta:
@id_s23u51s6
@description_
@smoke

GivenStories: precondition/sprint-23/us-51/aPreconditionToStoryUs51.story,
              precondition/sprint-23/us-51/aPreconditionToScenarioS6.story

Given the user opens last created invoice page
And the user logs in using 'departmentManager-s23u51' userName and 'lighthouse' password

When the user clicks on the include vat checkbox

Then the user waits for checkBoxPreLoader finish

Then the user checks the include vat checkbox is 'unChecked'

Given the user runs the recalculate_metrics cap command
Given the user navigates to the subCategory 'defaultSubCategory-s23u51', category 'defaultCategory-s23u51', group 'defaultGroup-s23u51' product list page

When the user opens product balance tab

Then the user checks the product balance list contains entry
| name | sku | barcode | inventory | averageDailySales | inventoryDays | lastPurchasePrice | averagePurchasePrice |
| name-2351-3 | sku-2351-3 | barcode-2351-3 | 10,0 | 0,0 | 0,0 | 100,00 р. | 100,00 р. |
| name-2351-4 | sku-2351-4 | barcode-2351-4 | 10,0 | 0,0 | 0,0 | 110,00 р. | 110,00 р. |

Scenario: Vat is not changed in already invoiceProduct if product have new vat

Meta:
@id_s23u51s7
@description

GivenStories: precondition/sprint-23/us-51/aPreconditionToStoryUs51.story,
              precondition/sprint-23/us-51/aPreconditionToScenarioS7.story

Given the user opens one invoice ago created invoice page
And the user logs in using 'departmentManager-s23u51' userName and 'lighthouse' password

Then the user checks the invoice products list contains exact entries
| name | units | quantity | price | totalSum | vatSum |
| name-2351-6 | шт. | 10,0 | 100,00 | 1 000,00 руб. | 90,90 руб. |

Given the user opens last created invoice page

Then the user checks the invoice products list contains exact entries
| name | units | quantity | price | totalSum | vatSum |
| name-2351-6 | шт. | 10,0 | 100,00 | 1 000,00 руб. | 0,00 руб. |





