Meta:
@sprint_29
@us_60.3
@supplier

Narrative:
As a категорийный менеджер
I want to удалить файл с текстом договора поставщика,
In order to не вводить в заблуждение сотрудников торговой сети, если договор потерял актуальность

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story

Scenario: Deleting file

Meta:
@smoke
@id_s29u60.3s1

Given the user opens supplier create page
And the user logs in as 'commercialManager'

When the user inputs values on supplier page
| elementName | value |
| supplierName | supplier-s29u60.3s1 |
And the user uploads file with name 'uploadFile.txt' and with size of '1560' kilobytes

Then the user waits for upload complete
And the user sees success message 'Файл успешно загружен'
And the user asserts uploaded file name is expected

When the user clicks on the supplier create button

When the user clicks on supplier list table element with name 'supplier-s29u60.3s1'

When the user clicks on the supplier upload delete button

Then the user asserts there is no file attached in supplier

When the user clicks on the supplier create button

Then the user asserts the download agreement button is not visible of supplier list item found by locator 'supplier-s29u60.3s1'

Scenario: Deleting file cancel

Meta:
@id_s29u60.3s2

Given the user opens supplier create page
And the user logs in as 'commercialManager'

When the user inputs values on supplier page
| elementName | value |
| supplierName | supplier-s29u60.3s2 |
And the user uploads file with name 'uploadFile.txt' and with size of '1560' kilobytes

Then the user waits for upload complete
And the user sees success message 'Файл успешно загружен'
And the user asserts uploaded file name is expected

When the user clicks on the supplier create button

When the user clicks on supplier list table element with name 'supplier-s29u60.3s2'

When the user clicks on the supplier upload delete button

Then the user asserts there is no file attached in supplier

When the user clicks on the supplier cancel button

When the user clicks on supplier list table element with name 'supplier-s29u60.3s2'

Then the user asserts uploaded file name is 'uploadFile.txt'

Scenario: Сheck the delete button is disabled

Meta:
@smoke
@id_s29u60.3s3

Given the user opens supplier create page
And the user logs in as 'commercialManager'

When the user inputs values on supplier page
| elementName | value |
| supplierName | supplier-s29u60.3s3 |
And the user uploads file with name 'uploadFile123.avi' and with size of '1600' kilobytes

Then the user waits for upload complete
And the user sees success message 'Файл успешно загружен'
And the user asserts uploaded file name is expected

When the user clicks on the supplier create button

When the user clicks on supplier list table element with name 'supplier-s29u60.3s3'

When the user uploads file with name 'uploadFile123.crt' and with size of '567' kilobytes

Then the user checks the upload delete button is disabled