package project.lighthouse.autotests.jbehave.stockMovement;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Alias;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.model.ExamplesTable;
import org.json.JSONException;
import project.lighthouse.autotests.steps.stockMovement.StockMovementSteps;

public class ThenStockMovementUserSteps {

    @Steps
    StockMovementSteps stockMovementSteps;

    @Then("the user asserts stock movement operations on the stock movement page $examplesTable")
    @Alias("пользователь проверяет операции на странице товародвижения $examplesTable")
    public void thenTheUserAssertsOperationOmTheStockMovementPage(ExamplesTable examplesTable) {
        stockMovementSteps.stockMovementPageContainInvoice(examplesTable);
    }

    @Then("пользователь проверяет конкретные операции на странице товародвижения $examplesTable")
    public void thenTheUserAssertsExactOperationOmTheStockMovementPage(ExamplesTable examplesTable) {
        stockMovementSteps.stockMovementPageContainExactInvoice(examplesTable);
    }

    @Then("the user asserts the invoice product list contain product with values $examplesTable")
    @Alias("пользователь проверяет, что список товаров содержит товары с данными $examplesTable")
    public void thenTheUserAssertsTheInvoiceProductListContainProductWithValues(ExamplesTable examplesTable) {
        stockMovementSteps.invoiceProductCollectionExactCompare(examplesTable);
    }

    @Then("пользователь проверяет, что список товаров в списании содержит товары с данными $examplesTable")
    public void thenTheUserAssertsTheWriteOffProductListContainProductWithValues(ExamplesTable examplesTable) {
        stockMovementSteps.invoiceWriteOffCollectionExactCompare(examplesTable);
    }

    @Then("the user asserts stock movement page title is '$title'")
    @Alias("пользователь проверяет, что заголовок страницы товародвижения равен '$title'")
    public void thenTheUserAssertsStockMovementPageTitle(String title) {
        stockMovementSteps.assertStockMovementPageTitle(title);
    }

    @Then("the user asserts create new invoice modal window title is '$title'")
    @Alias("пользователь проверяет, что заголовок модального окна создания накладной равен '$title'")
    public void thenTheUserAssertsCreateNewInvoiceModalWindowTitle(String title) {
        stockMovementSteps.assertInvoiceCreateModalWindowPageTitle(title);
    }

    @Then("пользователь проверяет, что заголовок модального окна списания накладной равен '$title'")
    public void thenTheUserAssertsCreateNewWriteOffModalWindowTitle(String title) {
        stockMovementSteps.assertWriteOffCreateModalWindowPageTitle(title);
    }

    @Then("the user asserts edit invoice modal window title is '$title'")
    @Alias("пользователь проверяет, что заголовок модального окна редактирования накладной равен '$title'")
    public void thenTheUserAssertsEditInvoiceModalWindowTitle(String title) {
        stockMovementSteps.assertInvoiceEditModalWindowPageTitle(title);
    }

    @Then("пользователь проверяет, что заголовок модального окна редактирования списания равен '$title'")
    public void thenTheUserAssertsEditWriteOffModalWindowTitle(String title) {
        stockMovementSteps.assertWriteOffEditModalWindowPageTitle(title);
    }

    @Then("the user asserts stock movement operations dont contain last created invoice")
    @Alias("пользователь проверяет, что в операциях товародвижения отсутствует последняя созданная накладная")
    public void thenTheUserAssertsStockMovementOperationsDontContainLastCreatedInvoice() throws JSONException {
        stockMovementSteps.stockMovementCollectionDontContainLastCreatedInvoice();
    }

    @Then("пользователь проверяет, что в операциях товародвижения отсутствует последнее созданное списание")
    public void thenTheUserAssertsStockMovementOperationsDontContainLastCreatedWriteOff() throws JSONException {
        stockMovementSteps.stockMovementCollectionDontContainLastCreatedWriteOff();
    }

    @Then("the user asserts invoice total sum is '$totalSum' in create new invoice modal window")
    @Alias("пользователь проверяет, что сумма итого равна '$totalSum' в модальном окне создания накладной")
    public void thenTheUserAssertsInvoiceTotalSumInCreateNewInvoiceModalWindow(String totalSum) {
        stockMovementSteps.assertInvoiceCreateModalWindowTotalSum(totalSum);
    }

    @Then("пользователь проверяет, что сумма итого равна '$totalSum' в модальном окне создания списания")
    public void thenTheUserAssertsWriteOffTotalSumInCreateNewInvoiceModalWindow(String totalSum) {
        stockMovementSteps.assertWriteOffCreateModalWindowTotalSum(totalSum);
    }

    @Then("the user asserts invoice total sum is '$totalSum' in edit invoice modal window")
    @Alias("пользователь проверяет, что сумма итого равна '$totalSum' в модальном окне редактирования накладной")
    public void thenTheUserAssertsInvoiceTotalSumInEditInvoiceModalWindow(String totalSum) {
        stockMovementSteps.assertInvoiceEditModalWindowTotalSum(totalSum);
    }

