Meta:
@sprint_32
@us_68.1
@invoice

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story

Scenario: Check place holder title

Meta:
@id

GivenStories: precondition/sprint-32/us-68_1/aUsersPreconditionToStory.story

Given the user opens the store 'store-s32u681' invoice create page
And the user logs in using 'departmentManager-s32u681' userName and 'lighthouse' password

Then the user checks the autocomplete placeholder text is 'Для добавления позиции введите наименование товара или код'

Scenario: Adding invoice product by enter key press

Meta:
@id
@smoke

GivenStories: precondition/sprint-32/us-68_1/aUsersPreconditionToStory.story,
              precondition/sprint-32/us-68_1/aPreconditionWithDataToOrderCreateStory.story

Given there is the product with 'name-32681' name, '32681' sku, '32681' barcode, 'unit' units, '100' purchasePrice of group named 'defaultGroup-s32u681', category named 'defaultCategory-s32u681', subcategory named 'defaultSubCategory-s32u681'

Given the user opens the store 'store-s32u681' invoice create page
And the user logs in using 'departmentManager-s32u681' userName and 'lighthouse' password

When the user inputs values on invoice page
| elementName | value |
| invoice product autocomplete | !name-32681 |

Then the user checks the autocomplete result list contains exact entries
| result |
| 32681 — name-32681 |
And the user checks the autocomplete result entry found by name '32681 — name-32681' is highlighted

When the user presses 'ENTER' key button

When the user presses 'ENTER' key button

Then the user waits for the invoice product edition preloader finish

Then the user checks the invoice products list contains exact entries
| name | units |quantity | retailPrice | totalSum |
| name-32681 | шт. | 1,0 | 100,00 | 100,00 |

Scenario: Adding invoice product by keyboard arrow choosing result and confirming by enter key press

Meta:
@
@smoke

GivenStories: precondition/sprint-32/us-68_1/aUsersPreconditionToStory.story,
              precondition/sprint-32/us-68_1/aPreconditionWithDataToOrderCreateStory.story,
              precondition/sprint-32/us-68_1/aPreconditionForValidationScenario.story

Given the user opens the store 'store-s32u681' invoice create page
And the user logs in using 'departmentManager-s32u681' userName and 'lighthouse' password

When the user inputs values on invoice page
| elementName | value |
| invoice product autocomplete | !name-326812 |

Then the user checks the autocomplete result list contains exact entries
| result |
| 3268121 — name-3268121 |
| 3268122 — name-3268122 |
| 3268123 — name-3268123 |
| 3268124 — name-3268124 |
And the user checks the autocomplete result entry found by name '3268121 — name-3268121' is highlighted

When the user presses 'ARROW_DOWN' key button
And the user presses 'ARROW_DOWN' key button

Then the user checks the autocomplete result entry found by name '3268123 — name-3268123' is highlighted

When the user presses 'ENTER' key button

When the user presses 'ENTER' key button

Then the user waits for the invoice product edition preloader finish

Then the user checks the invoice products list contains exact entries
| name | units |quantity | retailPrice | totalSum |
| name-3268123 | шт. | 1,0 | 100,00 | 100,00 |

Scenario: Adding invoice product by keyboard arrow choosing result and click on it

Meta:
@
@smoke

GivenStories: precondition/sprint-32/us-68_1/aUsersPreconditionToStory.story,
              precondition/sprint-32/us-68_1/aPreconditionWithDataToOrderCreateStory.story,
              precondition/sprint-32/us-68_1/aPreconditionForValidationScenario.story

Given the user opens the store 'store-s32u681' invoice create page
And the user logs in using 'departmentManager-s32u681' userName and 'lighthouse' password

When the user inputs values on invoice page
| elementName | value |
| invoice product autocomplete | !name-326812 |

Then the user checks the autocomplete result list contains exact entries
| result |
| 3268121 — name-3268121 |
| 3268122 — name-3268122 |
| 3268123 — name-3268123 |
| 3268124 — name-3268124 |
And the user checks the autocomplete result entry found by name '3268121 — name-3268121' is highlighted

