package project.lighthouse.autotests.jbehave.fixtureSteps.sprint_27.us_54_3;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import project.lighthouse.autotests.fixtures.sprint_27.Us_54_1_Fixture;
import project.lighthouse.autotests.steps.ConsoleCommandSteps;

import javax.xml.parsers.ParserConfigurationException;
import javax.xml.transform.TransformerException;
import javax.xml.xpath.XPathExpressionException;
import java.io.IOException;

public class GivenFixturePrepareSteps {

    @Steps
    ConsoleCommandSteps consoleCommandSteps;

    private Us_54_1_Fixture us_54_1_fixture = new Us_54_1_Fixture();

    @Given("the user prepares yesterday delayed purchase for us 54.3 story")
    public void givenTheUserPreparesYesterdayDelayedPurchasesForStory543() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException, InterruptedException {
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(us_54_1_fixture.getYesterdayDelayedPurchaseFixture().getPath());
    }
}
