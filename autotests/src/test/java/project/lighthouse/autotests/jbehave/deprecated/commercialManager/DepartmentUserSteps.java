package project.lighthouse.autotests.jbehave.deprecated.commercialManager;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.annotations.When;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.steps.AuthorizationSteps;
import project.lighthouse.autotests.steps.deprecated.commercialManager.DepartmentSteps;

public class DepartmentUserSteps {
    ExamplesTable departmentData;

    @Steps
    DepartmentSteps formSteps;
    @Steps
    AuthorizationSteps authorizationSteps;

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
    }

    @Then("user checks department card data $departmentData")
    public void userChecksDepartmentCardData(ExamplesTable departmentData) {
    }

    @When("user clicks to department '$departmentNumber'")
    public void userClickDepartmentRow(String departmentNumber) {

    }

    @When("user clicks edit department link")
    public void userClicksEditDepartmentLink() {
        formSteps.clicksEditDepartmentLink();
    }
}
