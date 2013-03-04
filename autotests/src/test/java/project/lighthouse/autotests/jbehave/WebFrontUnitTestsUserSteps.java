package project.lighthouse.autotests.jbehave;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.annotations.When;
import project.lighthouse.autotests.steps.WebFrontUnitTestSteps;

public class WebFrontUnitTestsUserSteps {

    @Steps
    WebFrontUnitTestSteps webFrontUnitTestSteps;

    @When("the user opens the UT page")
    public void WhenTheUserOpensTheUTPage(){
        webFrontUnitTestSteps.OpenUnitTestPage();
    }

    @Then("the user checks the unit tests passed")
    public void ThenTheUserChecksTheUnitTestsPassed(){
        webFrontUnitTestSteps.CheckUTIsPassed();
    }

}
