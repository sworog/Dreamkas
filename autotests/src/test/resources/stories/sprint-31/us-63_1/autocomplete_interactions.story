Meta:
@sprint_31
@us_63.1
@order

Narrative:
Когда нужно добавить товар в заказ,
Я хочу использовать название товара или локальный код в зависимости от ситуации и иметь возможность точно определить товар в системе,
Чтобы в заказ попали только нужные товары

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story

Scenario: Adding order product by enter key press

Meta:
@id_s31u63.1s3
@smoke

GivenStories: precondition/sprint-31/us-63_1/aPreconditionToStoryUs63.1.story,
              precondition/sprint-31/us-63_1/aPreconditionToPrepareCatalog.story

Given there is the product with 'name-30631' name, '30631' sku, '30631' barcode, 'unit' units, '100' purchasePrice of group named 'defaultGroup-s30u631', category named 'defaultCategory-s30u631', subcategory named 'defaultSubCategory-s30u631'

Given the user opens order create page
And the user logs in using 'departmentManager-s30u631' userName and 'lighthouse' password

When the user inputs values on order page
| elementName | value |
| order product autocomplete | !name-30631 |

Then the user checks the autocomplete result list contains exact entries
| result |
| 30631 — name-30631 |
And the user checks the autocomplete result entry found by name '30631 — name-30631' is highlighted

When the user presses 'ENTER' key button

When the user presses 'ENTER' key button

Then the user waits for the order product edition preloader finish

Then the user checks the order products list contains entry
| name | units |quantity | retailPrice | totalSum |
| name-30631 | шт. | 1,0 | 100,00 | 100,00 |

Scenario: Adding order product by keyboard arrow choosing result and confirming by enter key press

Meta:
@id_s31u63.1s4
@smoke

GivenStories: precondition/sprint-31/us-63_1/aPreconditionToStoryUs63.1.story,
              precondition/sprint-31/us-63_1/aPreconditionToPrepareCatalog.story,
              precondition/sprint-31/us-63_1/aPreconditionForValidationScenario.story

Given the user opens order create page
And the user logs in using 'departmentManager-s30u631' userName and 'lighthouse' password

When the user inputs values on order page
| elementName | value |
| order product autocomplete | !name-306312 |

Then the user checks the autocomplete result list contains exact entries
| result |
| 3063121 — name-3063121 |
| 3063122 — name-3063122 |
| 3063123 — name-3063123 |
| 3063124 — name-3063124 |
And the user checks the autocomplete result entry found by name '3063121 — name-3063121' is highlighted

When the user presses 'ARROW_DOWN' key button
And the user presses 'ARROW_DOWN' key button

Then the user checks the autocomplete result entry found by name '3063123 — name-3063123' is highlighted

When the user presses 'ENTER' key button

When the user presses 'ENTER' key button

Then the user waits for the order product edition preloader finish

Then the user checks the order products list contains entry
| name | units |quantity | retailPrice | totalSum |
| name-3063123 | шт. | 1,0 | 100,00 | 100,00 |

Scenario: Adding order product by keyboard arrow choosing result and click on it

Meta:
@id_s31u63.1s5
@smoke

GivenStories: precondition/sprint-31/us-63_1/aPreconditionToStoryUs63.1.story,
              precondition/sprint-31/us-63_1/aPreconditionToPrepareCatalog.story,
              precondition/sprint-31/us-63_1/aPreconditionForValidationScenario.story

Given the user opens order create page
And the user logs in using 'departmentManager-s30u631' userName and 'lighthouse' password

When the user inputs values on order page
| elementName | value |
| order product autocomplete | !name-306312 |

Then the user checks the autocomplete result list contains exact entries
| result |
| 3063121 — name-3063121 |
| 3063122 — name-3063122 |
| 3063123 — name-3063123 |
| 3063124 — name-3063124 |
And the user checks the autocomplete result entry found by name '3063121 — name-3063121' is highlighted

