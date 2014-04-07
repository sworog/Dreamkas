Meta:
@sprint_31
@us_63.1
@order

Narrative:
Когда нужно добавить товар в заказ,
Я хочу использовать название товара или локальный код в зависимости от ситуации и иметь возможность точно определить товар в системе,
Чтобы в заказ попали только нужные товары

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story

Scenario: Adding new order product with autocomplete by name

Meta:
@id_s31u63.1s1
@smoke

GivenStories: precondition/sprint-31/us-63_1/aPreconditionToStoryUs63.1.story,
              precondition/sprint-31/us-63_1/aPreconditionToPositiveScenarios.story

Given there is the product with 'сухое_молоко-30631' name, '30631_dry_milk' sku, '30631' barcode, 'unit' units, '100' purchasePrice of group named 'defaultGroup-s30u631', category named 'defaultCategory-s30u631', subcategory named 'defaultSubCategory-s30u631'

Given the user opens order create page
And the user logs in using 'departmentManager-s30u631' userName and 'lighthouse' password

When the user inputs values on order page
| elementName | value |
| supplier | supplier-s30u631s1 |
| order product autocomplete | сухое_молоко |
And the user presses 'ENTER' key button

Then the user waits for the order product edition preloader finish

Then the user checks the order products list contains entry
| name | units |quantity | retailPrice | totalSum |
| сухое_молоко-30631 | шт. | 1,0 | 100,00 | 100,00 |

When the user clicks the save order button

When the user clicks on the order with number '10001' on the orders list page

Then the user checks the order products list contains entry
| name | units |quantity | retailPrice | totalSum |
| сухое_молоко-30631 | шт. | 1,0 | 100,00 | 100,00 |

Scenario: Adding new order product with autocomplete by sku

Meta:
@id_s31u63.1s2
@smoke

GivenStories: precondition/sprint-31/us-63_1/aPreconditionToStoryUs63.1.story,
              precondition/sprint-31/us-63_1/aPreconditionToPositiveScenarios.story

Given there is the product with 'копыто_лошадиное_30631' name, '30631_horse_leg' sku, '30631' barcode, 'unit' units, '100' purchasePrice of group named 'defaultGroup-s30u631', category named 'defaultCategory-s30u631', subcategory named 'defaultSubCategory-s30u631'

Given the user opens order create page
And the user logs in using 'departmentManager-s30u631' userName and 'lighthouse' password

When the user inputs values on order page
| elementName | value |
| supplier | supplier-s30u631s1 |
| order product autocomplete | horse_leg |
And the user presses 'ENTER' key button

Then the user waits for the order product edition preloader finish

Then the user checks the order products list contains entry
| name | units |quantity | retailPrice | totalSum |
| копыто_лошадиное_30631 | шт. | 1,0 | 100,00 | 100,00 |

When the user clicks the save order button

When the user clicks on the order with number '10002' on the orders list page

Then the user checks the order products list contains entry
| name | units |quantity | retailPrice | totalSum |
| копыто_лошадиное_30631 | шт. | 1,0 | 100,00 | 100,00 |