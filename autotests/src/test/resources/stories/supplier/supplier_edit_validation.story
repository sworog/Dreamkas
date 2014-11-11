Meta:
@sprint_39
@us_108

Narrative:
Как владелец,
Я хочу создавать, редактировать и просматривать поставщиков торговой сети,
Чтобы контролировать закупки

Scenario: Edit supplier name is required

Meta:
@id_s39u108s19

GivenStories: precondition/sprint-39/us-108/aPreconditionToUserCreation.story

Given the user with email 's39u108@lighthouse.pro' creates supplier with name 's39u108supplierEditVal', address 'address', phone 'phone', email 'email', contactPerson 'contactPerson'

Given the user opens the supplier list page
And пользователь авторизуется в системе используя адрес электронной почты 's39u108@lighthouse.pro' и пароль 'lighthouse'

When the user clicks on the supplier with name 's39u108supplierEditVal'
And the user inputs values on the edit supplier modal window
| elementName | value |
| name |  |
And the user clicks on save button on the edit supplier modal window

Then the user checks the edit supplier modal window 'name' field has error message with text 'Заполните это поле'

Scenario: Edit supplier name max symbols validation (101)

Meta:
@id_s39u108s20

GivenStories: precondition/sprint-39/us-108/aPreconditionToUserCreation.story

Given the user with email 's39u108@lighthouse.pro' creates supplier with name 's39u108supplierEditVal1', address 'address', phone 'phone', email 'email', contactPerson 'contactPerson'

Given the user opens the supplier list page
And пользователь авторизуется в системе используя адрес электронной почты 's39u108@lighthouse.pro' и пароль 'lighthouse'

When the user clicks on the supplier with name 's39u108supplierEditVal1'
And the user generates symbols with count '101' in the edit supplier modal window field with name 'name'
And the user clicks on save button on the edit supplier modal window

Then the user checks the edit supplier modal window 'name' field has error message with text 'Не более 100 символов'

Scenario: Edit supplier name max symbols validation (100)

Meta:
@id_s39u108s21

GivenStories: precondition/sprint-39/us-108/aPreconditionToUserCreation.story

Given the user with email 's39u108@lighthouse.pro' creates supplier with name 's39u108supplierEditVal2', address 'address', phone 'phone', email 'email', contactPerson 'contactPerson'

Given the user opens the supplier list page
And пользователь авторизуется в системе используя адрес электронной почты 's39u108@lighthouse.pro' и пароль 'lighthouse'

When the user clicks on the supplier with name 's39u108supplierEditVal2'
And the user generates symbols with count '100' in the edit supplier modal window field with name 'name'
And the user clicks on save button on the edit supplier modal window

Then the user waits for modal window closing

Then the user asserts that supplier list contain supplier with stored name

Scenario: Edit supplier address max symbols validation (301)

Meta:
@id_s39u108s22

GivenStories: precondition/sprint-39/us-107/aPreconditionToUserCreation.story

Given the user with email 's39u108@lighthouse.pro' creates supplier with name 's39u108supplierEditVal3', address 'address', phone 'phone', email 'email', contactPerson 'contactPerson'

Given the user opens the supplier list page
And пользователь авторизуется в системе используя адрес электронной почты 's39u108@lighthouse.pro' и пароль 'lighthouse'

When the user clicks on the supplier with name 's39u108supplierEditVal3'
And the user generates symbols with count '301' in the edit supplier modal window field with name 'address'
And the user clicks on save button on the edit supplier modal window

Then the user checks the edit supplier modal window 'address' field has error message with text 'Не более 300 символов'

Scenario: Edit supplier address max symbols validation (300)

Meta:
@id_s39u108s23

GivenStories: precondition/sprint-39/us-107/aPreconditionToUserCreation.story

Given the user with email 's39u108@lighthouse.pro' creates supplier with name 's39u108supplierEditVal4', address 'address', phone 'phone', email 'email', contactPerson 'contactPerson'

Given the user opens the supplier list page
And пользователь авторизуется в системе используя адрес электронной почты 's39u108@lighthouse.pro' и пароль 'lighthouse'

When the user clicks on the supplier with name 's39u108supplierEditVal4'
And the user generates symbols with count '300' in the edit supplier modal window field with name 'address'
And the user clicks on save button on the edit supplier modal window

Then the user waits for modal window closing

Then the user asserts that supplier list contain supplier with name 's39u108supplierEditVal4'

Scenario: Edit supplier phone max symbols validation (301)

Meta:
@id_s39u108s24

GivenStories: precondition/sprint-39/us-107/aPreconditionToUserCreation.story

Given the user with email 's39u108@lighthouse.pro' creates supplier with name 's39u108supplierEditVal5', address 'address', phone 'phone', email 'email', contactPerson 'contactPerson'