When the user presses 'ARROW_DOWN' key button
And the user presses 'ARROW_DOWN' key button

Then the user checks the autocomplete result entry found by name '3063123 — name-3063123' is highlighted

When the user clicks on the autocomplete result with name '3063123 — name-3063123'

When the user presses 'ENTER' key button

When the user presses 'ENTER' key button

Then the user waits for the order product edition preloader finish

Then the user checks the order products list contains entry
| name | units |quantity | retailPrice | totalSum |
| name-3063123 | шт. | 1,0 | 100,00 | 100,00 |

Scenario: Adding order product by moving mouse cursor to autocomplete result and click on it

Meta:
@id_s31u63.1s6

GivenStories: precondition/sprint-31/us-63_1/aPreconditionToStoryUs63.1.story,
              precondition/sprint-31/us-63_1/aPreconditionToPrepareCatalog.story,
              precondition/sprint-31/us-63_1/aPreconditionForValidationScenario.story

Given the user opens order create page
And the user logs in using 'departmentManager-s30u631' userName and 'lighthouse' password

When the user inputs values on order page
| elementName | value |
| order product autocomplete | !name-306312 |

Then the user checks the autocomplete result list contains exact entries
| result |
| 3063121 — name-3063121 |
| 3063122 — name-3063122 |
| 3063123 — name-3063123 |
| 3063124 — name-3063124 |
And the user checks the autocomplete result entry found by name '3063121 — name-3063121' is highlighted

When the user hovers mouse over autocomplete result entry found by name '3063123 — name-3063123'

Then the user checks the autocomplete result entry found by name '3063123 — name-3063123' is highlighted

When the user clicks on the autocomplete result with name '3063123 — name-3063123'

When the user presses 'ENTER' key button

When the user presses 'ENTER' key button

Then the user waits for the order product edition preloader finish

Then the user checks the order products list contains entry
| name | units |quantity | retailPrice | totalSum |
| name-3063123 | шт. | 1,0 | 100,00 | 100,00 |

Scenario: Adding order product by moving mouse cursor to autocomplete result and confirming it by pressing ENTER key button

Meta:
@id_s31u63.1s7
@smoke

GivenStories: precondition/sprint-31/us-63_1/aPreconditionToStoryUs63.1.story,
              precondition/sprint-31/us-63_1/aPreconditionToPrepareCatalog.story,
              precondition/sprint-31/us-63_1/aPreconditionForValidationScenario.story

Given the user opens order create page
And the user logs in using 'departmentManager-s30u631' userName and 'lighthouse' password

When the user inputs values on order page
| elementName | value |
| order product autocomplete | !name-306312 |

Then the user checks the autocomplete result list contains exact entries
| result |
| 3063121 — name-3063121 |
| 3063122 — name-3063122 |
| 3063123 — name-3063123 |
| 3063124 — name-3063124 |
And the user checks the autocomplete result entry found by name '3063121 — name-3063121' is highlighted

When the user hovers mouse over autocomplete result entry found by name '3063123 — name-3063123'

Then the user checks the autocomplete result entry found by name '3063123 — name-3063123' is highlighted

When the user presses 'ENTER' key button

When the user presses 'ENTER' key button

Then the user waits for the order product edition preloader finish

Then the user checks the order products list contains entry
| name | units |quantity | retailPrice | totalSum |
| name-3063123 | шт. | 1,0 | 100,00 | 100,00 |

Scenario: No search autocomplete results

Meta:
@id_s31u63.1s8

GivenStories: precondition/sprint-31/us-63_1/aPreconditionToStoryUs63.1.story

Given the user opens order create page
And the user logs in using 'departmentManager-s30u631' userName and 'lighthouse' password

When the user inputs values on order page
| elementName | value |
| order product autocomplete | !такоготоваранесущетвуте |

Then the user checks the autocomplete result list contains exact entries
| result |
| Нет результатов |

Scenario: Autocomplete minimum search symbols validation negative

Meta:
@id

