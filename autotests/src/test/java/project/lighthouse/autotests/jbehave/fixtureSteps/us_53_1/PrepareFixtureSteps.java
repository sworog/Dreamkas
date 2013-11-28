package project.lighthouse.autotests.jbehave.fixtureSteps.us_53_1;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import project.lighthouse.autotests.fixtures.Us_53_1_Fixture;
import project.lighthouse.autotests.steps.ConsoleCommandSteps;

import javax.xml.parsers.ParserConfigurationException;
import javax.xml.transform.TransformerException;
import javax.xml.xpath.XPathExpressionException;
import java.io.File;
import java.io.IOException;

public class PrepareFixtureSteps {

    @Steps
    ConsoleCommandSteps consoleCommandSteps;

    @Given("the user prepares fixture on today from data set 1")
    public void givenTheUserPreparesFixtureOnTodayFromDataSet1() throws InterruptedException, ParserConfigurationException, IOException, XPathExpressionException, TransformerException {
        File file = new Us_53_1_Fixture().prepareTodayDataFromDataSet1();
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(file.getPath());
    }

    @Given("the user prepares fixture on yesterday from data set 1")
    public void givenTheUserPreparesFixtureOnYesterdayFromDataSet1() throws IOException, InterruptedException, TransformerException, XPathExpressionException, ParserConfigurationException {
        File file = new Us_53_1_Fixture().prepareYesterdayDataFromDataSet1();
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(file.getPath());
    }

    @Given("the user prepares fixture on last week from data set 1")
    public void givenTheUserPreparesFixtureOnLastWeekFromDataSet1() throws IOException, InterruptedException, TransformerException, XPathExpressionException, ParserConfigurationException {
        File file = new Us_53_1_Fixture().prepareLastWeekDataFromDataSet1();
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(file.getPath());
    }

    @Given("the user prepares fixture for 'Today Gross sale is more than yesterday one' scenario")
    public void givenTheUserPreparesFixtureForTodayGrossSaleIsMoreTahnYesterdayOneScenario() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException, InterruptedException {
        File todayDataFile = new Us_53_1_Fixture().prepareTodayDataFromDataSet2();
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(todayDataFile.getPath());

        File yesterdayDataFile = new Us_53_1_Fixture().prepareYesterdayDataFromDataSet1();
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(yesterdayDataFile.getPath());
    }

    @Given("the user prepares fixture for 'Today Gross sale is less than yesterday one' scenario")
    public void givenTheUserPreparesFixtureForTodayGrossSaleIsLessThanYesterdayOneScenario() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException, InterruptedException {
        File todayDataFile = new Us_53_1_Fixture().prepareTodayDataFromDataSet1();
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(todayDataFile.getPath());

        File yesterdayDataFile = new Us_53_1_Fixture().prepareYesterdayDataFromDataSet2();
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(yesterdayDataFile.getPath());
    }

    @Given("the user prepares fixture for 'Today Gross sale is equal yesterday one' scenario")
    public void givenTheUserPreparesFixtureForTodayGrossSaleIsEqualYesterdayOneScenario() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException, InterruptedException {
        File todayDataFile = new Us_53_1_Fixture().prepareTodayDataFromDataSet1();
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(todayDataFile.getPath());

        File yesterdayDataFile = new Us_53_1_Fixture().prepareYesterdayDataFromDataSet1();
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(yesterdayDataFile.getPath());
    }

    @Given("the user prepares fixture for 'Today Gross sale is more than last week one' scenario")
    public void givenTheUserPreparesFixtureForTodayGrossSaleIsMoreThanLastWeekOneScenario() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException, InterruptedException {
        File todayDataFile = new Us_53_1_Fixture().prepareTodayDataFromDataSet2();
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(todayDataFile.getPath());

        File yesterdayDataFile = new Us_53_1_Fixture().prepareLastWeekDataFromDataSet1();
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(yesterdayDataFile.getPath());
    }

    @Given("the user prepares fixture for 'Today Gross sale is less than last week one' scenario")
    public void givenTheUserPreparesFixtureForTodayGrossSaleIsLessThanLastWeekOneScenario() throws IOException, InterruptedException, TransformerException, XPathExpressionException, ParserConfigurationException {
        File todayDataFile = new Us_53_1_Fixture().prepareTodayDataFromDataSet1();
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(todayDataFile.getPath());

        File yesterdayDataFile = new Us_53_1_Fixture().prepareLatWeekDataFromDataSet2();
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(yesterdayDataFile.getPath());
    }

    @Given("the user prepares fixture for 'Today Gross sale is eqaul last week one' scenario")
    public void givenTheUserPreparesFixtureForTodayGrossSaleIsEqualLastWeekOneScenario() throws IOException, InterruptedException, TransformerException, XPathExpressionException, ParserConfigurationException {
        File todayDataFile = new Us_53_1_Fixture().prepareTodayDataFromDataSet1();
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(todayDataFile.getPath());

        File yesterdayDataFile = new Us_53_1_Fixture().prepareLastWeekDataFromDataSet1();
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(yesterdayDataFile.getPath());
    }
}