Given the user opens the supplier list page
And пользователь авторизуется в системе используя адрес электронной почты 's39u108@lighthouse.pro' и пароль 'lighthouse'

When the user clicks on the supplier with name 's39u108supplierEditVal5'
And the user generates symbols with count '301' in the edit supplier modal window field with name 'phone'
And the user clicks on save button on the edit supplier modal window

Then the user checks the edit supplier modal window 'phone' field has error message with text 'Не более 300 символов'

Scenario: Edit supplier phone max symbols validation (300)

Meta:
@id_s39u108s25

GivenStories: precondition/sprint-39/us-107/aPreconditionToUserCreation.story

Given the user with email 's39u108@lighthouse.pro' creates supplier with name 's39u108supplierEditVal6', address 'address', phone 'phone', email 'email', contactPerson 'contactPerson'

Given the user opens the supplier list page
And пользователь авторизуется в системе используя адрес электронной почты 's39u108@lighthouse.pro' и пароль 'lighthouse'

When the user clicks on the supplier with name 's39u108supplierEditVal6'
And the user generates symbols with count '300' in the edit supplier modal window field with name 'phone'
And the user clicks on save button on the edit supplier modal window

Then the user waits for modal window closing

Then the user asserts that supplier list contain supplier with name 's39u108supplierEditVal6'

Scenario: Edit supplier email max symbols validation (301)

Meta:
@@id_s39u108s26

GivenStories: precondition/sprint-39/us-107/aPreconditionToUserCreation.story

Given the user with email 's39u108@lighthouse.pro' creates supplier with name 's39u108supplierEditVal7', address 'address', phone 'phone', email 'email', contactPerson 'contactPerson'

Given the user opens the supplier list page
And пользователь авторизуется в системе используя адрес электронной почты 's39u108@lighthouse.pro' и пароль 'lighthouse'

When the user clicks on the supplier with name 's39u108supplierEditVal7'
And the user generates symbols with count '301' in the edit supplier modal window field with name 'email'
And the user clicks on save button on the edit supplier modal window

Then the user checks the edit supplier modal window 'email' field has error message with text 'Не более 300 символов'

Scenario: Edit supplier email max symbols validation (300)

Meta:
@id_s39u108s27

GivenStories: precondition/sprint-39/us-107/aPreconditionToUserCreation.story

Given the user with email 's39u108@lighthouse.pro' creates supplier with name 's39u108supplierEditVal8', address 'address', phone 'phone', email 'email', contactPerson 'contactPerson'

Given the user opens the supplier list page
And пользователь авторизуется в системе используя адрес электронной почты 's39u108@lighthouse.pro' и пароль 'lighthouse'

When the user clicks on the supplier with name 's39u108supplierEditVal8'
And the user generates symbols with count '300' in the edit supplier modal window field with name 'email'
And the user clicks on save button on the edit supplier modal window

Then the user waits for modal window closing

Then the user asserts that supplier list contain supplier with name 's39u108supplierEditVal8'

Scenario: Edit supplier contactPerson max symbols validation (301)

Meta:
@id_s39u108s28

GivenStories: precondition/sprint-39/us-107/aPreconditionToUserCreation.story

Given the user with email 's39u108@lighthouse.pro' creates supplier with name 's39u108supplierEditVal9', address 'address', phone 'phone', email 'email', contactPerson 'contactPerson'

Given the user opens the supplier list page
And пользователь авторизуется в системе используя адрес электронной почты 's39u108@lighthouse.pro' и пароль 'lighthouse'

When the user clicks on the supplier with name 's39u108supplierEditVal9'
And the user generates symbols with count '301' in the edit supplier modal window field with name 'contactPerson'
And the user clicks on save button on the edit supplier modal window

Then the user checks the edit supplier modal window 'contactPerson' field has error message with text 'Не более 300 символов'

Scenario: Edit supplier contactPerson max symbols validation (300)

Meta:
@id_s39u108s29

GivenStories: precondition/sprint-39/us-107/aPreconditionToUserCreation.story

Given the user with email 's39u108@lighthouse.pro' creates supplier with name 's39u108supplierEditVal10', address 'address', phone 'phone', email 'email', contactPerson 'contactPerson'

Given the user opens the supplier list page
And пользователь авторизуется в системе используя адрес электронной почты 's39u108@lighthouse.pro' и пароль 'lighthouse'

When the user clicks on the supplier with name 's39u108supplierEditVal10'
And the user generates symbols with count '300' in the edit supplier modal window field with name 'contactPerson'
And the user clicks on save button on the edit supplier modal window

Then the user waits for modal window closing

Then the user asserts that supplier list contain supplier with name 's39u108supplierEditVal10'