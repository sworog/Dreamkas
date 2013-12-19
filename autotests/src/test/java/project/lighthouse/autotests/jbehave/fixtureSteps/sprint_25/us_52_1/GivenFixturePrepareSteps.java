package project.lighthouse.autotests.jbehave.fixtureSteps.sprint_25.us_52_1;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import project.lighthouse.autotests.fixtures.Us_52_1_Fixture;
import project.lighthouse.autotests.steps.ConsoleCommandSteps;

import javax.xml.parsers.ParserConfigurationException;
import javax.xml.transform.TransformerException;
import javax.xml.xpath.XPathExpressionException;
import java.io.File;
import java.io.IOException;

public class GivenFixturePrepareSteps {

    @Steps
    ConsoleCommandSteps consoleCommandSteps;

    @Given("the user prepares fixture for story 52.1 precision 1")
    public void givenTheUserPreparesFixtureForStory521precision1() throws InterruptedException, ParserConfigurationException, IOException, XPathExpressionException, TransformerException {
        File file = new Us_52_1_Fixture().prepareFirstProductData();
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(file.getPath());
    }

    @Given("the user prepares fixture for story 52.1 precision 2")
    public void givenTheUserPreparesFixtureForStory521precision2() throws InterruptedException, ParserConfigurationException, IOException, XPathExpressionException, TransformerException {
        File file = new Us_52_1_Fixture().prepareSecondProductData();
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(file.getPath());
    }

    @Given("the user prepares fixture for story 52.1 precision 3")
    public void givenTheUserPreparesFixtureForStory521precision3() throws InterruptedException, ParserConfigurationException, IOException, XPathExpressionException, TransformerException {
        File file = new Us_52_1_Fixture().prepareThirdProductData();
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(file.getPath());
    }
}
