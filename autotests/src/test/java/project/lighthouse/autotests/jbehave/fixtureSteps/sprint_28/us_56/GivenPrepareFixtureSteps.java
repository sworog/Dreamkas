package project.lighthouse.autotests.jbehave.fixtureSteps.sprint_28.us_56;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import project.lighthouse.autotests.fixtures.sprint_28.Us_56_Fixture;
import project.lighthouse.autotests.steps.ConsoleCommandSteps;

import javax.xml.parsers.ParserConfigurationException;
import javax.xml.transform.TransformerException;
import javax.xml.xpath.XPathExpressionException;
import java.io.IOException;

public class GivenPrepareFixtureSteps {

    @Steps
    ConsoleCommandSteps consoleCommandSteps;

    private Us_56_Fixture us_56_fixture = new Us_56_Fixture();

    @Given("the user prepares yesterday purchases for us 56 story")
    public void givenTheUserPreparesYesterdayPurchasesForStory56() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException, InterruptedException {
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(us_56_fixture.getYesterdayPurchasesFixture().getPath());
    }
}
