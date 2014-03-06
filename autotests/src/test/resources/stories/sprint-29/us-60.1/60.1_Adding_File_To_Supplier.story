Meta:
@sprint_29
@us_60.1
@supplier

Narrative:
As a категорийный менеджер
I want to прикрепить файл с текстом договора к поставщику,
In order to в ЦО и в магазинах было возможно выяснить особенности работы с этим поставщиком

Scenario: Check adding agreement button is clickable

Meta:
@smoke
@id_s29u60.1s1

Given the user opens supplier create page
And the user logs in as 'commercialManager'

Then the user checks the upload agreement file buttton is clickable

Scenario: Adding agreement file to supplier

Meta:
@smoke
@id_s29u60.1s2

Given the user opens supplier create page
And the user logs in as 'commercialManager'

When the user inputs values on supplier page
| elementName | value |
| supplierName | supplier-s29u60.1s2 |

When the user uploads file with name 'uploadFile.txt' and with size of '1560' kilobytes

Then the user waits for upload complete
And the user sees success message 'Файл успешно загружен'

Then the user asserts uploaded file name is expected

When the user clicks on the supplier create button

Then the user asserts the download agreement button is visible of supplier list item found by locator 'supplier-s29u60.1s2'

Scenario: Adding agreement file to the supplier created page

Meta:
@id_s29u60.1s3

Given there is the supplier with name 'supplier-s29u60.1s3'
And the user navigates to supplier page with name 'supplier-s29u60.1s3'
And the user logs in as 'commercialManager'

When the user uploads file with name 'uploadFile.txt' and with size of '1120' kilobytes

Then the user waits for upload complete
And the user sees success message 'Файл успешно загружен'

Then the user asserts uploaded file name is expected

When the user clicks on the supplier create button

Then the user asserts the download agreement button is visible of supplier list item found by locator 'supplier-s29u60.1s3'

Scenario: Create supplier button is disabled while uploading

Meta:
@smoke
@id_s29u60.1s4

Given the user opens supplier page with random name
And the user logs in as 'commercialManager'

When the user uploads file with name 'uploadFile123.crt' and with size of '456' kilobytes

Then the user checks the supplier create button is disabled

Scenario: Upload button is disabled while uploading

Meta:
@smoke
@id_s29u60.1s5

Given the user opens supplier page with random name
And the user logs in as 'commercialManager'

When the user uploads file with name 'uploadFile123.crt' and with size of '589' kilobytes

Then the user checks the upload button is disabled

Scenario: Adding agreement file to the supplier created page and not save it

Meta:
@id_s29u60.1s6

Given there is the supplier with name 'supplier-s29u60.1s4'
And the user navigates to supplier page with name 'supplier-s29u60.1s4'
And the user logs in as 'commercialManager'

When the user uploads file with name 'uploadFile123.avi' and with size of '1600' kilobytes

Then the user waits for upload complete
And the user sees success message 'Файл успешно загружен'

Then the user asserts uploaded file name is expected

When the user clicks on the supplier cancel button

Then the user asserts the download agreement button is not visible of supplier list item found by locator 'supplier-s29u60.1s4'

When the user clicks on supplier list table element with name 'supplier-s29u60.1s4'

Then the user asserts there is no file attached in supplier

Scenario: No agreement button for supplier with no attached file on the supplier list

Meta:
@id_s29u60.1s7

Given there is the supplier with name 'supplier-s29u60.1s5'
And the user opens supplier list page
And the user logs in as 'commercialManager'

Then the user asserts the download agreement button is not visible of supplier list item found by locator 'supplier-s29u60.1s5'

Scenario: Supplier list Agreement button download is clickable

Meta:
@id_s29u60.1s8

Given the user opens supplier create page
And the user logs in as 'commercialManager'

When the user inputs values on supplier page
| elementName | value |
| supplierName | supplier-s29u60.1s6 |

When the user uploads file with name 'uploadFile123.doc' and with size of '252' kilobytes

Then the user waits for upload complete
And the user sees success message 'Файл успешно загружен'

Then the user asserts uploaded file name is expected

When the user clicks on the supplier create button

Then the user asserts the download agreement button is clickable of supplier list item found by locator 'supplier-s29u60.1s6'

Scenario: Supplier list Agreement button download is visible for supplier list item with file attached

Meta:
@id_s29u60.1s9

Given the user opens supplier create page
And the user logs in as 'commercialManager'

When the user inputs values on supplier page
| elementName | value |
| supplierName | supplier-s29u60.1s7 |

When the user uploads file with name 'uploadFile123.doc' and with size of '252' kilobytes

Then the user waits for upload complete
And the user sees success message 'Файл успешно загружен'

Then the user asserts uploaded file name is expected

When the user clicks on the supplier create button

Then the user asserts the download agreement button is visible of supplier list item found by locator 'supplier-s29u60.1s7'

Scenario: Adding file with the size more then 10 mb

Meta:
@id_s29u60.1s10

Given the user opens supplier create page
And the user logs in as 'commercialManager'

When the user inputs values on supplier page
| elementName | value |
| supplierName | supplier-s29u60.1s8 |

