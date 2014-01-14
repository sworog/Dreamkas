package project.lighthouse.autotests.jbehave;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.annotations.When;
import project.lighthouse.autotests.robotClient.InterruptedException_Exception;
import project.lighthouse.autotests.steps.RobotSteps;

import java.io.IOException;

public class RobotUserSteps {

    @Steps
    RobotSteps robotSteps;

    String uuid;

    @When("the robot starts the test named '$testName' on cashregistry with '$cashIp'")
    public void whenTheRobotsStartTheTestOnCashRegistry(String testName, String cashIp) throws InterruptedException_Exception {
        uuid = robotSteps.runTest(cashIp, testName);
    }

    @Then("the robot waits for the test success status")
    public void thenTheRobotWaitsForSuccessStatus() {
        robotSteps.waitForStatus(uuid);
    }

    @Given("the robot waits for complete export")
    public void givenTheRobotWaitsForCompleteExport() throws InterruptedException {
        robotSteps.checkExportIsDone();
    }

    @Given("the robot waits the import folder become empty")
    public void givenTheRobotWaitsTheImportFolderBecomeEmpty() throws InterruptedException {
        robotSteps.checkImportIsDone();
    }

    @Given("the robot prepares import clone data")
    public void givenTheRoborPreparesImportCloneData() throws IOException, InterruptedException {
        final String fileName = "purchases-clone-data.xml";
        robotSteps.prepareData(fileName);
    }

    @Given("the robot prepares import return data")
    public void givenTheRoborPreparesImportReturnData() throws IOException, InterruptedException {
        final String fileName = "purchases-return-data.xml";
        robotSteps.prepareData(fileName);
    }

    @Given("the robot prepares import sales data for story 52")
    public void givenTheRobotPreparesImportSalesData() throws IOException, InterruptedException {
        final String fileName = "purchases-data-us52.xml";
        robotSteps.prepareData(fileName);
    }
}
