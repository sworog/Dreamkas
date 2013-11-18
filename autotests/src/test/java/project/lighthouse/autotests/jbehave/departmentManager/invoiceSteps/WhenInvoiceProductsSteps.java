package project.lighthouse.autotests.jbehave.departmentManager.invoiceSteps;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.When;
import project.lighthouse.autotests.steps.departmentManager.InvoiceSteps;

public class WhenInvoiceProductsSteps {

    @Steps
    InvoiceSteps invoiceSteps;


    @When("the user clicks on property named '$propertyName' of invoice product named '$locator'")
    public void propertyClick(String locator, String propertyName) {
        invoiceSteps.objectPropertyClick(locator, propertyName);
    }

    @When("the user inputs the value '$value' in property named '$propertyName' of invoice product named '$locator'")
    public void propertyInput(String locator, String propertyName, String value) {
        invoiceSteps.objectPropertyInput(locator, propertyName, value);
    }
}