When the user presses 'ARROW_DOWN' key button
And the user presses 'ARROW_DOWN' key button

Then the user checks the autocomplete result entry found by name '3268123 — name-3268123' is highlighted

When the user clicks on the autocomplete result with name '3268123 — name-3268123'

When the user presses 'ENTER' key button

When the user presses 'ENTER' key button

Then the user waits for the invoice product edition preloader finish

Then the user checks the invoice products list contains exact entries
| name | units |quantity | retailPrice | totalSum |
| name-3268123 | шт. | 1,0 | 100,00 | 100,00 |

Scenario: Adding invoice product by moving mouse cursor to autocomplete result and click on it

Meta:
@

GivenStories: precondition/sprint-32/us-68_1/aUsersPreconditionToStory.story,
              precondition/sprint-32/us-68_1/aPreconditionWithDataToOrderCreateStory.story,
              precondition/sprint-32/us-68_1/aPreconditionForValidationScenario.story

Given the user opens the store 'store-s32u681' invoice create page
And the user logs in using 'departmentManager-s32u681' userName and 'lighthouse' password

When the user inputs values on invoice page
| elementName | value |
| invoice product autocomplete | !name-326812 |

Then the user checks the autocomplete result list contains exact entries
| result |
| 3268121 — name-3268121 |
| 3268122 — name-3268122 |
| 3268123 — name-3268123 |
| 3268124 — name-3268124 |
And the user checks the autocomplete result entry found by name '3268121 — name-3268121' is highlighted

When the user hovers mouse over autocomplete result entry found by name '3268123 — name-3268123'

Then the user checks the autocomplete result entry found by name '3268123 — name-3268123' is highlighted

When the user clicks on the autocomplete result with name '3268123 — name-3268123'

When the user presses 'ENTER' key button

When the user presses 'ENTER' key button

Then the user waits for the invoice product edition preloader finish

Then the user checks the invoice products list contains exact entries
| name | units |quantity | retailPrice | totalSum |
| name-3268123 | шт. | 1,0 | 100,00 | 100,00 |

Scenario: Adding invoice product by moving mouse cursor to autocomplete result and confirming it by pressing ENTER key button

Meta:
@
@smoke

GivenStories: precondition/sprint-32/us-68_1/aUsersPreconditionToStory.story,
              precondition/sprint-32/us-68_1/aPreconditionWithDataToOrderCreateStory.story,
              precondition/sprint-32/us-68_1/aPreconditionForValidationScenario.story

Given the user opens the store 'store-s32u681' invoice create page
And the user logs in using 'departmentManager-s32u681' userName and 'lighthouse' password

When the user inputs values on invoice page
| elementName | value |
| invoice product autocomplete | !name-326812 |

Then the user checks the autocomplete result list contains exact entries
| result |
| 3268121 — name-3268121 |
| 3268122 — name-3268122 |
| 3268123 — name-3268123 |
| 3268124 — name-3268124 |
And the user checks the autocomplete result entry found by name '3268121 — name-3268121' is highlighted

When the user hovers mouse over autocomplete result entry found by name '3268123 — name-3268123'

Then the user checks the autocomplete result entry found by name '3268123 — name-3268123' is highlighted

When the user presses 'ENTER' key button

When the user presses 'ENTER' key button

Then the user waits for the invoice product edition preloader finish

Then the user checks the invoice products list contains exact entries
| name | units |quantity | retailPrice | totalSum |
| name-3268123 | шт. | 1,0 | 100,00 | 100,00 |

Scenario: No search autocomplete results

Meta:
@

GivenStories: precondition/sprint-32/us-68_1/aUsersPreconditionToStory.story

Given the user opens the store 'store-s32u681' invoice create page
And the user logs in using 'departmentManager-s32u681' userName and 'lighthouse' password

When the user inputs values on invoice page
| elementName | value |
| invoice product autocomplete | !такоготоваранесущетвуте |

Then the user checks the autocomplete result list contains exact entries
| result |
| Нет результатов |

Scenario: Autocomplete minimum search symbols validation negative

Meta:
@id

