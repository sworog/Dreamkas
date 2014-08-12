package project.lighthouse.autotests.jbehave.stockMovement;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.When;
import org.jbehave.core.model.ExamplesTable;
import org.json.JSONException;
import project.lighthouse.autotests.steps.stockMovement.StockMovementSteps;

public class WhenStockMovementUserSteps {

    @Steps
    StockMovementSteps stockMovementSteps;

    @When("the user clicks on the accept products button")
    public void whenTheUserClicksOnTheAcceptProductButton() {
        stockMovementSteps.acceptProductsButtonClick();
    }

    @When("the user inputs values on the create new invoice modal window $examplesTable")
    public void whenTheUserInputsOnTheCreateNewInvoiceModalWindow(ExamplesTable examplesTable) {
        stockMovementSteps.invoiceCreateModalWindowInput(examplesTable);
    }

    @When("the user inputs values on the edit invoice modal window $examplesTable")
    public void whenTheUserInputsOnTheEditInvoiceModalWindow(ExamplesTable examplesTable) {
        stockMovementSteps.invoiceEditModalWindowWindowInput(examplesTable);
    }

    @When("the user clicks on the paid check box")
    public void whenTheUserClicksOnThePaidCheckBox() {
        stockMovementSteps.paidCheckBoxClick();
    }

    @When("the user clicks on the paid check box in the edit invoice modal window")
    public void whenTheUserClicksOnThePaidCheckBoxInTheEditInvoiceModalWindow() {
        stockMovementSteps.invoiceEditModalWindowPaidCheckBoxClick();
    }

    @When("the user clicks on the add new invoice product button")
    public void whenTheUserClicksOnTheAddNewInvoiceProductButton() {
        stockMovementSteps.addProductToInvoiceButtonClick();
    }

    @When("the user clicks on the add new invoice product button in the edit invoice modal window")
    public void whenTheUserClicksOnTheAddNewInvoiceProductButtonInTheEditModalWIndow() {
        stockMovementSteps.invoiceEditModalWindowAddProductToInvoiceButtonClick();
    }

    @When("the user clicks on the invoice accept button")
    public void whenTheUserClicksOnTheInvoiceAcceptButton() {
        stockMovementSteps.acceptInvoiceButtonClick();
    }

    @When("the user clicks on the invoice save button in the edit invoice modal window")
    public void whenTheUserClicksOnTheInvoiceSaveButton() {
        stockMovementSteps.saveInvoiceButtonClick();
    }

    @When("the user clicks on the last created invoice from builder steps on the stock movement page")
    public void whenTheUserClicksOnTheLastCreatedInvoiceFromBuilderStepsOnTheStockMovementPage() throws JSONException {
        stockMovementSteps.openLastCreatedInvoiceInStockMovementPage();
    }

    @When("the user clicks on delete invoice button in edit invoice modal window")
    public void whenTheUserClicksOnDeleteInvoiceButtonInEditInvoiceModalWindow() {
        stockMovementSteps.deleteInvoiceLinkClick();
    }

    @When("the user clicks on delete invoice confirm button in edit invoice modal window")
    public void whenTheUserClicksOnDeleteInvoiceConfirmButtonInEditInvoiceModalWindow() {
        stockMovementSteps.confirmDeleteInvoiceLinkClick();
    }

    @When("the user deletes the product with name '$name' in the edit invoice modal window")
    public void whenTheUserDeletesTheProductWithNameInTheEditInvoiceModalWindow(String name) {
        stockMovementSteps.invoiceProductWithNameDeleteIconClick(name);
    }
}