When the user uploads file with name 'uploadFile123.doc' and with size of '12200' kilobytes

Then the user waits for upload complete
And the user sees error messages
| error message |
| Размер файла должен быть меньше 10Мб |

Scenario: Adding empty file

Meta:
@id_s29u60.1s11

Given the user opens supplier create page
And the user logs in as 'commercialManager'

When the user inputs values on supplier page
| elementName | value |
| supplierName | supplier-s29u60.1s9 |

When the user uploads file with name 'uploadFile123.doc' and with size of '0' kilobytes

Then the user waits for upload complete
And the user sees success message 'Файл успешно загружен'

Then the user asserts uploaded file name is expected

Scenario: Download agreement file from supplier create page

Meta:
@smoke
@id_s29u60.1s12

Given the user opens supplier create page
And the user logs in as 'commercialManager'

When the user inputs values on supplier page
| elementName | value |
| supplierName | supplier-s29u60.1s10 |

When the user uploads file with name 'uploadFile12.jpeg' and with size of '1456' kilobytes

Then the user waits for upload complete
And the user sees success message 'Файл успешно загружен'

Then the user asserts uploaded file name is expected

Then the user asserts downloaded file is equals to uploaded file

Scenario: Download agreement file from the created supplier page

Meta:
@smoke
@id_s29u60.1s13

Given the user opens supplier create page
And the user logs in as 'commercialManager'

When the user inputs values on supplier page
| elementName | value |
| supplierName | supplier-s29u60.1s11 |

When the user uploads file with name 'uploadFile123.doc' and with size of '252' kilobytes

Then the user waits for upload complete
And the user sees success message 'Файл успешно загружен'

Then the user asserts uploaded file name is expected

When the user clicks on the supplier create button

When the user clicks on supplier list table element with name 'supplier-s29u60.1s11'

Then the user asserts downloaded file is equals to uploaded file

Scenario: Download agreement file from the supplier list

Meta:
@smoke
@id_s29u60.1s14

Given the user opens supplier create page
And the user logs in as 'commercialManager'

When the user inputs values on supplier page
| elementName | value |
| supplierName | supplier-s29u60.1s12 |

When the user uploads file with name 'uploadFile123.docx' and with size of '300' kilobytes

Then the user waits for upload complete
And the user sees success message 'Файл успешно загружен'

Then the user asserts uploaded file name is expected

When the user clicks on the supplier create button

Then the user asserts downloaded file is equals to uploaded file of supplier list item found by locator 'supplier-s29u60.1s12'

Scenario: Agreement file can be donwloaded by storeManager

Meta:
@smoke
@id_s29u60.1s15

Given the user opens supplier create page
And the user logs in as 'commercialManager'

When the user inputs values on supplier page
| elementName | value |
| supplierName | supplier-s29u60.1s13 |

When the user uploads file with name 'uploadFile123.docx' and with size of '300' kilobytes

Then the user waits for upload complete
And the user sees success message 'Файл успешно загружен'

Then the user asserts uploaded file name is expected

When the user clicks on the supplier create button

When the user logs out

Given the user opens supplier list page
And the user logs in as 'storeManager'

Then the user asserts the download agreement button is clickable of supplier list item found by locator 'supplier-s29u60.1s13'

Then the user asserts downloaded file is equals to uploaded file of supplier list item found by locator 'supplier-s29u60.1s13'

Scenario: Agreement file can be donwloaded by departmentManager

Meta:
@smoke
@id_s29u60.1s16

Given the user opens supplier create page
And the user logs in as 'commercialManager'

When the user inputs values on supplier page
| elementName | value |
| supplierName | supplier-s29u60.1s14 |

When the user uploads file with name 'uploadFile123.docx' and with size of '300' kilobytes

Then the user waits for upload complete
And the user sees success message 'Файл успешно загружен'

Then the user asserts uploaded file name is expected

When the user clicks on the supplier create button

When the user logs out

Given the user opens supplier list page
And the user logs in as 'departmentManager'

Then the user asserts the download agreement button is clickable of supplier list item found by locator 'supplier-s29u60.1s14'

Then the user asserts downloaded file is equals to uploaded file of supplier list item found by locator 'supplier-s29u60.1s14'

Scenario: Adding file with cyrillic name

Meta:
@id_s29u60.1s17

Given the user opens supplier create page
And the user logs in as 'commercialManager'

When the user inputs values on supplier page
| elementName | value |
| supplierName | supplier-s29u60.1s17 |

When the user uploads file with name 'ФайлСРуссимИменем.doc' and with size of '1234' kilobytes

Then the user waits for upload complete
And the user sees success message 'Файл успешно загружен'

Then the user asserts uploaded file name is expected

Scenario: Adding file with empty spaces

Meta:
@id_s29u60.1s18

Given the user opens supplier create page
And the user logs in as 'commercialManager'

When the user inputs values on supplier page
| elementName | value |
| supplierName | supplier-s29u60.1s18 |

When the user uploads file with name 'file with empty space.doc' and with size of '1234' kilobytes

Then the user waits for upload complete
And the user sees success message 'Файл успешно загружен'

Then the user asserts uploaded file name is expected


