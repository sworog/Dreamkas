package project.lighthouse.autotests.jbehave.departmentManager.invoice;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.When;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.steps.departmentManager.invoice.InvoiceSteps;

public class WhenInvoiceUserSteps {

    @Steps
    InvoiceSteps invoiceSteps;

    @When("the user inputs values on invoice page $examplesTable")
    public void whenTheUserInputsValuesOnOrderPage(ExamplesTable examplesTable) {
        invoiceSteps.input(examplesTable);
    }

    @When("the user inputs quantity '$value' on the invoice product with name '$name'")
    public void whenTheUserInputsQuantityOnTheInvoiceProductWithName(String value, String name) {
        invoiceSteps.invoiceProductObjectQuantityType(name, value);
    }

    @When("the user inputs price '$value' on the invoice product with name '$name'")
    public void whenTheUserInputsPriceOnTheInvoiceProductWithName(String value, String name) {
        invoiceSteps.invoiceProductObjectPriceType(name, value);
    }

    @When("the user accepts products and saves the invoice")
    public void whenTheUserAcceptsProductsAndSavesTheInvoice() {
        invoiceSteps.acceptProductsButtonClick();
    }
}
