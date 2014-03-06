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
        supplierSteps.assertUploadFileButtonIsClickable();
    }

    @Then("the user checks the replace agreement file buttton is clickable")
    public void thenTheUserChecksTheReplaceAgreementFileButtonIsClickable() {
        supplierSteps.assertReplaceFileButtonIsClickable();
    }

    @Then("the user checks the supplier create button is disabled")
    public void thenTheUserChecksTheSupplierCreateButtonIsDisabled() {
        supplierSteps.assertCreateButtonIsDisabled();
    }

    @Then("the user checks the upload button is disabled")
    public void thenTheUserChecksTheUploadButtonIsDisabled() {
        supplierSteps.assertUploadButtonIsDisabled();
    }

    @Then("the user checks the replace upload button is disabled")
    public void thenTheUserChecksTheReplaceButtonIsDisabled() {
        supplierSteps.assertReplaceButtonIsDisabled();
    }

    @Then("the user checks the upload delete button is disabled")
    public void thenTheUserChecksTheUploadDeleteButtonIsDisabled() {
        supplierSteps.assertDeleteButtonIsDisabled();
    }

    @Then("the user waits for upload complete")
    public void thenTheUserWaitsForUploadComplete() {
        supplierSteps.waitForUploadComplete();
    }

    @Then("the user asserts uploaded file name is expected")
    public void thenTheUserAssertsUploadedFileName() {
        supplierSteps.assertUploadedFileName();
    }

    @Then("the user asserts uploaded file name is '$fileName'")
    public void thenTheUserAssertsUploadedFileName(String fileName) {
        supplierSteps.assertUploadedFileName(fileName);
    }

    @Then("the user asserts downloaded file is equals to uploaded file")
    public void thenTheUserAssertsDownloadedFileIsequalsUploadedFile() throws Exception {
        supplierSteps.assertDownloadedFileEqualsToUploadedFileOnTheSupplierPage();
    }

    @Then("the user asserts downloaded file is equals to uploaded file of supplier list item found by locator '$locator'")
    public void thenTheUserAssertsDownloadedFileIsequalsUploadedFileOnTheSupplierListPage(String locator) throws Exception {
        supplierSteps.assertDownLoadedAgreementFileIsEqualsToUploadedFileOnTheSupplierList(locator);
    }

    @Then("the user asserts the download agreement button is clickable of supplier list item found by locator '$locator'")
    public void thenTheUserAssertsTheDownloadAgreementButtonIsClickableOfSupplierListItemFoundByLocator(String locator) {
        supplierSteps.assertDownLoadAgreementButtonIsClickable(locator);
    }

    @Then("the user asserts the download agreement button is not visible of supplier list item found by locator '$locator'")
    public void thenTheUserassertsTheDownloadAgreementButtonIsNotVisibleOfSupplierListItemFoundByLocator(String locator) {
        supplierSteps.assertDownloadAgreementButtonIsNotVisibleFromSupplierObjectByLocator(locator);
    }

    @Then("the user asserts the download agreement button is visible of supplier list item found by locator '$locator'")
    public void thenTheUserassertsTheDownloadAgreementButtonIsVisibleOfSupplierListItemFoundByLocator(String locator) {
        supplierSteps.assertDownloadAgreementButtonIsVisibleFromSupplierObjectByLocator(locator);
    }

    @Then("the user asserts there is no file attached in supplier")
    public void thenTheUserAssertsThereIsNoFileAttached() {
        supplierSteps.assertThereIsNoFileAttached();
    }
}
