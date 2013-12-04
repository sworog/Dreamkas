package project.lighthouse.autotests.jbehave.fixtureSteps.us_55_2;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import project.lighthouse.autotests.fixtures.Us_55_2_Fixture;
import project.lighthouse.autotests.steps.ConsoleCommandSteps;

import javax.xml.parsers.ParserConfigurationException;
import javax.xml.transform.TransformerException;
import javax.xml.xpath.XPathExpressionException;
import java.io.IOException;

public class GivenFixturePrepareSteps {

    @Steps
    ConsoleCommandSteps consoleCommandSteps;

    @Given("the user prepares data for us 55.2 story")
    public void givenTheUserPreparesDataForUs552Story() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException, InterruptedException {
        Us_55_2_Fixture us_55_2_fixture = new Us_55_2_Fixture();
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(us_55_2_fixture.prepareYesterdayDataForShop1().getPath());
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(us_55_2_fixture.prepareTwoDaysAgoForShop1().getPath());
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(us_55_2_fixture.prepareEightDaysAgoDataForShop1().getPath());
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(us_55_2_fixture.prepareYesterdayDataForShop2().getPath());
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(us_55_2_fixture.prepareTwoDaysAgoForShop2().getPath());
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(us_55_2_fixture.prepareEightDaysAgoDataForShop2().getPath());
    }
}
