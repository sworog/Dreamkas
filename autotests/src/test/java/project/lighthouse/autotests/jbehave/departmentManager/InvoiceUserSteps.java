package project.lighthouse.autotests.jbehave.departmentManager;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.When;
import project.lighthouse.autotests.steps.departmentManager.invoice.deprecated.InvoiceSteps;

public class InvoiceUserSteps {

    @Steps
    InvoiceSteps invoiceSteps;

    @When("the user clicks OK and accepts changes")
    public void whenTheUSerClicksOkAndAcceptsChanges() throws InterruptedException {
        invoiceSteps.acceptChangesButtonClick();
    }

    @When("the user clicks Cancel and discard changes")
    public void whenTheUserClicksCancelAndDiscardTheChanges() {
        invoiceSteps.discardChangesButtonClick();
    }

    @When("the user clicks OK and accepts deletion")
    public void whenTheUSerClicksOkAndAcceptsDeletion() throws InterruptedException {
        invoiceSteps.acceptDeleteButtonClick();
    }

    @When("the user clicks Cancel and discard deletion")
    public void whenTheUserClicksCancelAndDiscardTheDeletion() {
        invoiceSteps.discardDeleteButtonClick();
    }

    @When("the user clicks on item named '$itemName'")
    public void whenTheUserClicksOnItem(String itemName) {
        invoiceSteps.itemClick(itemName);
    }
}
