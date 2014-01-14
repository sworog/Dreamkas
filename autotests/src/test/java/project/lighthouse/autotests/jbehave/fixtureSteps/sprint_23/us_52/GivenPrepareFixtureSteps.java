package project.lighthouse.autotests.jbehave.fixtureSteps.sprint_23.us_52;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import project.lighthouse.autotests.fixtures.sprint_23.Us_52_Fixture;
import project.lighthouse.autotests.steps.ConsoleCommandSteps;

import javax.xml.parsers.ParserConfigurationException;
import javax.xml.transform.TransformerException;
import javax.xml.xpath.XPathExpressionException;
import java.io.IOException;

public class GivenPrepareFixtureSteps {

    @Steps
    ConsoleCommandSteps consoleCommandSteps;

    private Us_52_Fixture us_52_fixture = new Us_52_Fixture();

    @Given("the user prepares import sales data for story 52")
    public void givenTheUserPreparesImportCloneDataForUs52Story() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException, InterruptedException {
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(us_52_fixture.getImportSaleDataFixture().getPath());
    }
}
