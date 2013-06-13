
Scenario: alert number changesVerification stop edit button
Given there is the write off with number 'WriteOff-Alerts-1'
And the user navigates to the write off with number 'WriteOff-Alerts-1'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff number review' write off element to edit it
And the user inputs 'WriteOff Number-99' in the 'inline writeOff number' field on the write off page
And the user clicks finish edit button and ends the write off edition
Then the user checks alert text is equal to 'У вас есть несохранённые данные'
When the user clicks Cancel and discard changes
And the user clicks finish edit button and ends the write off edition
Then the user checks there is no alert on the page

Scenario: alert number changesVerification stop edit link
Given there is the write off with number 'WriteOff-Alerts-1'
And the user navigates to the write off with number 'WriteOff-Alerts-1'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff date review' write off element to edit it
And the user inputs 'todayDate' in the 'inline writeOff date' field on the write off page
And the user clicks finish edit link and ends the write off edition
Then the user checks alert text is equal to 'У вас есть несохранённые данные'
When the user clicks Cancel and discard changes
And the user clicks finish edit link and ends the write off edition
Then the user checks there is no alert on the page

Scenario: alert date changesVerification stop edit button
Given there is the write off with number 'WriteOff-Alerts-1'
And the user navigates to the write off with number 'WriteOff-Alerts-1'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff number review' write off element to edit it
And the user inputs 'WriteOff Number-99' in the 'inline writeOff number' field on the write off page
And the user clicks finish edit button and ends the write off edition
Then the user checks alert text is equal to 'У вас есть несохранённые данные'
When the user clicks Cancel and discard changes
And the user clicks finish edit button and ends the write off edition
Then the user checks there is no alert on the page

Scenario: alert date changesVerification stop edit link
Given there is the write off with number 'WriteOff-Alerts-1'
And the user navigates to the write off with number 'WriteOff-Alerts-1'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff date review' write off element to edit it
And the user inputs 'todayDate' in the 'inline writeOff date' field on the write off page
And the user clicks finish edit link and ends the write off edition
Then the user checks alert text is equal to 'У вас есть несохранённые данные'
When the user clicks Cancel and discard changes
And the user clicks finish edit link and ends the write off edition
Then the user checks there is no alert on the page

Scenario: alert productSku changesVerification stop edit button
Given there is the write off with number 'WriteOff-Alerts-2'
And the user navigates to the write off with number 'WriteOff-Alerts-2'
When the user clicks edit button and starts write off edition
And the user inputs '!неттакого' in the 'writeOff product sku autocomplete' field on the write off page
And the user clicks finish edit button and ends the write off edition
Then the user checks alert text is equal to 'У вас есть несохранённые данные'
When the user inputs '!' in the 'writeOff product sku autocomplete' field on the write off page
And the user clicks finish edit button and ends the write off edition
Then the user checks there is no alert on the page

Scenario: alert productSku changesVerification stop edit link
Given there is the write off with number 'WriteOff-Alerts-2'
And the user navigates to the write off with number 'WriteOff-Alerts-2'
When the user clicks edit button and starts write off edition
And the user inputs '!неттакого' in the 'writeOff product sku autocomplete' field on the write off page
And the user clicks finish edit link and ends the write off edition
Then the user checks alert text is equal to 'У вас есть несохранённые данные'
When the user inputs '!' in the 'writeOff product sku autocomplete' field on the write off page
And the user clicks finish edit link and ends the write off edition
Then the user checks there is no alert on the page

Scenario: alert productName changesVerification stop edit button
Given there is the write off with number 'WriteOff-Alerts-2'
And the user navigates to the write off with number 'WriteOff-Alerts-2'
When the user clicks edit button and starts write off edition
And the user inputs '!неттакого' in the 'writeOff product name autocomplete' field on the write off page
And the user clicks finish edit button and ends the write off edition
Then the user checks alert text is equal to 'У вас есть несохранённые данные'
When the user inputs '!' in the 'writeOff product name autocomplete' field on the write off page
And the user clicks finish edit button and ends the write off edition
Then the user checks there is no alert on the page

Scenario: alert productName changesVerification stop edit link
Given there is the write off with number 'WriteOff-Alerts-2'
And the user navigates to the write off with number 'WriteOff-Alerts-2'
When the user clicks edit button and starts write off edition
And the user inputs '!неттакого' in the 'writeOff product name autocomplete' field on the write off page
And the user clicks finish edit link and ends the write off edition
Then the user checks alert text is equal to 'У вас есть несохранённые данные'
When the user inputs '!' in the 'writeOff product name autocomplete' field on the write off page
And the user clicks finish edit link and ends the write off edition
Then the user checks there is no alert on the page

