Meta:
@sprint_31
@us_63.2
@order

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story

Scenario: Ending the order product edition by ENTER key press

Meta:
@id_s31u63.1s3
@smoke

GivenStories: precondition/sprint-31/us-63_1/aPreconditionToStoryUs63.1.story,
              precondition/sprint-31/us-63_1/aPreconditionToPrepareCatalog.story

Given there is the product with 'name-30631' name, '30631' barcode, 'unit' units, '100' purchasePrice of group named 'defaultGroup-s30u631', category named 'defaultCategory-s30u631', subcategory named 'defaultSubCategory-s30u631'

Given the user opens order create page
And the user logs in using 'departmentManager-s30u631' userName and 'lighthouse' password

When the user inputs values on order page
| elementName | value |
| order product autocomplete | name-30631 |

When the user presses 'ENTER' key button

Then the user waits for the order product edition preloader finish

Then the user checks the order products list contains entry
| name | units |quantity | retailPrice | totalSum |
| name-30631 | шт. | 1,0 | 100,00 | 100,00 |

Scenario: Ending the order product edition by focus change

Meta:
@id_s31u63.1s3
@smoke

GivenStories: precondition/sprint-31/us-63_1/aPreconditionToStoryUs63.1.story,
              precondition/sprint-31/us-63_1/aPreconditionToPrepareCatalog.story

Given there is the product with 'name-30631' name, '30631' barcode, 'unit' units, '100' purchasePrice of group named 'defaultGroup-s30u631', category named 'defaultCategory-s30u631', subcategory named 'defaultSubCategory-s30u631'

Given the user opens order create page
And the user logs in using 'departmentManager-s30u631' userName and 'lighthouse' password

When the user inputs values on order page
| elementName | value |
| order product autocomplete | name-30631 |

When the user focuses out on the order page

Then the user waits for the order product edition preloader finish

Then the user checks the order products list contains entry
| name | units |quantity | retailPrice | totalSum |
| name-30631 | шт. | 1,0 | 100,00 | 100,00 |