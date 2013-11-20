package project.lighthouse.autotests.jbehave.departmentManager.invoiceSteps;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Then;
import project.lighthouse.autotests.steps.departmentManager.InvoiceSteps;

public class ThenInvoiceSteps {

    @Steps
    InvoiceSteps invoiceSteps;

    @Then("the user checks the checkbox '$itemName' is '$state'")
    public void thenTheUserChecksTheCheckBox(String itemName, String state) {
        invoiceSteps.checkTheStateOfCheckBox(itemName, state);
    }

    @Then("the user waits for checkBoxPreloader")
    public void thenTheUserWaitsForCheckboxPreLoader() {
        invoiceSteps.checkBoxPreLoaderWait();
    }
}