Given there is the user with name 'departmentManager-s30u631', position 'departmentManager-s30u631', username 'departmentManager-s30u631', password 'lighthouse', role 'departmentManager'
And there is the store with number 'store-s30u631' managed by department manager named 'departmentManager-s30u631'

Given the user opens order create page
And the user logs in using 'departmentManager-s30u631' userName and 'lighthouse' password

When the user inputs value in element 'order product autocomplete' on order page

Then the user checks the autocomplete result list contains exact entries
| result |
| Для поиска введите более 2 символов |

Examples:
| value |
| !в |
| !вв |

Scenario: Autocomplete minimum search symbols validation positive

Meta:
@id_s31u63.1s9

GivenStories: precondition/sprint-31/us-63_1/aPreconditionToStoryUs63.1.story,
              precondition/sprint-31/us-63_1/aPreconditionToPrepareCatalog.story

Given there is the product with 'csme-30631' name, 'csme-30631' sku, '30631' barcode, 'unit' units, '100' purchasePrice of group named 'defaultGroup-s30u631', category named 'defaultCategory-s30u631', subcategory named 'defaultSubCategory-s30u631'

Given the user opens order create page
And the user logs in using 'departmentManager-s30u631' userName and 'lighthouse' password

When the user inputs values on order page
| elementName | value |
| order product autocomplete | !csm |

Then the user checks the autocomplete result list contains exact entries
| result |
| csme-30631 — csme-30631 |

Scenario: Check that order product is adding with quantity eqauls 1 by default

Meta:
@id_s31u63.1s10

GivenStories: precondition/sprint-31/us-63_1/aPreconditionToStoryUs63.1.story,
              precondition/sprint-31/us-63_1/aPreconditionToPrepareCatalog.story

Given there is the product with 'name-30631' name, '30631' sku, '30631' barcode, 'unit' units, '100' purchasePrice of group named 'defaultGroup-s30u631', category named 'defaultCategory-s30u631', subcategory named 'defaultSubCategory-s30u631'

Given the user opens order create page
And the user logs in using 'departmentManager-s30u631' userName and 'lighthouse' password

When the user inputs values on order page
| elementName | value |
| order product autocomplete | name-30631 |

When the user presses 'ENTER' key button

When the user presses 'ENTER' key button

Then the user waits for the order product edition preloader finish

Then the user checks the order products list contains entry
| name | units |quantity | retailPrice | totalSum |
| name-30631 | шт. | 1,0 | 100,00 | 100,00 |

Scenario: If Results are less than 3 no coincidencies bar is shown

Meta:
@id_s31u63.1s11

GivenStories: precondition/sprint-31/us-63_1/aPreconditionToStoryUs63.1.story,
              precondition/sprint-31/us-63_1/aPreconditionToPrepareCatalog.story,
              precondition/sprint-31/us-63_1/aPreconditionForIfResultsAreLessThan3NoCoincidenciesBarIsShownScenario.story

Given the user opens order create page
And the user logs in using 'departmentManager-s30u631' userName and 'lighthouse' password

When the user inputs values on order page
| elementName | value |
| order product autocomplete | !big3 |

Then the user checks the autocomplete result list contains exact entries
| result |
| 30631-big31 — big31-30631 |
| 30631-big32 — big32-30631 |
| 30631-big33 — big33-30631 |

Scenario: Check there are only five results in autocomplete results

Meta:
@id_s31u63.1s12

GivenStories: precondition/sprint-31/us-63_1/aPreconditionToStoryUs63.1.story,
              precondition/sprint-31/us-63_1/aPreconditionToPrepareCatalog.story,
              precondition/sprint-31/us-63_1/aPreconditionForCgeckThereAreOnluFiveResultsInACResultsScenario.story

Given the user opens order create page
And the user logs in using 'departmentManager-s30u631' userName and 'lighthouse' password

When the user inputs values on order page
| elementName | value |
| order product autocomplete | !kog3 |

