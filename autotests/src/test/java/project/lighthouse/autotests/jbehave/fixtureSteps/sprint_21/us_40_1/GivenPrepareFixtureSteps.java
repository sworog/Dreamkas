package project.lighthouse.autotests.jbehave.fixtureSteps.sprint_21.us_40_1;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import project.lighthouse.autotests.fixtures.sprint_21.Us_40_1_Fixture;
import project.lighthouse.autotests.steps.ConsoleCommandSteps;

import javax.xml.parsers.ParserConfigurationException;
import javax.xml.transform.TransformerException;
import javax.xml.xpath.XPathExpressionException;
import java.io.IOException;

public class GivenPrepareFixtureSteps {

    @Steps
    ConsoleCommandSteps consoleCommandSteps;

    private Us_40_1_Fixture us_40_1_fixture = new Us_40_1_Fixture();

    @Given("the user prepares import clone data for us 40.1 story")
    public void givenTheUserPreparesImportCloneDataForUs401Story() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException, InterruptedException {
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(us_40_1_fixture.getSaleImportCloneDataFixture().getPath());
    }
}
