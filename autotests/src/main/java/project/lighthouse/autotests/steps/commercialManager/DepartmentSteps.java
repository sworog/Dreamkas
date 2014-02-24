package project.lighthouse.autotests.steps.commercialManager;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.pages.commercialManager.department.DepartmentCardPage;
import project.lighthouse.autotests.pages.commercialManager.department.DepartmentCreatePage;
import project.lighthouse.autotests.pages.commercialManager.store.StoreCardPage;

import java.util.Map;

public class DepartmentSteps extends ScenarioSteps {
    StoreCardPage storeCardPage;
    DepartmentCreatePage departmentCreatePage;
    DepartmentCardPage departmentCardPage;

    @Step
    public void clickCreateNewDepartmentButton() {
        storeCardPage.createNewDepartmentButton().click();
    }

    @Step
    public void clickCreateDepartmentSubmitButton() {
        departmentCreatePage.submitButton().click();
    }

    @Step
    public void fillStoreFormData(ExamplesTable formData) {
        departmentCreatePage.inputTable(formData);
    }

    @Step
    public void checkDepartmentDataInList(ExamplesTable departmentData) {
        for (Map<String, String> column : departmentData.getRows()) {
            storeCardPage.findModelFieldContaining("department", column.get("elementName"), column.get("value"));
        }
    }

    @Step
    public void checkDepartmentDataInCard(ExamplesTable departmentData) {
        for (Map<String, String> column : departmentData.getRows()) {
            departmentCardPage.findModelFieldContaining("department", column.get("elementName"), column.get("value"));
        }
    }

    @Step
    public void clickDepartmentRow(String departmentNumber) {
        storeCardPage.findRowByDepartmentNumber(departmentNumber).click();
    }

    @Step
    public void navigateToDepartmentPage(String departmentId, String storeId) {
        departmentCardPage.navigateToDepartmentCardPage(departmentId, storeId);
    }

    @Step
    public void clicksEditDepartmentLink() {
        departmentCardPage.editButton().click();
    }
}
