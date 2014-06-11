Meta:
@sprint_36
@us_77

Narrative:
Как владелец торговой точки,
Я хочу изменить данные своей учетной записи,
Чтобы держать доступ к аккаунту под контролем

Scenario: Email field validation negative

Meta:
@id_s36u77s15

Given the user runs the symfony:user:create command with params: generated email and common password

Given the user opens user edit page
And the user logs in using generated email and common password

When the user inputs value in the field with elementName 'email'
And the user inputs password 'lighthouse' on the user edit page
And the user clicks on save user data button

Then the user user sees errorMessage

Examples:
| value | errorMessage |
|  | Заполните это поле |
| test | Значение адреса электронной почты недопустимо. |
| @ | Значение адреса электронной почты недопустимо. |
| test@ | Значение адреса электронной почты недопустимо. |
| @io | Значение адреса электронной почты недопустимо. |
| @lighthouse.pro | Значение адреса электронной почты недопустимо. |
| .test@lighthouse.pro | Значение адреса электронной почты недопустимо. |
| test.@lighthouse.pro | Значение адреса электронной почты недопустимо. |
| test..lighthouse.pro | Значение адреса электронной почты недопустимо. |
| test_exa-mple.com | Значение адреса электронной почты недопустимо. |
| test\@test@lighthouse.pro | Значение адреса электронной почты недопустимо. |
| abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghiklmn@lighthouse.pro | Значение адреса электронной почты недопустимо. |
| test@abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghiklm.com | Значение адреса электронной почты недопустимо. |
| test@-lighthouse.pro | Значение адреса электронной почты недопустимо. |
| test@lighthouse-.com | Значение адреса электронной почты недопустимо. |
| test@.lighthouse.pro | Значение адреса электронной почты недопустимо. |
| test@lighthouse.pro. | Значение адреса электронной почты недопустимо. |
| test@lighthouse..com | Значение адреса электронной почты недопустимо. |
| abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghiklm@abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghikl.abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghikl.abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghij | Значение адреса электронной почты недопустимо. |
| a@abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghikl.abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghikl.abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghikl.abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefg.hij | Значение адреса электронной почты недопустимо. |
| a@abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghikl.abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghikl.abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghikl.abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefg.hijk | Значение адреса электронной почты недопустимо. |
| """@lighthouse.pro | Значение адреса электронной почты недопустимо. |
| "\"@lighthouse.pro | Значение адреса электронной почты недопустимо. |
| test"@lighthouse.pro | Значение адреса электронной почты недопустимо. |
| "test@lighthouse.pro | Значение адреса электронной почты недопустимо. |
| "test"test@lighthouse.pro | Значение адреса электронной почты недопустимо. |
| test"text"@lighthouse.pro | Значение адреса электронной почты недопустимо. |
| "test""test"@lighthouse.pro | Значение адреса электронной почты недопустимо. |
| "test␀"@lighthouse.pro | Значение адреса электронной почты недопустимо. |
| "test\␀"@lighthouse.pro | Значение адреса электронной почты недопустимо. |
| "abcdefghijklmnopqrstuvwxyz␠abcdefghijklmnopqrstuvwxyz␠abcdefghj"@lighthouse.pro | Значение адреса электронной почты недопустимо. |
| "abcdefghijklmnopqrstuvwxyz␠abcdefghijklmnopqrstuvwxyz␠abcdefg\h"@lighthouse.pro | Значение адреса электронной почты недопустимо. |
| (comment)test@lighthouse.pro | Значение адреса электронной почты недопустимо. |
| ((comment)test@lighthouse.pro | Значение адреса электронной почты недопустимо. |
| (comment(comment))test@lighthouse.pro | Значение адреса электронной почты недопустимо. |
| test@(comment)lighthouse.pro | Значение адреса электронной почты недопустимо. |
| test(comment)test@lighthouse.pro | Значение адреса электронной почты недопустимо. |
| test@(comment)[255.255.255.255] | Значение адреса электронной почты недопустимо. |
| (comment)abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghiklm@lighthouse.pro | Значение адреса электронной почты недопустимо. |
| test@(comment)abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghikl.com | Значение адреса электронной почты недопустимо. |
| (comment)test@abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghik.abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghik.abcdefghijklmnopqrstuvwxyzabcdefghijklmnopqrstuvwxyzabcdefghijk.abcdefghijklmnopqrstuvwxyzabcdefghijk.abcdefghijklmnopqrstu | Значение адреса электронной почты недопустимо. |
| test@lighthouse.pro␊ | Значение адреса электронной почты недопустимо. |
| test@lighthouse.pro- | Значение адреса электронной почты недопустимо. |
| "test@lighthouse.pro | Значение адреса электронной почты недопустимо. |
| (test@lighthouse.pro | Значение адреса электронной почты недопустимо. |
| test@(lighthouse.pro | Значение адреса электронной почты недопустимо. |
| test@[1.2.3.4 | Значение адреса электронной почты недопустимо. |
| "test\"@lighthouse.pro | Значение адреса электронной почты недопустимо. |
| (comment\)test@lighthouse.pro | Значение адреса электронной почты недопустимо. |
| test@lighthouse.pro(comment\) | Значение адреса электронной почты недопустимо. |
| test@lighthouse.pro(comment\ | Значение адреса электронной почты недопустимо. |
| test@[RFC-5322-domain-literal] | Значение адреса электронной почты недопустимо. |
| test@[RFC-5322]-domain-literal] | Значение адреса электронной почты недопустимо. |
| test@[RFC-5322-[domain-literal] | Значение адреса электронной почты недопустимо. |
| test@[RFC-5322-\]-domain-literal] | Значение адреса электронной почты недопустимо. |
| test@[RFC-5322-domain-literal\] | Значение адреса электронной почты недопустимо. |
| test@[RFC-5322-domain-literal\ | Значение адреса электронной почты недопустимо. |
| @lighthouse.pro | Значение адреса электронной почты недопустимо. |
| test@.org | Значение адреса электронной почты недопустимо. |
| "\"@lighthouse.pro | Значение адреса электронной почты недопустимо. |
| ()test@lighthouse.pro | Значение адреса электронной почты недопустимо. |

Scenario: Email field validation positive

Meta:
@id_s36u77s16

Given the user runs the symfony:user:create command with params: generated email and common password

Given the user opens user edit page
And the user logs in using generated email and common password

When the user inputs value in the field with elementName 'email'
And the user inputs password 'lighthouse' on the user edit page
And the user clicks on save user data button

Then the user asserts the element 'email' value is equal to value on user card page

Examples:
| value |
| test@lighthouse.pro |
| test@lighthouse.org.pro |
| test@about.lighthouse |
| a@lighthouse.pro |
| test@lighthouse.a |
| test.test@lighthouse.pro |
| !#$%&`*+/=?^`{}~@lighthouse.pro |
| 123@lighthouse.pro |
| test@123.lighthouse |
| test@lighthouse-eddystone.pro |
| test@c--n.lighthouse |
| test@lighthouse.lighthouse-pro |
| "test"@lighthouse.pro |
| ""@lighthouse.pro |
| "\a"@lighthouse.pro |
| "\""@lighthouse.pro |
| "\\"@lighthouse.pro |
| test@xn--hxajbheg2az3al.xn--jxalpdlp |
| xn--test@lighthouse.pro |