package project.lighthouse.autotests.jbehave.commercialManager;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.annotations.When;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.steps.commercialManager.OrganizationSteps;

public class OrganizationUserSteps {
    @Steps
    OrganizationSteps organizationSteps;

    ExamplesTable organizationData;

    @Given("user is on create organization page")
    public void userIsOnCreateOrganizationPage() {
        organizationSteps.navigateToCreateOrganizationPage();
    }

    @Given("user is on company page")
    public void userIsOnCompanyPage() {
        organizationSteps.navigateToCompanyPage();
    }

    @When("user clicks organization form create button")
    public void userClicksCreateFormCreateButton() {
        organizationSteps.clickCreateButton();
    }

    @When("user fill organization inputs $fieldInputTable")
    public void userFillInputs(ExamplesTable fieldInputTable) {
        organizationSteps.fillInputs(fieldInputTable);
        organizationData = fieldInputTable;
    }

    @When("user clicks to organization in list with name $name")
    public void userClicksToOrganizationInListWithName(String name) {
        organizationSteps.clickOrganizationListItemByName(name);
    }

    @Then("user checks organization fields data")
    public void userChecksOrganizationFieldsData() {
        organizationSteps.checkOrganizationData(organizationData);
    }

    @When("user clicks create new organization link")
    public void userClicksCreateNewOrganizationLink() {
        organizationSteps.clickCreateNewOrganizationLink();
    }

    @Then("user checks organization form the element field '$name' has error message '$errorMessage'")
    public void userCheckOrganizationFormTheElementFieldHasErrorMessage(String name, String errorMessage) {
        organizationSteps.assertFieldErrorMessage(name, errorMessage);
    }
}