Then the user checks the autocomplete result list contains exact entries
| result |
| 30631-kog31 — kog31-30631 |
| 30631-kog32 — kog32-30631 |
| 30631-kog33 — kog33-30631 |
| 30631-kog34 — kog34-30631 |
| 30631-kog35 — kog35-30631 |
| Еще 1 результат. Уточните запрос. |

Scenario: If Results are more than 5 coincidencies bar is shown

Meta:
@id_s31u63.1s13

GivenStories: precondition/sprint-31/us-63_1/aPreconditionToStoryUs63.1.story,
              precondition/sprint-31/us-63_1/aPreconditionToPrepareCatalog.story,
              precondition/sprint-31/us-63_1/aPreconditionForIfResultsAreMoreThan5CoincidenciesBarIsShownScenario.story

Given the user opens order create page
And the user logs in using 'departmentManager-s30u631' userName and 'lighthouse' password

When the user inputs values on order page
| elementName | value |
| order product autocomplete | !log3 |

Then the user checks the autocomplete result list contains exact entries
| result |
| 30631-log31 — log31-30631 |
| 30631-log32 — log32-30631 |
| 30631-log33 — log33-30631 |
| 30631-log34 — log34-30631 |
| 30631-log35 — log35-30631 |
| Еще 13 результатов. Уточните запрос. |

When the user inputs values on order page
| elementName | value |
| order product autocomplete | !log312 |

Then the user checks the autocomplete result list contains exact entries
| result |
| 30631-log312 — log312-30631 |
| 30631-log3121 — log3121-30631 |
| 30631-log3122 — log3122-30631 |
| 30631-log3123 — log3123-30631 |
| 30631-log3124 — log3124-30631 |
| Еще 2 результата. Уточните запрос. |

Scenario: Check autocomplete result hightlight name

Meta:
@id_s31u63.1s14
@smoke

Given there is the user with name 'departmentManager-s30u631', position 'departmentManager-s30u631', username 'departmentManager-s30u631', password 'lighthouse', role 'departmentManager'
And there is the store with number 'store-s30u631' managed by department manager named 'departmentManager-s30u631'

Given there is the product with 'топленое_молоко-30631' name, '30631-small_milk' sku, '30631' barcode, 'unit' units, '100' purchasePrice of group named 'defaultGroup-s30u631', category named 'defaultCategory-s30u631', subcategory named 'defaultSubCategory-s30u631'

Given the user opens order create page
And the user logs in using 'departmentManager-s30u631' userName and 'lighthouse' password

When the user inputs value in element 'order product autocomplete' on order page

Then the user checks the autocomplete result entry found by name '30631-small_milk — топленое_молоко-30631' highlighted text is expectedValue

Examples:
| value | expectedValue |
| !топ | топ |
| !топл | топл |
| !топле | топле |
| !топленое | топленое |
| !топленое_молоко | топленое_молоко |

Scenario: Check autocomplete result hightlight sku

Meta:
@id_s31u63.1s15
@smoke

Given there is the user with name 'departmentManager-s30u631', position 'departmentManager-s30u631', username 'departmentManager-s30u631', password 'lighthouse', role 'departmentManager'
And there is the store with number 'store-s30u631' managed by department manager named 'departmentManager-s30u631'

Given there is the product with 'топленый_кефир-30631' name, '30631-small_milk_another' sku, '30631' barcode, 'unit' units, '100' purchasePrice of group named 'defaultGroup-s30u631', category named 'defaultCategory-s30u631', subcategory named 'defaultSubCategory-s30u631'

Given the user opens order create page
And the user logs in using 'departmentManager-s30u631' userName and 'lighthouse' password

When the user inputs value in element 'order product autocomplete' on order page

Then the user checks the autocomplete result entry found by name '30631-small_milk_another — топленый_кефир-30631' highlighted text is expectedValue

Examples:
| value | expectedValue |
| !sma | sma |
| !small_ | small_ |
| !small_milk | small_milk |
| !small_milk_ano | small_milk_ano |
| !30631-small_milk_another | 30631-small_milk_another |