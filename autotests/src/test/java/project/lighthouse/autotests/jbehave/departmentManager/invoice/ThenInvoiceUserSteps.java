package project.lighthouse.autotests.jbehave.departmentManager.invoice;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.steps.departmentManager.invoice.InvoiceSteps;

public class ThenInvoiceUserSteps {

    @Steps
    InvoiceSteps invoiceSteps;

    @Then("the user asserts the invoice field label with name '$elementName'")
    public void thenTheUserAssertsTheInvoiceFieldLabelWithName(String elementName) {
        invoiceSteps.assertFieldLabel(elementName);
    }

    @Then("the user waits for the invoice product edition preloader finish")
    public void thenTheUserWaitsForTheInvoiceProductEditionPreloaderFinish() {
        invoiceSteps.invoiceProductEditionPreLoaderWait();
    }

    @Then("the user checks the invoice products list contains exact entries $examplesTable")
    public void thenTheUserChecksTheInvoiceProductsListContainsExactEntries(ExamplesTable examplesTable) {
        invoiceSteps.invoiceProductsCollectionExactCompare(examplesTable);
    }

    @Then("the user checks the invoice total sum is '$expectedValue'")
    public void thenTheUserChecksTheInvoiceTotalSum(String expectedValue) {
        invoiceSteps.assertInvoiceTotalSum(expectedValue);
    }

    @Then("the user checks the invoice vat sum is '$expectedValue'")
    public void thenTheUserChecksTheInvoiceVatSum(String expectedValue) {
        invoiceSteps.assertInvoiceVatSum(expectedValue);
    }

    @Then("the user checks stored values on invoice page")
    public void thenTheUserChecksStoredValuesOnInvoicePage() {
        invoiceSteps.checkValues();
    }
}
