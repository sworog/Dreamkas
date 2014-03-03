package project.lighthouse.autotests.steps.commercialManager.supplier;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import org.junit.Assert;
import project.lighthouse.autotests.helper.FileCreator;
import project.lighthouse.autotests.helper.StringGenerator;
import project.lighthouse.autotests.pages.commercialManager.supplier.SupplierListPage;
import project.lighthouse.autotests.pages.commercialManager.supplier.SupplierPage;

import java.io.File;

public class SupplierSteps extends ScenarioSteps {

    SupplierPage supplierPage;
    SupplierListPage supplierListPage;

    private String supplierName;

    @Step
    public void openSupplierCreatePage() {
        supplierPage.open();
    }

    @Step
    public void openSupplierListPage() {
        supplierListPage.open();
    }

    @Step
    public void input(ExamplesTable examplesTable) {
        supplierPage.inputTable(examplesTable);
    }

    @Step
    public void input(String elementName, String value) {
        supplierPage.input(elementName, value);
    }

    @Step
    public void createButtonClick() {
        supplierPage.getCreateButtonFacade().click();
    }

    @Step
    public void cancelButtonClick() {
        supplierPage.getCancelButtonLinkFacade().click();
    }

    @Step
    public void labelsCheck(String elementName) {
        supplierPage.checkFieldLabel(elementName);
    }

    @Step
    public void checkFieldLength(String elementName, int number) {
        supplierPage.checkFieldLength(elementName, number);
    }

    @Step
    public void generateString(String elementName, int number) {
        String generatedData = new StringGenerator(number).generateTestData();
        supplierPage.input(elementName, generatedData);
        supplierName = generatedData;
    }

    @Step
    public void contains(String locator) {
        supplierListPage.getSupplierObjectCollection().contains(locator);
    }

    @Step
    public void supplierObjectCollectionNotContains(String locator) {
        supplierListPage.getSupplierObjectCollection().notContains(locator);
    }

    @Step
    public void supplierCollectionObjectClickByLocator(String locator) {
        supplierListPage.getSupplierObjectCollection().clickByLocator(locator);
    }

    @Step
    public void supplierObjectCollectionContainsStoredValue() {
        supplierListPage.getSupplierObjectCollection().contains(supplierName);
    }

    @Step
    public void uploadFileButtonClick() {
        supplierPage.getUploadForm().uploadButtonClick();
    }

    @Step
    public void replaceFileButtonClick() {
        supplierPage.getUploadForm().replaceFileButtonClick();
    }

    @Step
    public void uploadFile(String fileName, int size) {
        File file = new FileCreator(fileName, size).create();
        supplierPage.getUploadForm().uploadFile(file);
    }

    @Step
    public void assertCreateButtonIsDisabled() {
        if (!supplierPage.getCreateButtonFacade().isDisable()) {
            Assert.fail("The supplier create button is not disabled");
        }
    }

    @Step
    public void assertCancelButtonIsDisabled() {
        if (!supplierPage.getCancelButtonLinkFacade().isDisable()) {
            Assert.fail("The supplier cancel button is not disabled");
        }
    }

    @Step
    public void waitForUploadComplete() {
        supplierPage.getUploadForm().waitForUploadComplete();
    }
}