Given there is the user with name 'departmentManager-s32u681', position 'departmentManager-s32u681', username 'departmentManager-s32u681', password 'lighthouse', role 'departmentManager'
And there is the store with number 'store-s32u681' managed by department manager named 'departmentManager-s32u681'

Given the user opens the store 'store-s32u681' invoice create page
And the user logs in using 'departmentManager-s32u681' userName and 'lighthouse' password

When the user inputs value in the 'invoice product autocomplete' invoice field

Then the user checks the autocomplete result list contains exact entries
| result |
| Для поиска введите более 2 символов |

Examples:
| value |
| !в |
| !вв |

Scenario: Autocomplete minimum search symbols validation positive

Meta:
@id_

GivenStories: precondition/sprint-32/us-68_1/aUsersPreconditionToStory.story,
              precondition/sprint-32/us-68_1/aPreconditionWithDataToOrderCreateStory.story

Given there is the product with 'csme-32681' name, 'csme-32681' sku, '32681' barcode, 'unit' units, '100' purchasePrice of group named 'defaultGroup-s32u681', category named 'defaultCategory-s32u681', subcategory named 'defaultSubCategory-s32u681'

Given the user opens the store 'store-s32u681' invoice create page
And the user logs in using 'departmentManager-s32u681' userName and 'lighthouse' password

When the user inputs values on invoice page
| elementName | value |
| invoice product autocomplete | !csm |

Then the user checks the autocomplete result list contains exact entries
| result |
| csme-32681 — csme-32681 |

Scenario: Check that invoice product is adding with quantity eqauls 1 by default

Meta:
@id_

GivenStories: precondition/sprint-32/us-68_1/aUsersPreconditionToStory.story,
              precondition/sprint-32/us-68_1/aPreconditionWithDataToOrderCreateStory.story

Given there is the product with 'name-32681' name, '32681' sku, '32681' barcode, 'unit' units, '100' purchasePrice of group named 'defaultGroup-s32u681', category named 'defaultCategory-s32u681', subcategory named 'defaultSubCategory-s32u681'

Given the user opens the store 'store-s32u681' invoice create page
And the user logs in using 'departmentManager-s32u681' userName and 'lighthouse' password

When the user inputs values on invoice page
| elementName | value |
| invoice product autocomplete | name-32681 |

When the user presses 'ENTER' key button

When the user presses 'ENTER' key button

Then the user waits for the invoice product edition preloader finish

Then the user checks the invoice products list contains exact entries
| name | units |quantity | retailPrice | totalSum |
| name-32681 | шт. | 1,0 | 100,00 | 100,00 |

Scenario: If Results are less than 3 no coincidencies bar is shown

Meta:
@id_

GivenStories: precondition/sprint-32/us-63_1/aPreconditionToStoryUs63.1.story,
              precondition/sprint-32/us-63_1/aPreconditionToPrepareCatalog.story,
              precondition/sprint-32/us-63_1/aPreconditionForIfResultsAreLessThan3NoCoincidenciesBarIsShownScenario.story

Given the user opens the store 'store-s32u681' invoice create page
And the user logs in using 'departmentManager-s32u681' userName and 'lighthouse' password

When the user inputs values on invoice page
| elementName | value |
| invoice product autocomplete | !big3 |

Then the user checks the autocomplete result list contains exact entries
| result |
| 32681-big31 — big31-32681 |
| 32681-big32 — big32-32681 |
| 32681-big33 — big33-32681 |

Scenario: Check there are only five results in autocomplete results

Meta:
@id_

GivenStories: precondition/sprint-32/us-63_1/aPreconditionToStoryUs63.1.story,
              precondition/sprint-32/us-63_1/aPreconditionToPrepareCatalog.story,
              precondition/sprint-32/us-63_1/aPreconditionForCgeckThereAreOnluFiveResultsInACResultsScenario.story

Given the user opens the store 'store-s32u681' invoice create page
And the user logs in using 'departmentManager-s32u681' userName and 'lighthouse' password

When the user inputs values on invoice page
| elementName | value |
| invoice product autocomplete | !kog3 |

