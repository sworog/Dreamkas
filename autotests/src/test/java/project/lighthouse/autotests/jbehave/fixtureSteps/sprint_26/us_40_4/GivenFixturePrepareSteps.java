package project.lighthouse.autotests.jbehave.fixtureSteps.sprint_26.us_40_4;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import project.lighthouse.autotests.fixtures.sprint_26.Us_40_4_Fixture;
import project.lighthouse.autotests.steps.ConsoleCommandSteps;

import javax.xml.parsers.ParserConfigurationException;
import javax.xml.transform.TransformerException;
import javax.xml.xpath.XPathExpressionException;
import java.io.IOException;

public class GivenFixturePrepareSteps {

    @Steps
    ConsoleCommandSteps consoleCommandSteps;

    private Us_40_4_Fixture us_40_4_fixture = new Us_40_4_Fixture();

    @Given("the user prepares sale purchase for us 40.4 story")
    public void givenTheUserPreparesSalePurchaseForUs404Story() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException, InterruptedException {
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(us_40_4_fixture.prepareSalePurchaseFile().getPath());
    }

    @Given("the user prepares sale purchase return for us 40.4 story")
    public void givenTheUserPreparesSalePurchaseReturnForUs404Story() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException, InterruptedException {
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(us_40_4_fixture.prepareSalePurchaseReturnFile().getPath());
    }

    @Given("the user prepares sale return 1 for us 40.4 story")
    public void givenTheUserPreparesReturnForUs404Story() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException, InterruptedException {
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(us_40_4_fixture.prepareReturnFile().getPath());
    }

    @Given("the user prepares sale return 2 for us 40.4 story")
    public void givenTheUserPreparesReturn2ForUs404Story() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException, InterruptedException {
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(us_40_4_fixture.prepareAnotherReturnFile().getPath());
    }
}
