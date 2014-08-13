package project.lighthouse.autotests.jbehave.stockMovement;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.model.ExamplesTable;
import org.json.JSONException;
import project.lighthouse.autotests.steps.stockMovement.StockMovementSteps;

public class ThenStockMovementUserSteps {

    @Steps
    StockMovementSteps stockMovementSteps;

    @Then("the user asserts stock movement operations on the stock movement page $examplesTable")
    public void thenTheUserAssertsOperationOmTheStockMovementPage(ExamplesTable examplesTable) {
        stockMovementSteps.stockMovementPageContainInvoice(examplesTable);
    }

    @Then("the user asserts the invoice product list contain product with values $examplesTable")
    public void thenTheUserAssertsTheInvoiceProductListContainProductWithValues(ExamplesTable examplesTable) {
        stockMovementSteps.invoiceProductCollectionExactCompare(examplesTable);
    }

    @Then("the user asserts stock movement page title is '$title'")
    public void thenTheUserAssertsStockMovementPageTitle(String title) {
        stockMovementSteps.assertStockMovementPageTitle(title);
    }

    @Then("the user asserts create new invoice modal window title is '$title'")
    public void thenTheUserAssertsCreateNewInvoiceModalWindowTitle(String title) {
        stockMovementSteps.assertInvoiceCreateModalWindowPageTitle(title);
    }

    @Then("the user asserts edit invoice modal window title is '$title'")
    public void thenTheUserAssertsEditInvoiceModalWindowTitle(String title) {
        stockMovementSteps.assertInvoiceEditModalWindowPageTitle(title);
    }

    @Then("the user asserts stock movement operations dont contain last created invoice")
    public void thenTheUserAssertsStockMovementOperationsDontContainLastCreatedInvoice() throws JSONException {
        stockMovementSteps.stockMovementCollectionDontContainLastCreatedInvoice();
    }

    @Then("the user asserts invoice total sum is '$totalSum' in create new invoice modal window")
    public void thenTheUserAssertsInvoiceTotalSumInCreateNewInvoiceModalWindow(String totalSum) {
        stockMovementSteps.assertInvoiceCreateModalWindowTotalSum(totalSum);
    }

    @Then("the user asserts invoice total sum is '$totalSum' in edit invoice modal window")
    public void thenTheUserAssertsInvoiceTotalSumInEditInvoiceModalWindow(String totalSum) {
        stockMovementSteps.assertInvoiceEditModalWindowTotalSum(totalSum);
    }

    @Then("the user asserts the invoice date is set automatically to now date")
    public void thenTheUserAssertsTheInvoiceDateIsSetAutomaticallyToNowDate() {
        stockMovementSteps.assertInvoiceDateIsNowDate();
    }

    @Then("the user checks the element with name '$name' has value equals to '$value' in the edit invoice modal window")
    public void thenTheUserChecksTheElementWithNameHasValue(String name, String value) {
        stockMovementSteps.invoiceEditModalWindowCheckValue(name, value);
    }

    @Then("the user checks the element with name '$name' has value equals to '$value' in the create new invoice modal window")
    public void thenTheUserChecksTheElementWithNameHasValueInTheCreateNewInvoiceModalWindow(String name, String value) {
        stockMovementSteps.invoiceCreateModalWindowCheckValue(name, value);
    }

    @Then("the user checks values on the edit invoice modal window $examplesTable")
    public void thenTheUserChecksValuesOnTheEditInvoiceModalWindow(ExamplesTable examplesTable) {
        stockMovementSteps.invoiceEditModalWindowChecksValues(examplesTable);
    }
}
