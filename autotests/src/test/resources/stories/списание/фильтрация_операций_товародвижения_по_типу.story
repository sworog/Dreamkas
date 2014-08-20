Meta:
@sprint_40
@us_103

Narrative:
Как владелец,
Я хочу создавать, редактировать и удалять списания товаров от поставщика в магазинах,
Чтобы остатки себестоимости товаров в учетной системе соответствовали действительности

Scenario: Фильтрация операций по типу Приемка на странице товародвижения

Meta:
@smoke
@id_s40u103typeFilters1

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/writeOff/aPreconditionToUserCreation.story,
              precondition/writeOff/aPreconditionToTestDataCreation.story,
              precondition/writeOff/aPreconditionForWriteOffEditionScenario.story,
              precondition/writeOff/aPreconditionToTestWriteOffCreation.story,
              precondition/writeOff/aPreconditionToTestInvoiceCreation.story

Given пользователь открывает страницу товародвижения
And пользователь авторизуется в системе используя адрес электронной почты 's40u103@lighthouse.pro' и пароль 'lighthouse'

Then пользователь проверяет конкретные операции на странице товародвижения
| date | type | status | store | sumTotal |
| 19.08.2014  | Списание | | Из s40u103-store | 23,98 |
| 28.07.2014  | Приёмка | / не оплачена | В s40u103-store | 750,00 |

When пользователь вводит данные в поля на странице товародвижения
| elementName | value |
| types | Приёмка |
And пользователь нажимает на кнопку Применить фильтры на странице товародвижения

Then пользователь проверяет конкретные операции на странице товародвижения
| date | type | status | store | sumTotal |
| 28.07.2014  | Приёмка | / не оплачена | В s40u103-store | 750,00 |

Scenario: Фильтрация операций по типу Списаний на странице товародвижения

Meta:
@smoke
@id_s40u103typeFilters2

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/writeOff/aPreconditionToUserCreation.story,
              precondition/writeOff/aPreconditionToTestDataCreation.story,
              precondition/writeOff/aPreconditionForWriteOffEditionScenario.story,
              precondition/writeOff/aPreconditionToTestWriteOffCreation.story,
              precondition/writeOff/aPreconditionToTestInvoiceCreation.story

Given пользователь открывает страницу товародвижения
And пользователь авторизуется в системе используя адрес электронной почты 's40u103@lighthouse.pro' и пароль 'lighthouse'

Then пользователь проверяет конкретные операции на странице товародвижения
| date | type | status | store | sumTotal |
| 19.08.2014  | Списание | | Из s40u103-store | 23,98 |
| 28.07.2014  | Приёмка | / не оплачена | В s40u103-store | 750,00 |

When пользователь вводит данные в поля на странице товародвижения
| elementName | value |
| types | Списание |
And пользователь нажимает на кнопку Применить фильтры на странице товародвижения

Then пользователь проверяет конкретные операции на странице товародвижения
| date | type | store | sumTotal |
| 19.08.2014  | Списание | Из s40u103-store | 23,98 |

Scenario: Сброс фильтров операций по типу на странице товародвижения

Meta:
@smoke
@id_s40u103typeFilters3

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/writeOff/aPreconditionToUserCreation.story,
              precondition/writeOff/aPreconditionToTestDataCreation.story,
              precondition/writeOff/aPreconditionForWriteOffEditionScenario.story,
              precondition/writeOff/aPreconditionToTestWriteOffCreation.story,
              precondition/writeOff/aPreconditionToTestInvoiceCreation.story

Given пользователь открывает страницу товародвижения
And пользователь авторизуется в системе используя адрес электронной почты 's40u103@lighthouse.pro' и пароль 'lighthouse'

Then пользователь проверяет конкретные операции на странице товародвижения
| date | type | status | store | sumTotal |
| 19.08.2014  | Списание | | Из s40u103-store | 23,98 |
| 28.07.2014  | Приёмка | / не оплачена | В s40u103-store | 750,00 |

When пользователь вводит данные в поля на странице товародвижения
| elementName | value |
| types | Списание |
And пользователь нажимает на кнопку Применить фильтры на странице товародвижения

Then пользователь проверяет конкретные операции на странице товародвижения
| date | type | store | sumTotal |
| 19.08.2014  | Списание | Из s40u103-store | 23,98 |

When пользователь нажимает на кнопку Сбросить фильтры на странице товародвижения

Then пользователь проверяет поля на странице товародвижения
| elementName | value |
| types | Все операции |

Then пользователь проверяет конкретные операции на странице товародвижения
| date | type | status | store | sumTotal |
| 19.08.2014  | Списание | | Из s40u103-store | 23,98 |
| 28.07.2014  | Приёмка | / не оплачена | В s40u103-store | 750,00 |