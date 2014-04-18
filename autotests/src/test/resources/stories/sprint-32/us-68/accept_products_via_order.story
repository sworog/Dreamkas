Meta:
@sprint_32
@us_68
@invoice

Narrative:
As a заведущий отделом,
I want to принять товар на основании заказа,
In order to осуществить приемку быстро и контролируя ее состав

Scenario: Accept products via order

Meta:
@id_s32u68s1
@smoke

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/sprint-32/us-68/aUsersPreconditionToStory.story,
              precondition/sprint-32/us-68/aPreconditionWithData.story

Given the user opens order create page
And the user logs in using 'departmentManager-s32u68' userName and 'lighthouse' password

When the user inputs values on order page
| elementName | value |
| supplier | supplier-s32u68s1 |

When the user inputs values on order page
| elementName | value |
| order product autocomplete | name-3268 |
And the user inputs quantity '5,7' on the order product with name 'name-3268'
And the user presses 'ENTER' key button

Then the user waits for the order product edition preloader finish

Then the user checks the order products list contains entry
| name | units |quantity | retailPrice | totalSum |
| name-3268 | шт. | 5,7 | 100,00 | 570,00 |

Then the user checks the order total sum is 'Итого: 570,00 руб'

When the user clicks the save order button

When the user clicks on the order with number '10001' on the orders list page

When the user clicks the accept order button

Then the user checks values on the invoice page
| elementName | value |
| supplier | supplier-s32u68s1 |

Then the user checks the invoice products list contains exact entries
| name | units | quantity | price | totalSum | vatSum |
| name-3268 | шт. | 5,7 | 100,00 | 570,00 руб. | 0,00 руб. |

Then the user checks the invoice total sum is 'Итого: 570,00 руб.'

When the user inputs values on invoice page
| elementName | value |
| acceptanceDate | 02.04.2013 16:23 |
| accepter | accepter |
| supplierInvoiceNumber | supplierInvoiceNumber-1 |
| legalEntity | legalEntity |

When the user accepts products and saves the invoice

Then the user sees no error messages

When the user clicks the local navigation invoice search link
And the user searches invoice by sku or supplier sku '10001'
And the user clicks the invoice search button and starts the search

When the user clicks on the search result invoice with number '10001'

Then the user checks values on the invoice page
| elementName | value |
| supplier | supplier-s32u68s1 |
| acceptanceDate | 02.04.2013 16:23 |
| accepter | accepter |
| supplierInvoiceNumber | supplierInvoiceNumber-1 |
| legalEntity | legalEntity |

Then the user checks the invoice products list contains exact entries
| name | units | quantity | price | totalSum | vatSum |
| name-3268 | шт. | 5,7 | 100,00 | 570,00 руб. | 0,00 руб. |

Then the user checks the invoice total sum is 'Итого: 570,00 руб.'

Given the user opens orders list page

Then the user checks the orders list do not contain order with number '10001'

Scenario: Accept products via order cancel

Meta:
@id_s32u68s2

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/sprint-32/us-68/aUsersPreconditionToStory.story,
              precondition/sprint-32/us-68/aPreconditionWithData.story

Given the user opens order create page
And the user logs in using 'departmentManager-s32u68' userName and 'lighthouse' password

When the user inputs values on order page
| elementName | value |
| supplier | supplier-s32u68s1 |

When the user inputs values on order page
| elementName | value |
| order product autocomplete | name-3268 |
And the user inputs quantity '5,7' on the order product with name 'name-3268'
And the user presses 'ENTER' key button

Then the user waits for the order product edition preloader finish

Then the user checks the order products list contains entry
| name | units |quantity | retailPrice | totalSum |
| name-3268 | шт. | 5,7 | 100,00 | 570,00 |

Then the user checks the order total sum is 'Итого: 570,00 руб'

When the user clicks the save order button

When the user clicks on the order with number '10001' on the orders list page

When the user clicks the accept order button

Then the user checks values on the invoice page
| elementName | value |
| supplier | supplier-s32u68s1 |

Then the user checks the invoice products list contains exact entries
| name | units | quantity | price | totalSum | vatSum |
| name-3268 | шт. | 5,7 | 100,00 | 570,00 руб. | 0,00 руб. |

Then the user checks the invoice total sum is 'Итого: 570,00 руб.'

When the user inputs values on invoice page
| elementName | value |
| acceptanceDate | 02.04.2013 16:23 |
| accepter | accepter |
| supplierInvoiceNumber | supplierInvoiceNumber-1 |
| legalEntity | legalEntity |

