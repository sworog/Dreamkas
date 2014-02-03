package project.lighthouse.autotests.jbehave.fixtureSteps.sprint_28.us_54_4;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import project.lighthouse.autotests.fixtures.sprint_28.Us_54_4_Fixture;
import project.lighthouse.autotests.steps.ConsoleCommandSteps;

import javax.xml.parsers.ParserConfigurationException;
import javax.xml.transform.TransformerException;
import javax.xml.xpath.XPathExpressionException;
import java.io.IOException;

public class GivenFixturePrepareSteps {

    @Steps
    ConsoleCommandSteps consoleCommandSteps;

    private Us_54_4_Fixture us_54_4_fixture = new Us_54_4_Fixture();

    @Given("the user prepares yesterday purchases for us 54.4 story")
    public void givenTheUserPreparesYesterdayPurchasesForStory541() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException, InterruptedException {
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(us_54_4_fixture.getYesterdayPurchasesFixture().getPath());
    }

    @Given("the user prepares two days ago purchases for us 54.4 story")
    public void givenTheUserPreparesTwoDaysAgoPurchasesForStory541() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException, InterruptedException {
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(us_54_4_fixture.getTwoDaysAgoPurchasesFixture().getPath());
    }

    @Given("the user prepares yesterday purchases duplication with updated product for us 54.4 story")
    public void givenTheUserPreparesYesterdayPurchasesDuplicationWithUpdatedProductForStory541() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException, InterruptedException {
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(us_54_4_fixture.getYesterdayPurchasesDuplicationFixtureWithUpdatedProduct().getPath());
    }

    @Given("the user prepares yesterday purchases duplication with updated price for us 54.4 story")
    public void givenTheUserPreparesYesterdayPurchasesDuplicationWithUpdatedPriceForStory541() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException, InterruptedException {
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(us_54_4_fixture.getYesterdayPurchasesDuplicationFixtureWithUpdatedPrice().getPath());
    }
}
