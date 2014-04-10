package project.lighthouse.autotests.jbehave.departmentManager.invoice;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.When;
import project.lighthouse.autotests.steps.departmentManager.invoice.InvoiceSteps;

public class WhenInvoiceMenuNavigationUserSteps {

    @Steps
    InvoiceSteps invoiceSteps;

    @When("the user clicks the create invoice link on order page menu navigation")
    public void whenTheUserClicksTheCreateInvoiceLinkOnOrderPageMenuNavigation() {
        invoiceSteps.invoiceCreateLinkClick();
    }

    @When("the user clicks the local navigation invoice search link")
    public void whenTheUserClicksTheLocalNavigationInvoiceSearchLink() {
        invoiceSteps.searchLinkClick();
    }
}
