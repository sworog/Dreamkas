package project.lighthouse.autotests.jbehave.fixtureSteps.us_57_4;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import project.lighthouse.autotests.fixtures.Us_57_4_Fixture;
import project.lighthouse.autotests.steps.ConsoleCommandSteps;

import javax.xml.parsers.ParserConfigurationException;
import javax.xml.transform.TransformerException;
import javax.xml.xpath.XPathExpressionException;
import java.io.IOException;

public class GivenFixturePrepareSteps {

    @Steps
    ConsoleCommandSteps consoleCommandSteps;

    @Given("the user prepares data for us 57.4 story")
    public void givenTheUserPreparesDataForUs552Story() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException, InterruptedException {
        Us_57_4_Fixture us_57_4_fixture = new Us_57_4_Fixture();
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(us_57_4_fixture.prepareTodayDataForShop1().getPath());
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(us_57_4_fixture.prepareYesterdayDataForShop1().getPath());
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(us_57_4_fixture.prepareWeekAgoDataForShop1().getPath());
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(us_57_4_fixture.prepareTodayDataForShop2().getPath());
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(us_57_4_fixture.prepareYesterdayDataForShop2().getPath());
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(us_57_4_fixture.prepareWeekAgoDataForShop2().getPath());
    }
}