When the user clicks the invoice cancel link button

Then the user sees no error messages

When the user clicks the local navigation invoice search link
And the user searches invoice by sku or supplier sku '10001'
And the user clicks the invoice search button and starts the search

Then the user checks the form results text is 'Мы не смогли найти накладную с номером 10001'

Given the user opens orders list page

Then the user checks the orders list contains exact entries
| number | supplier | date |
| 10001 | supplier-s32u68s1 | {todayDate} |

Scenario: Check order link navigation data on invoice page while invoice create

Meta:
@id_s32u68s3

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/sprint-32/us-68/aUsersPreconditionToStory.story,
              precondition/sprint-32/us-68/aPreconditionWithData.story

Given the user opens order create page
And the user logs in using 'departmentManager-s32u68' userName and 'lighthouse' password

When the user inputs values on order page
| elementName | value |
| supplier | supplier-s32u68s1 |

When the user inputs values on order page
| elementName | value |
| order product autocomplete | name-3268 |
And the user inputs quantity '5,7' on the order product with name 'name-3268'
And the user presses 'ENTER' key button

Then the user waits for the order product edition preloader finish

Then the user checks the order products list contains entry
| name | units |quantity | retailPrice | totalSum |
| name-3268 | шт. | 5,7 | 100,00 | 570,00 |

Then the user checks the order total sum is 'Итого: 570,00 руб'

When the user clicks the save order button

When the user clicks on the order with number '10001' on the orders list page

When the user clicks the accept order button

Then the user checks the invoice is formed by order

When the user clicks the ivoice order link

Then the user checks values on the invoice page
| elementName | value |
| supplier | supplier-s32u68s1 |

Then the user checks the order products list contains entry
| name | units |quantity | retailPrice | totalSum |
| name-3268 | шт. | 5,7 | 100,00 | 570,00 |

Then the user checks the order total sum is 'Итого: 570,00 руб'

Then the user checks the order accept button should be visible
And the user checks the delete order link button should be visible
And the user checks the download file link should be visible

Scenario: Check order link navigation data on invoice page after invoice creation

Meta:
@id_s32u68s4

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/sprint-32/us-68/aUsersPreconditionToStory.story,
              precondition/sprint-32/us-68/aPreconditionWithData.story

Given the user opens order create page
And the user logs in using 'departmentManager-s32u68' userName and 'lighthouse' password

When the user inputs values on order page
| elementName | value |
| supplier | supplier-s32u68s1 |

When the user inputs values on order page
| elementName | value |
| order product autocomplete | name-3268 |
And the user inputs quantity '5,7' on the order product with name 'name-3268'
And the user presses 'ENTER' key button

Then the user waits for the order product edition preloader finish

Then the user checks the order products list contains entry
| name | units |quantity | retailPrice | totalSum |
| name-3268 | шт. | 5,7 | 100,00 | 570,00 |

Then the user checks the order total sum is 'Итого: 570,00 руб'

When the user clicks the save order button

When the user clicks on the order with number '10001' on the orders list page

When the user clicks the accept order button

Then the user checks values on the invoice page
| elementName | value |
| supplier | supplier-s32u68s1 |

Then the user checks the invoice products list contains exact entries
| name | units | quantity | price | totalSum | vatSum |
| name-3268 | шт. | 5,7 | 100,00 | 570,00 руб. | 0,00 руб. |

Then the user checks the invoice total sum is 'Итого: 570,00 руб.'

When the user inputs values on invoice page
| elementName | value |
| acceptanceDate | 02.04.2013 16:23 |
| accepter | accepter |
| supplierInvoiceNumber | supplierInvoiceNumber-1 |
| legalEntity | legalEntity |

When the user accepts products and saves the invoice

Then the user sees no error messages

When the user clicks the local navigation invoice search link
And the user searches invoice by sku or supplier sku '10001'
And the user clicks the invoice search button and starts the search

When the user clicks on the search result invoice with number '10001'

Then the user checks the invoice is formed by order

When the user clicks the ivoice order link

Then the user checks values on the invoice page
| elementName | value |
| supplier | supplier-s32u68s1 |

Then the user checks the order products list contains entry
| name | units |quantity | retailPrice | totalSum |
| name-3268 | шт. | 5,7 | 100,00 | 570,00 |

Then the user checks the order total sum is 'Итого: 570,00 руб'

Then the user checks the order accept button should be not visible
And the user checks the delete order link button should be not visible
And the user checks the download file link should be not visible