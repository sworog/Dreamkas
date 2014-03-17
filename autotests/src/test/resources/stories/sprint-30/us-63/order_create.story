Meta:
@sprint_30
@us_63
@order

Narrative:
As a заведующий отделом
I want to зафиксировать в системе заказ поставщику
In order to чтобы потом принимать поставку на основании заказа

Scenario: Order create

Meta:
@id_s30u63s1
@order
@smoke

GivenStories: precondition/sprint-30/us-63/aPreconditionToStoryUs63.story

Given the user opens the authorization page
And the user logs in using 'departmentManager-s30u63' userName and 'lighthouse' password

When the user clicks the menu orders item
And the user clicks the create order link on order page menu navigation

When the user inputs values on order page
| elementName | value |
| supplier | supplier-s30u63s1 |
When the user inputs values in addition new product form on the order page
| elementName | value |
| name | name-3063 |
| quantity | 5 |

When the user clicks the add order product button

Then the user checks the order products list contains entry
| name | quantity | retailPrice | totalSum | inventory |

And the user checks the order total sum is '345,78'

When the user clicks the save order button

Then the user sees no error messages

Scenario: Verify product all data found by autocomplete in addittion form

Meta:
@id_s30u63s2
@smoke

GivenStories: precondition/sprint-30/us-63/aPreconditionToStoryUs63.story

Given The user opens order create page
And the user logs in using 'departmentManager-s30u63' userName and 'lighthouse' password

When the user inputs values in addition new product form on the order page
| elementName | value |
| name | name-3063 |
| quantity | 50 |

Then the user checks the filled autocomplete values in product addition form
| elementName | value |
| name | name-3063 |
| quantity | 5 |
| retailPrice | 100 |
| totalSum | 5 000 |
| inventory | 0 |

Scenario: Verify product inventory found by autocomplete in addittion form if there is invoice already

Meta:
@id_s30u63s3

GivenStories: precondition/sprint-30/us-63/aPreconditionToStoryUs63.story,
              precondition/sprint-30/us-63/aPreconditionToScenario3.story

Given The user opens order create page
And the user logs in using 'departmentManager-s30u63' userName and 'lighthouse' password

When the user inputs values in addition new product form on the order page
| elementName | value |
| name | name-3063-1 |

Then the user checks the filled autocomplete values in product addition form
| elementName | value |
| inventory | 12,456 |

Scenario: Verify product inventory found by autocomplete in addittion form if there is writeOff already

Meta:
@id_s30u63s4

GivenStories: precondition/sprint-30/us-63/aPreconditionToStoryUs63.story,
              precondition/sprint-30/us-63/aPreconditionToScenario4.story

Given The user opens order create page
And the user logs in using 'departmentManager-s30u63' userName and 'lighthouse' password

When the user inputs values in addition new product form on the order page
| elementName | value |
| name | name-3063-2 |

Then the user checks the filled autocomplete values in product addition form
| elementName | value |
| inventory | -43 |