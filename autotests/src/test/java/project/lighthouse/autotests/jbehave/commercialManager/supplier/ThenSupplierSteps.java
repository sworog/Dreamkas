package project.lighthouse.autotests.jbehave.commercialManager.supplier;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Then;
import project.lighthouse.autotests.steps.commercialManager.supplier.SupplierSteps;

public class ThenSupplierSteps {

    @Steps
    SupplierSteps supplierSteps;

    @Then("The user asserts label of field with name '$elementName'")
    public void thenTheUserAssertsLabelOfFieldWithName(String elementName) {
        supplierSteps.labelsCheck(elementName);
    }
}
