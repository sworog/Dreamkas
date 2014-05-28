Meta:
@sprint_35
@us_74

Narrative:
Как владелец торговой сети,
Я хочу зарегестрировать аккаун в LH,
Чтобы иметь в систему свою учетную запись

Scenario: Email is unique

Meta:
@id_s35u74s11

Given the user opens lighthouse sign up page

When the user inputs 'test@lighthouse.pro' value in email field
And the user clicks on sign up button

Then the user checks the sign up text is 'Ваша учетная запись успешно создана. Для входа введите пароль присланный вам на емаил'

Given the user opens lighthouse sign up page

When the user inputs 'test@lighthouse.pro' value in email field
And the user clicks on sign up button

Then the user sees exact error messages
| error message |
| Пользователь с таким email уже существует |

Scenario: Email field validation negative

Meta:
@id_s35u74s12

Given the user opens lighthouse sign up page

When the user inputs value in email field
And the user clicks on sign up button

Then the user user sees errorMessage

Examples:
| value | errorMessage |
| test | Заполните поле |
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
| test@iana-.com | Значение адреса электронной почты недопустимо. |
| test@.lighthouse.pro | Значение адреса электронной почты недопустимо. |
| test@lighthouse.pro. | Значение адреса электронной почты недопустимо. |
| test@iana..com | Значение адреса электронной почты недопустимо. |
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
| "test"."test"@lighthouse.pro | Значение адреса электронной почты недопустимо. |
| "test".test@lighthouse.pro | Значение адреса электронной почты недопустимо. |
| "test␀"@lighthouse.pro | Значение адреса электронной почты недопустимо. |
| "test\␀"@lighthouse.pro | Значение адреса электронной почты недопустимо. |
| "abcdefghijklmnopqrstuvwxyz␠abcdefghijklmnopqrstuvwxyz␠abcdefghj"@lighthouse.pro | Значение адреса электронной почты недопустимо. |
| "abcdefghijklmnopqrstuvwxyz␠abcdefghijklmnopqrstuvwxyz␠abcdefg\h"@lighthouse.pro | Значение адреса электронной почты недопустимо. |
| test@a[255.255.255.255] | Значение адреса электронной почты недопустимо. |
| test@[255.255.255] | Значение адреса электронной почты недопустимо. |
| test@[255.255.255.255.255] | Значение адреса электронной почты недопустимо. |
| test@[255.255.255.256] | Значение адреса электронной почты недопустимо. |
| test@[1111:2222:3333:4444:5555:6666:7777:8888] | Значение адреса электронной почты недопустимо. |
| test@[IPv6:1111:2222:3333:4444:5555:6666:7777] | Значение адреса электронной почты недопустимо. |
| test@[IPv6:1111:2222:3333:4444:5555:6666:7777:8888:9999] | Значение адреса электронной почты недопустимо. |
| test@[IPv6:1111:2222:3333:4444:5555:6666:7777:888G] | Значение адреса электронной почты недопустимо. |
| test@[IPv6:1111:2222:3333:4444:5555:6666::7777:8888] | Значение адреса электронной почты недопустимо. |
| test@[IPv6::3333:4444:5555:6666:7777:8888] | Значение адреса электронной почты недопустимо. |
| test@[IPv6:1111::4444:5555::8888] | Значение адреса электронной почты недопустимо. |
| test@[IPv6:1111:2222:3333:4444:5555:255.255.255.255] | Значение адреса электронной почты недопустимо. |
| test@[IPv6:1111:2222:3333:4444:5555:6666:7777:255.255.255.255] | Значение адреса электронной почты недопустимо. |
| test@[IPv6:1111:2222:3333:4444:5555:6666::255.255.255.255] | Значение адреса электронной почты недопустимо. |
| test@[IPv6:1111:2222:3333:4444:::255.255.255.255] | Значение адреса электронной почты недопустимо. |
| test@[IPv6::255.255.255.255] | Значение адреса электронной почты недопустимо. |
| ␠test␠@lighthouse.pro | Значение адреса электронной почты недопустимо. |
| test@␠iana␠.com | Значение адреса электронной почты недопустимо. |
| test␠.␠test@lighthouse.pro | Значение адреса электронной почты недопустимо. |
| test␠.␠test@lighthouse.pro | Значение адреса электронной почты недопустимо. |
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
| test@[RFC-5322-\␇-domain-literal] | Значение адреса электронной почты недопустимо. |
| test@[RFC-5322-\␉-domain-literal] | Значение адреса электронной почты недопустимо. |
| test@[RFC-5322-\]-domain-literal] | Значение адреса электронной почты недопустимо. |
| test@[RFC-5322-domain-literal\] | Значение адреса электронной почты недопустимо. |
| test@[RFC-5322-domain-literal\ | Значение адреса электронной почты недопустимо. |
| test@[RFC␠5322␠domain␠literal] | Значение адреса электронной почты недопустимо. |
| test@[RFC-5322-domain-literal]␠(comment) | Значение адреса электронной почты недопустимо. |
| @lighthouse.pro | Значение адреса электронной почты недопустимо. |
| test@.org | Значение адреса электронной почты недопустимо. |
| ""@lighthouse.pro | Значение адреса электронной почты недопустимо. |
| "\"@lighthouse.pro | Значение адреса электронной почты недопустимо. |
| ()test@lighthouse.pro | Значение адреса электронной почты недопустимо. |
| test@lighthouse.pro␍ | Значение адреса электронной почты недопустимо. |
| ␇@lighthouse.pro | Значение адреса электронной почты недопустимо. |
| test@␇.org | Значение адреса электронной почты недопустимо. |
| "␇"@lighthouse.pro | Значение адреса электронной почты недопустимо. |

Scenario: Email field validation positive

Meta:
@id_s35u74s13

Given the user opens lighthouse sign up page

When the user inputs value in email field
And the user clicks on sign up button

Then the user checks the sign up text is 'Ваша учетная запись успешно создана. Для входа введите пароль присланный вам на емаил'
And the user asserts the element 'email' value is equal to value

Examples:
| value |
| test@io |
| test@lighthouse.pro |
| test@nominet.org.uk |
| test@about.museum |
| a@lighthouse.pro |
| test@e.com |
| test@iana.a |
| test.test@lighthouse.pro |
| !#$%&`*+/=?^`{|}~@lighthouse.pro |
| 123@lighthouse.pro |
| test@123.com |
| test@iana.123 |
| test@255.255.255.255 |
| test@mason-dixon.com |
| test@c--n.com |
| test@lighthouse.co-uk |
| "test"@lighthouse.pro |
| ""@lighthouse.pro |
| "\a"@lighthouse.pro |
| "\""@lighthouse.pro |
| "\\"@lighthouse.pro |
| "test\␠test"@lighthouse.pro |
| test@[255.255.255.255] |
| test@[IPv6:1111:2222:3333:4444:5555:6666:7777:8888] |
| test@[IPv6:1111:2222:3333:4444:5555:6666::8888] |
| test@[IPv6:1111:2222:3333:4444:5555::8888] |
| test@[IPv6:::3333:4444:5555:6666:7777:8888] |
| test@[IPv6:::] |
| test@[IPv6:1111:2222:3333:4444:5555:6666:255.255.255.255] |
| test@[IPv6:1111:2222:3333:4444::255.255.255.255] |
| test@xn--hxajbheg2az3al.xn--jxalpdlp |
| xn--test@lighthouse.pro |
| test@org |
| test@test.com |
| test@nic.no |
