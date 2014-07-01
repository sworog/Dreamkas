Meta:
@sprint_29
@sprint_37
@us_60.2
@us_81.1
@supplier

Narrative:
As a категорийный менеджер
I want to заменить файл с текстом договора поставщика,
In order to в системы всегда был актуальный договор с поставщиком

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story

Scenario: Check replacing agreement button is clickable

Meta:
@smoke
@id_s29u60.2s1

Given the user opens supplier create page
And the user logs in as 'owner'

When the user inputs values on supplier page
| elementName | value |
| supplierName | supplier-s29u60.2s1 |
And the user uploads file with name 'uploadFile123.avi' and with size of '1600' kilobytes

Then the user waits for upload complete
And the user sees success message 'Файл успешно загружен'
And the user asserts uploaded file name is expected

When the user clicks on the supplier create button

When the user clicks on supplier list table element with name 'supplier-s29u60.2s1'

Then the user checks the replace agreement file buttton is clickable

Scenario: Replace button is disabled while uploading

Meta:
@smoke
@id_s29u60.2s2

Given the user opens supplier create page
And the user logs in as 'owner'

When the user inputs values on supplier page
| elementName | value |
| supplierName | supplier-s29u60.2s2 |
And the user uploads file with name 'uploadFile123.avi' and with size of '1600' kilobytes

Then the user waits for upload complete
And the user sees success message 'Файл успешно загружен'
And the user asserts uploaded file name is expected

When the user clicks on the supplier create button

When the user clicks on supplier list table element with name 'supplier-s29u60.2s2'

When the user uploads file with name 'uploadFile123.crt' and with size of '567' kilobytes

Then the user checks the replace upload button is disabled

Scenario: Adding files with the same name

Meta:
@id_s29u60.2s3

Given the user opens supplier create page
And the user logs in as 'owner'

When the user inputs values on supplier page
| elementName | value |
| supplierName | supplier-s29u60.2s3 |
And the user uploads file with name 'uploadFile.txt' and with size of '1560' kilobytes

Then the user waits for upload complete
And the user sees success message 'Файл успешно загружен'
And the user asserts uploaded file name is expected

When the user clicks on the supplier create button

When the user clicks on supplier list table element with name 'supplier-s29u60.2s3'

When the user uploads file with name 'uploadFile.txt' and with size of '2100' kilobytes

Then the user waits for upload complete
And the user sees success message 'Файл успешно загружен'
And the user asserts uploaded file name is expected

When the user clicks on the supplier create button

Then the user sees no error messages

Scenario: Replacing file

Meta:
@id_s29u60.2s4

Given the user opens supplier create page
And the user logs in as 'owner'

When the user inputs values on supplier page
| elementName | value |
| supplierName | supplier-s29u60.2s4 |
And the user uploads file with name 'uploadFile.txt' and with size of '1560' kilobytes

Then the user waits for upload complete
And the user sees success message 'Файл успешно загружен'
And the user asserts uploaded file name is expected

When the user clicks on the supplier create button

When the user clicks on supplier list table element with name 'supplier-s29u60.2s4'

When the user uploads file with name 'uploadFileNew.txt' and with size of '2100' kilobytes

Then the user waits for upload complete
And the user sees success message 'Файл успешно загружен'
And the user asserts uploaded file name is expected

When the user clicks on the supplier create button

Then the user sees no error messages

Scenario: Replacing file cancel

Meta:
@id_s29u60.2s5

Given the user opens supplier create page
And the user logs in as 'owner'

When the user inputs values on supplier page
| elementName | value |
| supplierName | supplier-s29u60.2s5 |
And the user uploads file with name 'uploadFile.txt' and with size of '1560' kilobytes

Then the user waits for upload complete
And the user sees success message 'Файл успешно загружен'
And the user asserts uploaded file name is expected

When the user clicks on the supplier create button

When the user clicks on supplier list table element with name 'supplier-s29u60.2s5'

When the user uploads file with name 'uploadFileNew.txt' and with size of '2100' kilobytes

Then the user waits for upload complete
And the user sees success message 'Файл успешно загружен'
And the user asserts uploaded file name is expected

When the user clicks on the supplier cancel button

When the user clicks on supplier list table element with name 'supplier-s29u60.2s5'

Then the user asserts uploaded file name is 'uploadFile.txt'