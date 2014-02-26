package project.lighthouse.autotests.steps.commercialManager.supplier;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.helper.StringGenerator;
import project.lighthouse.autotests.pages.commercialManager.supplier.SupplierPage;

public class SupplierSteps extends ScenarioSteps {

    SupplierPage supplierPage;

    @Step
    public void openSupplierCreatePage() {
        supplierPage.open();
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
        supplierPage.createButtonClick();
    }

    @Step
    public void cancelButtonClick() {
        supplierPage.cancelButtonClick();
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
    }

    @Step
    public void contains(String locator) {
        supplierPage.getSupplierObjectCollection().contains(locator);
    }
}
