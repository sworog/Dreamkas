package project.lighthouse.autotests.jbehave.commercialManager.supplier;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Then;
import project.lighthouse.autotests.steps.commercialManager.supplier.SupplierSteps;

public class ThenSupplierSteps {

    @Steps
    SupplierSteps supplierSteps;

    @Then("the user asserts label of supplier field with name '$elementName'")
    public void thenTheUserAssertsLabelOfFieldWithName(String elementName) {
        supplierSteps.labelsCheck(elementName);
    }

    @Then("the user asserts the supplier field length with name '$elementName' is '$number'")
    public void thenTheUserAssertsTheFieldLengthWithName(String elementName, int number) {
        supplierSteps.checkFieldLength(elementName, number);
    }
}
