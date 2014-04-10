package project.lighthouse.autotests.jbehave.departmentManager.invoice;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.steps.departmentManager.invoice.deprecated.InvoiceSteps;

public class ThenInvoiceProductsSteps {

    @Steps
    InvoiceSteps invoiceSteps;

    @Then("the user checks the invoice products list contains entry $examplesTable")
    public void thenTheUserChecksTheInvoiceProductsListContainsEntry(ExamplesTable examplesTable) {
        invoiceSteps.compareWithExampleTable(examplesTable);
    }
}
