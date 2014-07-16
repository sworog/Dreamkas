Meta:
@sprint_32
@us_68.1
@invoice

GivenStories: precondition/customPrecondition/symfonyEnvInitPrecondition.story

Scenario: Invoice download agreement button is not visible on invoice edit page if supplier doesnt have an agreement

Meta:
@id

GivenStories: precondition/sprint-32/us-68_1/aUsersPreconditionToStory.story

Given there is the supplier with name 'supplier-s32u681s1'
And there is the invoice in the store by 'departmentManager-s32u681'

Given the user opens last created invoice page
And the user logs in using 'departmentManager-s32u681' userName and 'lighthouse' password

When the user inputs values on invoice page
| elementName | value |
| supplier | supplier-s32u681s1 |

Then the user checks the download agreement button should be not visible on the invoice page

Scenario: Invoice download agreement button is not visible on invoice create page if supplier doesnt have an agreement

Meta:
@id

GivenStories: precondition/sprint-32/us-68_1/aUsersPreconditionToStory.story

Given there is the supplier with name 'supplier-s32u681s1'

Given the user opens the store 'store-s32u681' invoice create page
And the user logs in using 'departmentManager-s32u681' userName and 'lighthouse' password

When the user inputs values on invoice page
| elementName | value |
| supplier | supplier-s32u681s1 |

Then the user checks the download agreement button should be not visible on the invoice page

Scenario: Invoice download agreement button is visible on invoice create page if supplier has an agreement

Meta:
@id

GivenStories: precondition/sprint-32/us-68_1/aUsersPreconditionToStory.story

Given the user opens supplier create page
And the user logs in as 'commercialManager'

When the user inputs values on supplier page
| elementName | value |
| supplierName | supplier-s32u681s1-with-file |

When the user uploads file with name 'uploadFile123.docx' and with size of '300' kilobytes

Then the user waits for upload complete
And the user sees success message 'Файл успешно загружен'

Then the user asserts uploaded file name is expected

When the user clicks on the supplier create button

When the user logs out

Given the user opens the store 'store-s32u681' invoice create page
And the user logs in using 'departmentManager-s32u681' userName and 'lighthouse' password

When the user inputs values on invoice page
| elementName | value |
| supplier | supplier-s32u681s1-with-file |

Then the user checks the download agreement button should be visible on the invoice page

Scenario: Invoice download agreement button is visible on invoice edit page if supplier has an agreement

Meta:
@id

GivenStories: precondition/sprint-32/us-68_1/aUsersPreconditionToStory.story

Given there is the invoice in the store by 'departmentManager-s32u681'

Given the user opens supplier create page
And the user logs in as 'commercialManager'

When the user inputs values on supplier page
| elementName | value |
| supplierName | supplier-s32u681s1-with-file-2 |

When the user uploads file with name 'uploadFile123.docx' and with size of '300' kilobytes

Then the user waits for upload complete
And the user sees success message 'Файл успешно загружен'

Then the user asserts uploaded file name is expected

When the user clicks on the supplier create button

When the user logs out

Given the user opens last created invoice page
And the user logs in using 'departmentManager-s32u681' userName and 'lighthouse' password

When the user inputs values on invoice page
| elementName | value |
| supplier | supplier-s32u681s1-with-file-2 |

Then the user checks the download agreement button should be visible on the invoice page