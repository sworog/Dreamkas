Meta:
@sprint_39
@us_108

Narrative:
Как владелец,
Я хочу создавать, редактировать и просматривать поставщиков торговой сети,
Чтобы контролировать закупки

Scenario: Supplier name is required

Meta:
@id_s39u108s9

GivenStories: precondition/sprint-39/us-108/aPreconditionToUserCreation.story

Given the user opens the supplier list page
And the user logs in using 's39u108@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the add new supplier create button
And the user clicks on add button on the create new supplier modal window

Then the user checks the create new supplier modal window 'name' field has error message with text 'Заполните это поле'

Scenario: Supplier name max symbols validation (101)

Meta:
@id_s39u108s10

GivenStories: precondition/sprint-39/us-108/aPreconditionToUserCreation.story

Given the user opens the supplier list page
And the user logs in using 's39u108@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the add new supplier create button
And the user generates symbols with count '101' in the create supplier modal window field with name 'name'
And the user clicks on add button on the create new supplier modal window

Then the user checks the create new supplier modal window 'name' field has error message with text 'Не более 100 символов'

Scenario: Supplier name max symbols validation (100)

Meta:
@id_s39u108s11

GivenStories: precondition/sprint-39/us-108/aPreconditionToUserCreation.story

Given the user opens the supplier list page
And the user logs in using 's39u108@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the add new supplier create button
And the user generates symbols with count '100' in the create supplier modal window field with name 'name'
And the user clicks on add button on the create new supplier modal window

Then the user waits for modal window closing

Then the user asserts that supplier list contain supplier with stored name

Scenario: Supplier address max symbols validation (301)

Meta:
@id_s39u108s12

GivenStories: precondition/sprint-39/us-107/aPreconditionToUserCreation.story

Given the user opens the supplier list page
And the user logs in using 's39u108@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the add new supplier create button
And the user inputs values on the create new supplier modal window
| elementName | value |
| name | s39u107val1 |
And the user generates symbols with count '301' in the create supplier modal window field with name 'address'
And the user clicks on add button on the create new supplier modal window

Then the user checks the create new supplier modal window 'address' field has error message with text 'Не более 300 символов'

Scenario: Supplier address max symbols validation (300)

Meta:
@id_s39u108s13

GivenStories: precondition/sprint-39/us-107/aPreconditionToUserCreation.story

Given the user opens the supplier list page
And the user logs in using 's39u108@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the add new supplier create button
And the user inputs values on the create new supplier modal window
| elementName | value |
| name | s39u107val2 |
And the user generates symbols with count '300' in the create supplier modal window field with name 'address'
And the user clicks on add button on the create new supplier modal window

Then the user waits for modal window closing

Then the user asserts that supplier list contain supplier with name 's39u107val2'

Scenario: Supplier phone max symbols validation (301)

Meta:
@id_s39u108s14

GivenStories: precondition/sprint-39/us-107/aPreconditionToUserCreation.story

Given the user opens the supplier list page
And the user logs in using 's39u108@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the add new supplier create button
And the user inputs values on the create new supplier modal window
| elementName | value |
| name | s39u107val3 |
And the user generates symbols with count '301' in the create supplier modal window field with name 'phone'
And the user clicks on add button on the create new supplier modal window

Then the user checks the create new supplier modal window 'phone' field has error message with text 'Не более 300 символов'

Scenario: Supplier phone max symbols validation (300)

Meta:
@id_s39u108s15

GivenStories: precondition/sprint-39/us-107/aPreconditionToUserCreation.story

Given the user opens the supplier list page
And the user logs in using 's39u108@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the add new supplier create button
And the user inputs values on the create new supplier modal window
| elementName | value |
| name | s39u107val4 |
And the user generates symbols with count '300' in the create supplier modal window field with name 'phone'
And the user clicks on add button on the create new supplier modal window

Then the user waits for modal window closing

Then the user asserts that supplier list contain supplier with name 's39u107val4'

Scenario: Supplier email max symbols validation (301)

Meta:
@@id_s39u108s16

GivenStories: precondition/sprint-39/us-107/aPreconditionToUserCreation.story

Given the user opens the supplier list page
And the user logs in using 's39u108@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the add new supplier create button
And the user inputs values on the create new supplier modal window
| elementName | value |
| name | s39u107val5 |
And the user generates symbols with count '301' in the create supplier modal window field with name 'email'
And the user clicks on add button on the create new supplier modal window

Then the user checks the create new supplier modal window 'email' field has error message with text 'Не более 300 символов'

Scenario: Supplier email max symbols validation (300)

Meta:
@id_s39u108s17

GivenStories: precondition/sprint-39/us-107/aPreconditionToUserCreation.story

Given the user opens the supplier list page
And the user logs in using 's39u108@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the add new supplier create button
And the user inputs values on the create new supplier modal window
| elementName | value |
| name | s39u107val6 |
And the user generates symbols with count '300' in the create supplier modal window field with name 'email'
And the user clicks on add button on the create new supplier modal window

Then the user waits for modal window closing

Then the user asserts that supplier list contain supplier with name 's39u107val6'

Scenario: Supplier contactPerson max symbols validation (301)

Meta:
@id_s39u108s18

GivenStories: precondition/sprint-39/us-107/aPreconditionToUserCreation.story

Given the user opens the supplier list page
And the user logs in using 's39u108@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the add new supplier create button
And the user inputs values on the create new supplier modal window
| elementName | value |
| name | s39u107val7 |
And the user generates symbols with count '301' in the create supplier modal window field with name 'contactPerson'
And the user clicks on add button on the create new supplier modal window

Then the user checks the create new supplier modal window 'contactPerson' field has error message with text 'Не более 300 символов'

Scenario: Supplier contactPerson max symbols validation (300)

Meta:
@id_s39u108s19

GivenStories: precondition/sprint-39/us-107/aPreconditionToUserCreation.story

Given the user opens the supplier list page
And the user logs in using 's39u108@lighthouse.pro' userName and 'lighthouse' password

When the user clicks on the add new supplier create button
And the user inputs values on the create new supplier modal window
| elementName | value |
| name | s39u107val8 |
And the user generates symbols with count '300' in the create supplier modal window field with name 'contactPerson'
And the user clicks on add button on the create new supplier modal window

Then the user waits for modal window closing

Then the user asserts that supplier list contain supplier with name 's39u107val8'