Scenario: alert productBarCode changesVerification stop edit button
Given there is the write off with number 'WriteOff-Alerts-2'
And the user navigates to the write off with number 'WriteOff-Alerts-2'
When the user clicks edit button and starts write off edition
And the user inputs '!неттакого' in the 'writeOff product barCode autocomplete' field on the write off page
And the user clicks finish edit button and ends the write off edition
Then the user checks alert text is equal to 'У вас есть несохранённые данные'
When the user inputs '!' in the 'writeOff product barCode autocomplete' field on the write off page
And the user clicks finish edit button and ends the write off edition
Then the user checks there is no alert on the page

Scenario: alert productBarCode changesVerification stop edit link
Given there is the write off with number 'WriteOff-Alerts-2'
And the user navigates to the write off with number 'WriteOff-Alerts-2'
When the user clicks edit button and starts write off edition
And the user inputs '!неттакого' in the 'writeOff product barCode autocomplete' field on the write off page
And the user clicks finish edit link and ends the write off edition
Then the user checks alert text is equal to 'У вас есть несохранённые данные'
When the user inputs '!' in the 'writeOff product barCode autocomplete' field on the write off page
And the user clicks finish edit link and ends the write off edition
Then the user checks there is no alert on the page

Scenario: alert productAmount changesVerification stop edit button
Given there is the write off with number 'WriteOff-Alerts-2'
And the user navigates to the write off with number 'WriteOff-Alerts-2'
When the user clicks edit button and starts write off edition
And the user inputs 'неттакого' in the 'writeOff product quantity' field on the write off page
And the user clicks finish edit button and ends the write off edition
Then the user checks alert text is equal to 'У вас есть несохранённые данные'
When the user inputs '' in the 'writeOff product quantity' field on the write off page
And the user clicks finish edit button and ends the write off edition
Then the user checks there is no alert on the page

Scenario: alert productAmount changesVerification stop edit link
Given there is the write off with number 'WriteOff-Alerts-2'
And the user navigates to the write off with number 'WriteOff-Alerts-2'
When the user clicks edit button and starts write off edition
And the user inputs 'неттакого' in the 'writeOff product quantity' field on the write off page
And the user clicks finish edit link and ends the write off edition
Then the user checks alert text is equal to 'У вас есть несохранённые данные'
When the user inputs '' in the 'writeOff product quantity' field on the write off page
And the user clicks finish edit link and ends the write off edition
Then the user checks there is no alert on the page

Scenario: alert productPrice changesVerification stop edit button
Given there is the write off with number 'WriteOff-Alerts-2'
And the user navigates to the write off with number 'WriteOff-Alerts-2'
When the user clicks edit button and starts write off edition
And the user inputs 'неттакого' in the 'writeOff product price' field on the write off page
And the user clicks finish edit button and ends the write off edition
Then the user checks alert text is equal to 'У вас есть несохранённые данные'
When the user inputs '' in the 'writeOff product price' field on the write off page
And the user clicks finish edit button and ends the write off edition
Then the user checks there is no alert on the page

Scenario: alert ProductPrice changesVerification stop edit link
Given there is the write off with number 'WriteOff-Alerts-2'
And the user navigates to the write off with number 'WriteOff-Alerts-2'
When the user clicks edit button and starts write off edition
And the user inputs 'неттакого' in the 'writeOff product price' field on the write off page
And the user clicks finish edit link and ends the write off edition
Then the user checks alert text is equal to 'У вас есть несохранённые данные'
When the user inputs '' in the 'writeOff product price' field on the write off page
And the user clicks finish edit link and ends the write off edition
Then the user checks there is no alert on the page

Scenario: alert productCause changesVerification stop edit button
Given there is the write off with number 'WriteOff-Alerts-2'
And the user navigates to the write off with number 'WriteOff-Alerts-2'
When the user clicks edit button and starts write off edition
And the user inputs 'неттакого' in the 'writeOff cause' field on the write off page
And the user clicks finish edit button and ends the write off edition
Then the user checks alert text is equal to 'У вас есть несохранённые данные'
When the user inputs '' in the 'writeOff cause' field on the write off page
And the user clicks finish edit button and ends the write off edition
Then the user checks there is no alert on the page

