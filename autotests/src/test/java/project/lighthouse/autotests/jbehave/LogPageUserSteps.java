package project.lighthouse.autotests.jbehave;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Alias;
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

    @Then("the user checks the last log product is '$productName'")
    @Alias("the user checks the last log product is <productName>")
    public void thenTheUSerChecksTheLastLogFinalMessage(String productName) {
        logSteps.assertLastRecalcLogProduct(productName);
    }

    @Then("the user checks the last log title is '$expectedTitle'")
    public void thenTheUserChecksTheLastLogTitle(String expectedTitle) {
        logSteps.assertLastRecalcLogTitle(expectedTitle);
    }

    @Then("the user checks the last log status text is '$expectedStatusText'")
    public void thenTheUserChecksTheLastLogStatusText(String expectedStatusText) {
        logSteps.assertLastRecalcLogStatusText(expectedStatusText);
    }

    @Then("the user waits for the last log message success status")
    public void thenTheUserWaitsForTheLastLogMessageSuccessStatus() {
        logSteps.waitLastRecalcLogMessageSuccessStatus();
    }

    //Set10 export

    @Then("the user checks the last set10 export log title is '$expectedTitle'")
    public void thenTheUserChecksTheLastSet10ExportLogTitle(String expectedTitle) {
        logSteps.assertLastSet10ExportRecalcLogTitle(expectedTitle);
    }

    @Then("the user checks the last set10 export log status text is '$expectedStatusText'")
    public void thenTheUserChecksTheLastSet10ExportLogStatusText(String expectedStatusText) {
        logSteps.assertLastSet10ExportRecalcLogStatusText(expectedStatusText);
    }

    @Then("the user waits for the last set10 export log message success status")
    public void thenTheUserWaitsForTheLastSet10ExportLogMessageSuccessStatus() {
        logSteps.waitLastSet10ExportProductLogMessageSuccessStatus();
    }
}
