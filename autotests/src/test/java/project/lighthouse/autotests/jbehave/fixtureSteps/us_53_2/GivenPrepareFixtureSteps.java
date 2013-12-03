package project.lighthouse.autotests.jbehave.fixtureSteps.us_53_2;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import project.lighthouse.autotests.fixtures.Us_53_2_Fixture;
import project.lighthouse.autotests.steps.ConsoleCommandSteps;

import javax.xml.parsers.ParserConfigurationException;
import javax.xml.transform.TransformerException;
import javax.xml.xpath.XPathExpressionException;
import java.io.IOException;

public class GivenPrepareFixtureSteps {

    @Steps
    ConsoleCommandSteps consoleCommandSteps;

    @Given("the user prepares data for us 53.2 story")
    public void givenTheUserPreparesDataForUs532Story() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException, InterruptedException {
        Us_53_2_Fixture us_53_2_fixture = new Us_53_2_Fixture();
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(us_53_2_fixture.prepareTodayData().getPath());
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(us_53_2_fixture.prepareYesterdayData().getPath());
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(us_53_2_fixture.prepareWeekAgoData().getPath());
    }
}
