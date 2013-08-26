package project.lighthouse.autotests.jbehave;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import org.jbehave.core.annotations.Then;
import project.lighthouse.autotests.steps.logSteps.LogSteps;

public class LogPageUserSteps {

    @Steps
    LogSteps logSteps;

    @Given("the user opens the log page")
    public void givenTheUserOpensTheLogPage() {
        logSteps.open();
    }

    @Then("the user checks the last log final message is '$expectedMessage'")
    public void thenTheUSerChecksTheLastLogFinalMessage(String expectedMessage) {
        logSteps.assertLastLogFinalMessage(expectedMessage);
    }

    @Then("the user checks the last log title is '$expectedTitle'")
    public void thenTheUserChecksTheLastLogTitle(String expectedTitle) {
        logSteps.assertLastLogTitle(expectedTitle);
    }

    @Then("the user waits for the last log message success status")
    public void thenTheUserWaitsForTheLastLogMessageSuccessStatus() {
        logSteps.waitStatusForSuccess();
    }
}
