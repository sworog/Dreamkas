package project.lighthouse.autotests.jbehave.departmentManager;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.When;
import project.lighthouse.autotests.steps.departmentManager.invoice.deprecated.InvoiceSteps;

public class InvoiceUserSteps {

    @Steps
    InvoiceSteps invoiceSteps;

    @When("the user clicks on item named '$itemName'")
    public void whenTheUserClicksOnItem(String itemName) {
        invoiceSteps.itemClick(itemName);
    }
}
