package project.lighthouse.autotests.jbehave.commercialManager;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.annotations.When;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.steps.commercialManager.DepartmentSteps;

public class DepartmentUserSteps {
    ExamplesTable departmentData;

    @Steps
    DepartmentSteps formSteps;

    @When("user clicks create new department button")
    public void userClicksCreateNewDepartmentButton() {
        formSteps.clickCreateNewDepartmentButton();
    }

    @When("user clicks department form submit button")
    public void userClicksFormSubmitButton() {
        formSteps.clickCreateDepartmentSubmitButton();
    }

    @When("user fills department form with following data $formData")
    public void userFillsFormData(ExamplesTable formData) {
        formSteps.fillStoreFormData(formData);
        departmentData = formData;
    }

    @Then("user checks department card data")
    public void userChecksDepartmentCardData() {
        formSteps.checkDepartmentDataInList(departmentData);
    }
}