    @Then("пользователь проверяет, что сумма итого равна '$totalSum' в модальном окне редактирования списания")
    public void thenTheUserAssertsWriteOffTotalSumInEditInvoiceModalWindow(String totalSum) {
        stockMovementSteps.assertWriteOffEditModalWindowTotalSum(totalSum);
    }

    @Then("the user asserts the invoice date is set automatically to now date")
    @Alias("пользователь проверяет, что поле дата заполнено сегодняшней датой")
    public void thenTheUserAssertsTheInvoiceDateIsSetAutomaticallyToNowDate() {
        stockMovementSteps.assertInvoiceDateIsNowDate();
    }

    @Then("пользователь проверяет, что поле дата заполнено сегодняшней датой в модальном окне создания списания")
    public void thenTheUserAssertsTheWriteOffDateIsSetAutomaticallyToNowDate() {
        stockMovementSteps.assertWriteOffDateIsNowDate();
    }

    @Then("the user checks the element with name '$name' has value equals to '$value' in the edit invoice modal window")
    @Alias("пользователь проверяет, что поле с именем '$name' заполнено значением '$value' в модальном окне редактирования накладной")
    public void thenTheUserChecksTheElementWithNameHasValue(String name, String value) {
        stockMovementSteps.invoiceEditModalWindowCheckValue(name, value);
    }

    @Then("the user checks the element with name '$name' has value equals to '$value' in the create new invoice modal window")
    @Alias("пользователь проверяет, что поле с именем '$name' заполнено значением '$value' в модальном окне создания накладной")
    public void thenTheUserChecksTheElementWithNameHasValueInTheCreateNewInvoiceModalWindow(String name, String value) {
        stockMovementSteps.invoiceCreateModalWindowCheckValue(name, value);
    }

    @Then("пользователь проверяет, что поле с именем supplier заполнено сохраненным значением в модальном окне создания накладной")
    public void thenTheUserChecksTheElementWithNameHasValueInTheCreateNewInvoiceModalWindow() {
        stockMovementSteps.invoiceCreateModalWindowCheckValue();
    }

    @Then("пользователь проверяет, что поле с именем product.name заполнено сохраненным значением в модальном окне создания накладной")
    public void thenTheUserChecksTheProductNameHasValueInTheCreateNewInvoiceModalWindow() {
        stockMovementSteps.invoiceCreateModalWindowCheckValueProductName();
    }

    @Then("the user checks values on the edit invoice modal window $examplesTable")
    @Alias("пользователь проверяет поля в модальном окне редактирования накладной $examplesTable")
    public void thenTheUserChecksValuesOnTheEditInvoiceModalWindow(ExamplesTable examplesTable) {
        stockMovementSteps.invoiceEditModalWindowChecksValues(examplesTable);
    }

    @Then("пользователь проверяет поля в модальном окне редактирования списания $examplesTable")
    public void thenTheUserChecksValuesOnTheEditWriteOffModalWindow(ExamplesTable examplesTable) {
        stockMovementSteps.WriteOffEditModalWindowChecksValues(examplesTable);
    }

    @Then("пользователь проверяет, что у поля с именем '$elementName' имеется сообщения об ошибке с сообщением '$message' в модальном окне создания поставщика в накладной")
    public void thenTheUserChecksTheCreateNewSupplierModalWindowFieldHasErrorMessage(String elementName, String message) {
        stockMovementSteps.supplierCreateModalPageCheckErrorMessage(elementName, message);
    }

    @Then("пользователь проверяет, что у поля с именем '$elementName' имеется сообщения об ошибке с сообщением '$errorMessage' в модальном окне создания товара в накладной")
    @Alias("пользователь проверяет, что у поля с именем '$elementName' имеется сообщения об ошибке с сообщением errorMessage в модальном окне создания товара в накладной")
    public void thenTheUserChecksTheCreateNewProductModalWindowFieldHasErrorMessage(String elementName, String errorMessage) {
        stockMovementSteps.assertCreateNewProductModalWindowFieldErrorMessage(elementName, errorMessage);
    }

    @Then("пользователь проверяет, что у поля с именем '$elementName' имеется сообщения об ошибке с сообщением '$errorMessage' в модальном окне создания накладной")
    @Alias("пользователь проверяет, что у поля с именем '$elementName' имеется сообщения об ошибке с сообщением errorMessage в модальном окне создания накладной")
    public void thenTheUserChecksTheCreateNewInvoiceModalWindowFieldHasErrorMessage(String elementName, String errorMessage) {
        stockMovementSteps.invoiceCreateModalCheckErrorMessage(elementName, errorMessage);
    }

    @Then("пользователь проверяет, что у поля с именем '$elementName' имеется сообщения об ошибке с сообщением '$message' в модальном окне редактирования накладной")
    public void thenTheUserChecksTheEditInvoiceModalWindowFieldHasErrorMessage(String elementName, String message) {
        stockMovementSteps.invoiceEditModalCheckErrorMessage(elementName, message);
    }
}