Scenario: alert ProductCause changesVerification stop edit link
Given there is the write off with number 'WriteOff-Alerts-2'
And the user navigates to the write off with number 'WriteOff-Alerts-2'
When the user clicks edit button and starts write off edition
And the user inputs 'неттакого' in the 'writeOff cause' field on the write off page
And the user clicks finish edit link and ends the write off edition
Then the user checks alert text is equal to 'У вас есть несохранённые данные'
When the user inputs '' in the 'writeOff cause' field on the write off page
And the user clicks finish edit link and ends the write off edition
Then the user checks there is no alert on the page

Scenario: alert writeOff product verification ProductBarCode stop edit button
Given there is the product with 'IE-IPA-1' name, 'IE-IPA-1' sku, 'IE-IPA-1' barcode, 'liter' units
And there is the write off with 'WriteOff-Alerts-10' number with product 'IE-IPE' with quantity '1', price '1' and cause 'Причина'
And the user navigates to the write off with number 'WriteOff-Alerts-10'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff product barCode review' write off element to edit it
And the user inputs 'IE-IPA-1' in the 'inline writeOff product barCode autocomplete' field on the write off page
And the user clicks finish edit button and ends the write off edition
Then the user checks alert text is equal to 'У вас есть несохранённые данные'
When the user clicks Cancel and discard changes
And the user clicks finish edit button and ends the write off edition
Then the user checks there is no alert on the page

Scenario: alert writeOff product verification ProductBarCode stop edit link
Given there is the product with 'IE-IPA-1' name, 'IE-IPA-1' sku, 'IE-IPA-1' barcode, 'liter' units
And there is the write off with 'WriteOff-Alerts-11' number with product 'IE-IPE' with quantity '1', price '1' and cause 'Причина'
And the user navigates to the write off with number 'WriteOff-Alerts-11'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff product barCode review' write off element to edit it
And the user inputs 'IE-IPA-1' in the 'inline writeOff product barCode autocomplete' field on the write off page
And the user clicks finish edit link and ends the write off edition
Then the user checks alert text is equal to 'У вас есть несохранённые данные'
When the user clicks Cancel and discard changes
And the user clicks finish edit link and ends the write off edition
Then the user checks there is no alert on the page

Scenario: alert writeOff product verification ProductName stop edit button
Given there is the product with 'IE-IPA-1' name, 'IE-IPA-1' sku, 'IE-IPA-1' barcode, 'liter' units
And there is the write off with 'WriteOff-Alerts-12' number with product 'IE-IPE' with quantity '1', price '1' and cause 'Причина'
And the user navigates to the write off with number 'WriteOff-Alerts-12'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff product name review' write off element to edit it
And the user inputs 'IE-IPA-1' in the 'inline writeOff product name autocomplete' field on the write off page
And the user clicks finish edit button and ends the write off edition
Then the user checks alert text is equal to 'У вас есть несохранённые данные'
When the user clicks Cancel and discard changes
And the user clicks finish edit button and ends the write off edition
Then the user checks there is no alert on the page

Scenario: alert writeOff product verification ProductName stop edit link
Given there is the product with 'IE-IPA-1' name, 'IE-IPA-1' sku, 'IE-IPA-1' barcode, 'liter' units
And there is the write off with 'WriteOff-Alerts-13' number with product 'IE-IPE' with quantity '1', price '1' and cause 'Причина'
And the user navigates to the write off with number 'WriteOff-Alerts-13'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff product name review' write off element to edit it
And the user inputs 'IE-IPA-1' in the 'inline writeOff product name autocomplete' field on the write off page
And the user clicks finish edit link and ends the write off edition
Then the user checks alert text is equal to 'У вас есть несохранённые данные'
When the user clicks Cancel and discard changes
And the user clicks finish edit link and ends the write off edition
Then the user checks there is no alert on the page

Scenario: alert writeOff product verification ProductSku stop edit button
Given there is the product with 'IE-IPA-1' name, 'IE-IPA-1' sku, 'IE-IPA-1' barcode, 'liter' units
And there is the write off with 'WriteOff-Alerts-14' number with product 'IE-IPE' with quantity '1', price '1' and cause 'Причина'
And the user navigates to the write off with number 'WriteOff-Alerts-14'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff product sku review' write off element to edit it
And the user inputs 'IE-IPA-1' in the 'inline writeOff product sku autocomplete' field on the write off page
And the user clicks finish edit button and ends the write off edition
Then the user checks alert text is equal to 'У вас есть несохранённые данные'
When the user clicks Cancel and discard changes
And the user clicks finish edit button and ends the write off edition
Then the user checks there is no alert on the page

