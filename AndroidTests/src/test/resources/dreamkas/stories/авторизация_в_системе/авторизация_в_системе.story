Meta:
@android

Scenario: Пользователь успешно авторизуется в системе

Meta:
@test

Given пользователь авторизируется в системе используя адрес электронной почты 'owner@lighthouse.pro' и пароль 'lighthouse'

Then пользователь проверяет, что текущая активити это 'ru.crystals.vaverjanov.dreamkas.view.LighthouseDemoActivity_'
