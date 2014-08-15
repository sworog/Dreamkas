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

    @Then("the user asserts edit invoice modal window title is '$title'")
    @Alias("пользователь проверяет, что заголовок модального окна редактирования накладной равен '$title'")
    public void thenTheUserAssertsEditInvoiceModalWindowTitle(String title) {
        stockMovementSteps.assertInvoiceEditModalWindowPageTitle(title);
    }

    @Then("the user asserts stock movement operations dont contain last created invoice")
    @Alias("пользователь проверяет, что в операциях товародвижения отсутствует последняя созданная накладная")
    public void thenTheUserAssertsStockMovementOperationsDontContainLastCreatedInvoice() throws JSONException {
        stockMovementSteps.stockMovementCollectionDontContainLastCreatedInvoice();
    }

    @Then("the user asserts invoice total sum is '$totalSum' in create new invoice modal window")
    @Alias("пользователь проверяет, что сумма итого равна '$totalSum' в модальном окне создания накладной")
    public void thenTheUserAssertsInvoiceTotalSumInCreateNewInvoiceModalWindow(String totalSum) {
        stockMovementSteps.assertInvoiceCreateModalWindowTotalSum(totalSum);
    }

    @Then("the user asserts invoice total sum is '$totalSum' in edit invoice modal window")
    @Alias("пользователь проверяет, что сумма итого равна '$totalSum' в модальном окне редактирования накладной")
    public void thenTheUserAssertsInvoiceTotalSumInEditInvoiceModalWindow(String totalSum) {
        stockMovementSteps.assertInvoiceEditModalWindowTotalSum(totalSum);
    }

    @Then("the user asserts the invoice date is set automatically to now date")
    @Alias("пользователь проверяет, что поле дата заполнено сегодняшней датой")
    public void thenTheUserAssertsTheInvoiceDateIsSetAutomaticallyToNowDate() {
        stockMovementSteps.assertInvoiceDateIsNowDate();
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

    @Then("the user checks values on the edit invoice modal window $examplesTable")
    @Alias("пользователь проверяет поля в модальном окне редактирования накладной $examplesTable")
    public void thenTheUserChecksValuesOnTheEditInvoiceModalWindow(ExamplesTable examplesTable) {
        stockMovementSteps.invoiceEditModalWindowChecksValues(examplesTable);
    }
}
