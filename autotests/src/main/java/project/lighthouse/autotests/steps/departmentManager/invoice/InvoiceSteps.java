package project.lighthouse.autotests.steps.departmentManager.invoice;

import net.thucydides.core.steps.ScenarioSteps;
import project.lighthouse.autotests.pages.departmentManager.invoice.InvoicePage;

public class InvoiceSteps extends ScenarioSteps {

    InvoicePage invoicePage;

    public void assertFieldLabel(String elementName) {
        invoicePage.checkFieldLabel(elementName);
    }
}
