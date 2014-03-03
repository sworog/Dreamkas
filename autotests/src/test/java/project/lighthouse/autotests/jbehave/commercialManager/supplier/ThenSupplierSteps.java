package project.lighthouse.autotests.jbehave.commercialManager.supplier;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Alias;
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

    @Then("the user checks the supplier list contains element with value")
    @Alias("the user checks the supplier list contains element with value '$value'")
    public void thenTheUserChecksTheSupplierListContainsElementWithValue(String value) {
        supplierSteps.contains(value);
    }

    @Then("the user checks the supplier list not contains element with value '$value'")
    public void thenTheUserChecksTheSupplierListNotContainsElementValue(String value) {
        supplierSteps.supplierObjectCollectionNotContains(value);
    }

    @Then("the user checks the supplier list contains stored element")
    public void thenTheUserChecksTheSupplierListContainsStoredElement() {
        supplierSteps.supplierObjectCollectionContainsStoredValue();
    }

    @Then("the user checks the upload agreement file buttton is clickable")
    public void thenTheUserChecksTheUploadAgreementFileButtonIsClickable() {
        supplierSteps.uploadFileButtonClick();
    }

    @Then("the user checks the replace agreement file buttton is clickable")
    public void thenTheUserChecksTheReplaceAgreementFileButtonIsClickable() {
        supplierSteps.replaceFileButtonClick();
    }

    @Then("the user checks the supplier create button is disabled")
    public void thenTheUserChecksTheSupplierCreateButtonIsDisabled() {
        supplierSteps.assertCreateButtonIsDisabled();
    }

    @Then("the user checks the supplier cancel button is disabled")
    public void thenTheUserChecksTheSupplierCancelButtonIsDisabled() {
        supplierSteps.assertCancelButtonIsDisabled();
    }

    @Then("the user waits for upload complete")
    public void thenTheUserWaitsForUploadComplete() {
        supplierSteps.waitForUploadComplete();
    }
}
