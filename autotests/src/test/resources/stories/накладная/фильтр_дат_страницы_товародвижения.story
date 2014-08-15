Meta:
@sprint_39
@us_102

Narrative:
Как владелец,
Я хочу создавать, редактировать и удалять поступления товаров от поставщика в магазинах,
Чтобы остатки себестоимости товаров в учетной системе соответствовали действительности

Scenario: Накладная по дате выходит из фильтра дат

Meta:
@id_s39u102s18

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/sprint-39/us-102/aPreconditionToUserCreation.story,
              precondition/sprint-39/us-102/aPreconditionToTestDataCreation.story,
              precondition/sprint-39/us-102/aPreconditionToTestInvoiceCreation.story

Given пользователь открывает страницу товародвижения
And пользователь авторизуется в системе используя адрес электронной почты 's39u102@lighthouse.pro' и пароль 'lighthouse'

When пользователь вводит данные в поля на странице товародвижения
| elementName | value |
| dateFrom | 26.07.2014 |

Then пользователь ждет пока загрузится простой прелоадер

When пользователь вводит данные в поля на странице товародвижения
| elementName | value |
| dateTo | 27.07.2014 |

Then пользователь ждет пока загрузится простой прелоадер

Then пользователь проверяет, что на странице присутствует текст 'Не найдено ни одной операции с товарами.'

Scenario: Накладная по дате входит из фильтра дат

Meta:
@id_s39u102s19

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/sprint-39/us-102/aPreconditionToUserCreation.story,
              precondition/sprint-39/us-102/aPreconditionToTestDataCreation.story,
              precondition/sprint-39/us-102/aPreconditionToTestInvoiceCreation.story

Given пользователь открывает страницу товародвижения
And пользователь авторизуется в системе используя адрес электронной почты 's39u102@lighthouse.pro' и пароль 'lighthouse'

When пользователь вводит данные в поля на странице товародвижения
| elementName | value |
| dateTo | 29.07.2014 |

Then пользователь ждет пока загрузится простой прелоадер

When пользователь вводит данные в поля на странице товародвижения
| elementName | value |
| dateFrom | 27.07.2014 |

Then пользователь ждет пока загрузится простой прелоадер

Then пользователь проверяет конкретные операции на странице товародвижения
| date | type | status | store | sumTotal |
| 28.07.2014 | Приёмка | / не оплачена | В s39u102-store | 750,50 |

Scenario: Поля Фильтра дат равны дате накладной

Meta:
@id_s39u102s20

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/sprint-39/us-102/aPreconditionToUserCreation.story,
              precondition/sprint-39/us-102/aPreconditionToTestDataCreation.story,
              precondition/sprint-39/us-102/aPreconditionToTestInvoiceCreation.story

Given пользователь открывает страницу товародвижения
And пользователь авторизуется в системе используя адрес электронной почты 's39u102@lighthouse.pro' и пароль 'lighthouse'

When пользователь вводит данные в поля на странице товародвижения
| elementName | value |
| dateTo | 28.07.2014 |

Then пользователь ждет пока загрузится простой прелоадер

When пользователь вводит данные в поля на странице товародвижения
| elementName | value |
| dateFrom | 28.07.2014 |

Then пользователь ждет пока загрузится простой прелоадер

Then пользователь проверяет конкретные операции на странице товародвижения
| date | type | status | store | sumTotal |
| 28.07.2014 | Приёмка | / не оплачена | В s39u102-store | 750,50 |