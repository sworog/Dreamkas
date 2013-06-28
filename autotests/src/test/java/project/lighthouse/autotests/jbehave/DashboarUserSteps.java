package project.lighthouse.autotests.jbehave;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Alias;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.annotations.When;
import project.lighthouse.autotests.steps.DashBoardSteps;

public class DashboarUserSteps {

    @Steps
    DashBoardSteps dashBoardSteps;

    @When("user opens the dashboard '$sectionName' section")
    public void whenTheUserOpensTheDashboardSection(String sectionName) {
        dashBoardSteps.buttonClick(sectionName);
    }

    @Then("the user checks the dashboard link to '$sectionName' section is present")
    @Alias("the user checks the dashboard link to <sectionName> section is present")
    public void thenTheUserChecksTheLinkToSectionIsPresent(String sectionName) {
        dashBoardSteps.shouldBeVisible(sectionName);
    }

    @Then("the user checks the dashboard link to '$sectionName' section is not present")
    public void thenTheUserChecksTheLinkToSectionIsNotPresent(String sectionName) {
        dashBoardSteps.shouldNotBeVisible(sectionName);
    }
}
