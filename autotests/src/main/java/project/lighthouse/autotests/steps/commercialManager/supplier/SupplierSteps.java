package project.lighthouse.autotests.steps.commercialManager.supplier;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.pages.commercialManager.supplier.SupplierPage;

public class SupplierSteps extends ScenarioSteps {

    SupplierPage supplierPage;

    @Step
    public void input(ExamplesTable examplesTable) {
        supplierPage.inputTable(examplesTable);
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
}
