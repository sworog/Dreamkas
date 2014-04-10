package project.lighthouse.autotests.steps.departmentManager.invoice;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import project.lighthouse.autotests.pages.departmentManager.invoice.menu.InvoiceLocalNavigation;

public class InvoiceSteps extends ScenarioSteps {

    InvoiceLocalNavigation invoiceLocalNavigation;

    @Step
    public void searchLinkClick() {
        invoiceLocalNavigation.searchLinkClick();
    }

    @Step
    public void invoiceCreateLinkClick() {
        invoiceLocalNavigation.invoiceCreateLinkClick();
    }
}
