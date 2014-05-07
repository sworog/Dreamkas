package project.lighthouse.autotests.jbehave;

import jcifs.smb.SmbException;
import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.annotations.When;
import org.xml.sax.SAXException;
import project.lighthouse.autotests.robotClient.InterruptedException_Exception;
import project.lighthouse.autotests.steps.RobotSteps;

import java.io.IOException;
import java.net.MalformedURLException;

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
    public void givenTheRobotWaitsForCompleteExport() throws InterruptedException, MalformedURLException, SmbException {
        robotSteps.checkExportIsDone();
    }

    @Given("the robot waits the import folder become empty")
    public void givenTheRobotWaitsTheImportFolderBecomeEmpty() throws InterruptedException, MalformedURLException, SmbException {
        robotSteps.checkImportIsDone();
    }

    @Given("the robot prepares import purchase data")
    public void givenTheRobotPreparesData() throws InterruptedException, IOException {
        final String fileName = "purchases-data.xml";
        robotSteps.prepareData(fileName);
    }

    @Then("the robot checks xml file in autotests folder for equals fixture file '$fixtureFile'")
    public void thenTheRobotChecksXmlFileInAutotestsFolderForEqualsFixtureFile(String fixtureFile) throws IOException, SAXException {
        robotSteps.checkProductWeightExport(fixtureFile);
    }
}
