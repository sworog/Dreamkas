package project.lighthouse.autotests.jbehave.fixtureSteps.sprint_22.us_40_3;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import project.lighthouse.autotests.fixtures.sprint_22.Us_40_3_Fixture;
import project.lighthouse.autotests.steps.ConsoleCommandSteps;

import javax.xml.parsers.ParserConfigurationException;
import javax.xml.transform.TransformerException;
import javax.xml.xpath.XPathExpressionException;
import java.io.IOException;

public class GivenPrepareFixtureSteps {

    @Steps
    ConsoleCommandSteps consoleCommandSteps;

    private Us_40_3_Fixture us_40_3_fixture = new Us_40_3_Fixture();

    @Given("the user prepares import return data for us 40.3 story for product with '$name' name")
    public void givenTheUserPreparesImportCloneDataForUs401Story(String name) throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException, InterruptedException {
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(us_40_3_fixture.prepareReturnData(name));
    }
}
