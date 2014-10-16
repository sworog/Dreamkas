Meta:
@release_0.44
@us_118.1

Narrative:
Как владелец,
Я хочу просматривать отчёт о продажах и прибыли по товарам внутри группы
Чтобы понимать, какие товары наиболее эффективны

Scenario: Проверка правильности отчёта по продажам и прибыли, когда продаж не было

Meta:
@smoke

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/отчеты/создание_юзера.story,
              precondition/отчеты/создание_магазина_с_товаром.story


Given пользователь открывает страницу отчета по продажам и прибыли
And пользователь авторизуется в системе используя адрес электронной почты 'reports@lighthouse.pro' и пароль 'lighthouse'
And пользователь кликает на группу 'reports-group1'
When пользователь* находится на странице 'странице отчета по продажам и прибыли по товарам внутри группы'
Then пользователь* проверяет, что список 'отчета по продажам и прибыли по товарам внутри группы' содержит точные данные
    | товар | продажи | себестоимость | прибыль | количество |
    | reports-product1 | 0,00 (0%) | 0,00 | 0,00 (0%) | 0,0 шт. |

