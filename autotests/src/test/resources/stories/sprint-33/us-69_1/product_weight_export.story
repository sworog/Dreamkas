Meta:
@us_69.1
@sprint_33

Narrative:
Как комерческий директор
Я хочу выгрузить весовой и штучный товар на кассу
Что бы продовать товары

Scenario: Export weight product to centrum (xml)

Meta:
@id_s33u69.1s1
@smoke

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/sprint-33/us-69_1/aPreconditionWithData.story

Given the user opens the settings page
And the user logs in as 'watchman'
When the user input values on the setting page
| elementName | value |
| set10-integration-url | smb://faro.lighthouse.pro/centrum/autotests |
| set10-integration-login | erp |
| set10-integration-password | erp |
And the user clicks save button on the setting page
Then the user sees success message 'Настройки успешно сохранены'
When the user logs out

Given the user opens catalog page
And the user logs in as 'commercialManager'
When the user clicks on products export link
Given the user opens the log page
Then the user checks the last set10 export log title is 'Выгрузка товаров в Set Retail 10'
Then the user waits for the last set10 export log message success status
And the user checks the last set10 export log status text is 'выполнено'

Then the robot checks xml file in autotests folder for equals fixture file 'fixtures/products/productWeightExport.xml'


Scenario: Export piece product to centrum (xml)

Meta:
@id_s33u69.1s2
@smoke

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story,
              precondition/sprint-33/us-69_1/aPreconditionWithDataS2.story

Given the user opens the settings page
And the user logs in as 'watchman'
When the user input values on the setting page
| elementName | value |
| set10-integration-url | smb://faro.lighthouse.pro/centrum/autotests |
| set10-integration-login | erp |
| set10-integration-password | erp |
And the user clicks save button on the setting page
Then the user sees success message 'Настройки успешно сохранены'
When the user logs out

Given the user opens catalog page
And the user logs in as 'commercialManager'
When the user clicks on products export link
Given the user opens the log page
Then the user checks the last set10 export log title is 'Выгрузка товаров в Set Retail 10'
Then the user waits for the last set10 export log message success status
And the user checks the last set10 export log status text is 'выполнено'

Then the robot checks xml file in autotests folder for equals fixture file 'fixtures/products/productPieceExport.xml'
