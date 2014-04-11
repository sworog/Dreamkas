package project.lighthouse.autotests.jbehave.departmentManager.invoice;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Then;
import project.lighthouse.autotests.steps.departmentManager.invoice.InvoiceSteps;

public class ThenInvoiceUserSteps {

    @Steps
    InvoiceSteps invoiceSteps;

    @Then("the user asserts the invoice field label with name '$elementName'")
    public void thenTheUserAssertsTheInvoiceFieldLabelWithName(String elementName) {
        invoiceSteps.assertFieldLabel(elementName);
    }
}
