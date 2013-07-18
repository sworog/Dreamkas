package project.lighthouse.autotests.steps.commercialManager;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.pages.Pages;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import org.json.JSONException;
import project.lighthouse.autotests.StaticData;
import project.lighthouse.autotests.objects.Department;
import project.lighthouse.autotests.objects.Store;
import project.lighthouse.autotests.pages.commercialManager.department.DepartmentApi;
import project.lighthouse.autotests.pages.commercialManager.department.DepartmentCardPage;
import project.lighthouse.autotests.pages.commercialManager.department.DepartmentCreatePage;
import project.lighthouse.autotests.pages.commercialManager.store.StoreCardPage;

import java.io.IOException;
import java.util.Map;

public class DepartmentSteps extends ScenarioSteps {
    StoreCardPage storeCardPage;
    DepartmentCreatePage departmentCreatePage;
    DepartmentApi departmentApi;
    DepartmentCardPage departmentCardPage;

    public DepartmentSteps(Pages pages) {
        super(pages);
    }

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
        for (Map<String,String> column : departmentData.getRows()) {
            storeCardPage.findModelFieldContaining("department", column.get("elementName"), column.get("value"));
        }
    }

    @Step
    public void checkDepartmentDataInCard(ExamplesTable departmentData) {
        for (Map<String,String> column : departmentData.getRows()) {
            departmentCardPage.findModelFieldContaining("department", column.get("elementName"), column.get("value"));
        }
    }

    @Step
    public void clickDepartmentRow(String departmentNumber) {
        storeCardPage.findRowByDepartmentNumber(departmentNumber).click();
    }

    @Step
    public Department createDepartmentInDefaultStore(String departmentNumber, String departmentName) throws IOException, JSONException {
        departmentApi.createStoreDepartmentThroughPost(departmentNumber, departmentName);
        return StaticData.departments.get(departmentNumber);
    }

    @Step
    public void navigateToDepartmentPage(String departmentId, String storeId){
        departmentCardPage.navigateToDepartmentCardPage(departmentId, storeId);
    }
}
