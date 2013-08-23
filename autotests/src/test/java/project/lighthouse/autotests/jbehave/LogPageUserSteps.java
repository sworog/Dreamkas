package project.lighthouse.autotests.jbehave;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import org.jbehave.core.annotations.Then;
import project.lighthouse.autotests.steps.logPage.LogPageSteps;

public class LogPageUserSteps {

    @Steps
    LogPageSteps logPageSteps;

    @Given("the user opens the log page")
    public void givenTheUserOpensTheLogPage() {
        logPageSteps.open();
    }

    @Then("the user checks the last log final message is '$expectedMessage'")
    public void thenTheUSerChecksTheLastLogFinalMessage(String expectedMessage) {
        logPageSteps.assertLastLogFinalMessage(expectedMessage);
    }

    @Then("the user checks the last log title is '$expectedTitle'")
    public void thenTheUserChecksTheLastLogTitle(String expectedTitle) {
        logPageSteps.assertLastLogTitle(expectedTitle);
    }

    @Then("the user waits for the last log message success status")
    public void thenTheUserWaitsForTheLastLogMessageSuccessStatus() {
        logPageSteps.waitStatusForSuccess();
    }
}
