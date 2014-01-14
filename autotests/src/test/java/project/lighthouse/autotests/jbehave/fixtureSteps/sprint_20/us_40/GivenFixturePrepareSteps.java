package project.lighthouse.autotests.jbehave.fixtureSteps.sprint_20.us_40;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import project.lighthouse.autotests.fixtures.sprint_20.Us_40_Fixture;
import project.lighthouse.autotests.steps.ConsoleCommandSteps;

import javax.xml.parsers.ParserConfigurationException;
import javax.xml.transform.TransformerException;
import javax.xml.xpath.XPathExpressionException;
import java.io.IOException;

public class GivenFixturePrepareSteps {

    @Steps
    ConsoleCommandSteps consoleCommandSteps;

    private Us_40_Fixture us_40_fixture = new Us_40_Fixture();

    @Given("the user prepares import purchase data for us 40 story")
    public void givenTheUserPreparesSalePurchaseForUs40Story() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException, InterruptedException {
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(us_40_fixture.getFixtureFile().getPath());
    }

    @Given("the user prepares import purchase data with no such product for us 40 story")
    public void givenTheUserPreparesSalePurchaseWithNoSuchProductForUs40Story() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException, InterruptedException {
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(us_40_fixture.getFixtureFileWithNoSuchProduct().getPath());
    }

    @Given("the user prepares import purchase data with no such store for us 40 story")
    public void givenTheUserPreparesSalePurchaseWithNoSuchStoreForUs40Story() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException, InterruptedException {
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(us_40_fixture.getFixtureFileWithNoExistStore().getPath());
    }

    @Given("the user prepares import purchase data with corrupted data for us 40 story")
    public void givenTheUserPreparesSalePurchaseWithCorruptedDataForUs40Story() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException, InterruptedException {
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(us_40_fixture.getFixtureFileWithCorruptedData().getPath());
    }
}
