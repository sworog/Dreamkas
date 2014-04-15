package project.lighthouse.autotests.jbehave.departmentManager.invoice;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.When;
import project.lighthouse.autotests.steps.departmentManager.invoice.InvoiceMenuNavigationSteps;

public class WhenInvoiceMenuNavigationUserSteps {

    @Steps
    InvoiceMenuNavigationSteps invoiceMenuNavigationSteps;

    @When("the user clicks the create invoice link on invoice page menu navigation")
    public void whenTheUserClicksTheCreateInvoiceLinkOnOrderPageMenuNavigation() {
        invoiceMenuNavigationSteps.invoiceCreateLinkClick();
    }

    @When("the user clicks the local navigation invoice search link")
    public void whenTheUserClicksTheLocalNavigationInvoiceSearchLink() {
        invoiceMenuNavigationSteps.searchLinkClick();
    }
}