Then the user checks the autocomplete result list contains exact entries
| result |
| 32681-kog31 — kog31-32681 |
| 32681-kog32 — kog32-32681 |
| 32681-kog33 — kog33-32681 |
| 32681-kog34 — kog34-32681 |
| 32681-kog35 — kog35-32681 |
| Еще 1 результат. Уточните запрос. |

Scenario: If Results are more than 5 coincidencies bar is shown

Meta:
@id_

GivenStories: precondition/sprint-32/us-63_1/aPreconditionToStoryUs63.1.story,
              precondition/sprint-32/us-63_1/aPreconditionToPrepareCatalog.story,
              precondition/sprint-32/us-63_1/aPreconditionForIfResultsAreMoreThan5CoincidenciesBarIsShownScenario.story

Given the user opens the store 'store-s32u681' invoice create page
And the user logs in using 'departmentManager-s32u681' userName and 'lighthouse' password

When the user inputs values on invoice page
| elementName | value |
| invoice product autocomplete | !log3 |

Then the user checks the autocomplete result list contains exact entries
| result |
| 32681-log31 — log31-32681 |
| 32681-log32 — log32-32681 |
| 32681-log33 — log33-32681 |
| 32681-log34 — log34-32681 |
| 32681-log35 — log35-32681 |
| Еще 13 результатов. Уточните запрос. |

When the user inputs values on invoice page
| elementName | value |
| invoice product autocomplete | !log312 |

Then the user checks the autocomplete result list contains exact entries
| result |
| 32681-log312 — log312-32681 |
| 32681-log3121 — log3121-32681 |
| 32681-log3122 — log3122-32681 |
| 32681-log3123 — log3123-32681 |
| 32681-log3124 — log3124-32681 |
| Еще 2 результата. Уточните запрос. |

Scenario: Check autocomplete result hightlight name

Meta:
@id_
@smoke

Given there is the user with name 'departmentManager-s32u681', position 'departmentManager-s32u681', username 'departmentManager-s32u681', password 'lighthouse', role 'departmentManager'
And there is the store with number 'store-s32u681' managed by department manager named 'departmentManager-s32u681'

Given there is the product with 'топленое_молоко-32681' name, '32681-small_milk' sku, '32681' barcode, 'unit' units, '100' purchasePrice of group named 'defaultGroup-s32u681', category named 'defaultCategory-s32u681', subcategory named 'defaultSubCategory-s32u681'

Given the user opens the store 'store-s32u681' invoice create page
And the user logs in using 'departmentManager-s32u681' userName and 'lighthouse' password

When the user inputs value in the 'invoice product autocomplete' invoice field

Then the user checks the autocomplete result entry found by name '32681-small_milk — топленое_молоко-32681' highlighted text is expectedValue

Examples:
| value | expectedValue |
| !топ | топ |
| !топл | топл |
| !топле | топле |
| !топленое | топленое |
| !топленое_молоко | топленое_молоко |

Scenario: Check autocomplete result hightlight sku

Meta:
@id_
@smoke

Given there is the user with name 'departmentManager-s32u681', position 'departmentManager-s32u681', username 'departmentManager-s32u681', password 'lighthouse', role 'departmentManager'
And there is the store with number 'store-s32u681' managed by department manager named 'departmentManager-s32u681'

Given there is the product with 'топленый_кефир-32681' name, '32681-small_milk_another' sku, '32681' barcode, 'unit' units, '100' purchasePrice of group named 'defaultGroup-s32u681', category named 'defaultCategory-s32u681', subcategory named 'defaultSubCategory-s32u681'

Given the user opens the store 'store-s32u681' invoice create page
And the user logs in using 'departmentManager-s32u681' userName and 'lighthouse' password

When the user inputs value in the 'invoice product autocomplete' invoice field

Then the user checks the autocomplete result entry found by name '32681-small_milk_another — топленый_кефир-32681' highlighted text is expectedValue

Examples:
| value | expectedValue |
| !sma | sma |
| !small_ | small_ |
| !small_milk | small_milk |
| !small_milk_ano | small_milk_ano |
| !32681-small_milk_another | 32681-small_milk_another |