Scenario: alert writeOff product verification ProductSku stop edit link
Given there is the product with 'IE-IPA-1' name, 'IE-IPA-1' sku, 'IE-IPA-1' barcode, 'liter' units
And there is the write off with 'WriteOff-Alerts-15' number with product 'IE-IPE' with quantity '1', price '1' and cause 'Причина'
And the user navigates to the write off with number 'WriteOff-Alerts-15'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff product sku review' write off element to edit it
And the user inputs 'IE-IPA-1' in the 'inline writeOff product sku autocomplete' field on the write off page
And the user clicks finish edit link and ends the write off edition
Then the user checks alert text is equal to 'У вас есть несохранённые данные'
When the user clicks Cancel and discard changes
And the user clicks finish edit link and ends the write off edition
Then the user checks there is no alert on the page

Scenario: alert writeOff product verification productAmount stop edit button
Given there is the write off with 'WriteOff-Alerts-16' number with product 'IE-IPE' with quantity '1', price '1' and cause 'Причина'
And the user navigates to the write off with number 'WriteOff-Alerts-16'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff product quantity review' write off element to edit it
And the user inputs '2' in the 'inline writeOff product quantity' field on the write off page
And the user clicks finish edit button and ends the write off edition
Then the user checks alert text is equal to 'У вас есть несохранённые данные'
When the user clicks Cancel and discard changes
And the user clicks finish edit button and ends the write off edition
Then the user checks there is no alert on the page

Scenario: alert writeOff product verification productAmount stop edit link
Given there is the write off with 'WriteOff-Alerts-17' number with product 'IE-IPE' with quantity '1', price '1' and cause 'Причина'
And the user navigates to the write off with number 'WriteOff-Alerts-17'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff product quantity review' write off element to edit it
And the user inputs '2' in the 'inline writeOff product quantity' field on the write off page
And the user clicks finish edit link and ends the write off edition
Then the user checks alert text is equal to 'У вас есть несохранённые данные'
When the user clicks Cancel and discard changes
And the user clicks finish edit link and ends the write off edition
Then the user checks there is no alert on the page

Scenario: alert writeOff product verification ProductPrice stop edit button
Given there is the write off with 'WriteOff-Alerts-18' number with product 'IE-IPE' with quantity '1', price '1' and cause 'Причина'
And the user navigates to the write off with number 'WriteOff-Alerts-18'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff product price review' write off element to edit it
And the user inputs '2' in the 'inline writeOff product price' field on the write off page
And the user clicks finish edit button and ends the write off edition
Then the user checks alert text is equal to 'У вас есть несохранённые данные'
When the user clicks Cancel and discard changes
And the user clicks finish edit button and ends the write off edition
Then the user checks there is no alert on the page

Scenario: alert writeOff product verification ProductPrice stop edit link
Given there is the write off with 'WriteOff-Alerts-19' number with product 'IE-IPE' with quantity '1', price '1' and cause 'Причина'
And the user navigates to the write off with number 'WriteOff-Alerts-19'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff product price review' write off element to edit it
And the user inputs '2' in the 'inline writeOff product price' field on the write off page
And the user clicks finish edit link and ends the write off edition
Then the user checks alert text is equal to 'У вас есть несохранённые данные'
When the user clicks Cancel and discard changes
And the user clicks finish edit link and ends the write off edition
Then the user checks there is no alert on the page

Scenario: alert writeOff product verification ProductCause stop edit button
Given there is the write off with 'WriteOff-Alerts-20' number with product 'IE-IPE' with quantity '1', price '1' and cause 'Причина'
And the user navigates to the write off with number 'WriteOff-Alerts-20'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff cause review' write off element to edit it
And the user inputs '2' in the 'inline writeOff cause' field on the write off page
And the user clicks finish edit button and ends the write off edition
Then the user checks alert text is equal to 'У вас есть несохранённые данные'
When the user clicks Cancel and discard changes
And the user clicks finish edit button and ends the write off edition
Then the user checks there is no alert on the page

Scenario: alert writeOff product verification ProductCause stop edit link
Given there is the write off with 'WriteOff-Alerts-21' number with product 'IE-IPE' with quantity '1', price '1' and cause 'Причина'
And the user navigates to the write off with number 'WriteOff-Alerts-21'
When the user clicks edit button and starts write off edition
And the user clicks on 'writeOff cause review' write off element to edit it
And the user inputs '2' in the 'inline writeOff cause' field on the write off page
And the user clicks finish edit link and ends the write off edition
Then the user checks alert text is equal to 'У вас есть несохранённые данные'
When the user clicks Cancel and discard changes
And the user clicks finish edit link and ends the write off edition
Then the user checks there is no alert on the page

