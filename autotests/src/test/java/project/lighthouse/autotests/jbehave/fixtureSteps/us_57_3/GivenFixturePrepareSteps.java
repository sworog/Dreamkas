package project.lighthouse.autotests.jbehave.fixtureSteps.us_57_3;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import project.lighthouse.autotests.fixtures.Us_57_3_Fixture;
import project.lighthouse.autotests.steps.ConsoleCommandSteps;

import javax.xml.parsers.ParserConfigurationException;
import javax.xml.transform.TransformerException;
import javax.xml.xpath.XPathExpressionException;
import java.io.IOException;

public class GivenFixturePrepareSteps {

    @Steps
    ConsoleCommandSteps consoleCommandSteps;

    private Us_57_3_Fixture us_57_3_fixture = new Us_57_3_Fixture();

    @Given("the user prepares data for red highlighted checks - today data is bigger than yesterday/weekAgo one for story 57.3")
    public void givenTheUserPreparesDataForRedHighlitedChecksTodayDataIsBiggerThanYesterdayAndWeekAgoOne() throws ParserConfigurationException, TransformerException, XPathExpressionException, IOException, InterruptedException {
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(us_57_3_fixture.getTodayIsBiggerThanYesterdayAndWeekAgoDataSet().prepareTodayData().getPath());
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(us_57_3_fixture.getTodayIsBiggerThanYesterdayAndWeekAgoDataSet().prepareYesterdayData().getPath());
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(us_57_3_fixture.getTodayIsBiggerThanYesterdayAndWeekAgoDataSet().prepareWeekAgoData().getPath());
    }

    @Given("the user prepares data for red highlighted checks - today data is equal yesterday/weekAgo one for story 57.3")
    public void givenTheUserPreparesDataForRedHighLightedChecksTodayDataIsEqualYesterdayWeekAgoOne() throws ParserConfigurationException, TransformerException, XPathExpressionException, IOException, InterruptedException {
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(us_57_3_fixture.getTodayYesterdayWeekAgoDataAreEqualToEachOtherDataSet().prepareTodayData().getPath());
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(us_57_3_fixture.getTodayYesterdayWeekAgoDataAreEqualToEachOtherDataSet().prepareYesterdayData().getPath());
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(us_57_3_fixture.getTodayYesterdayWeekAgoDataAreEqualToEachOtherDataSet().prepareWeekAgoData().getPath());
    }

    @Given("the user prepares data for red highlighted checks - today data is smaller than yesterday and bigger than weekAgo one for story 57.3")
    public void givenTheUserPreparesDataForRedHighLightedChecksTodayDataIsSmallerThanYesterdayAndBiggerThanWeekAgo() throws ParserConfigurationException, TransformerException, XPathExpressionException, IOException, InterruptedException {
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(us_57_3_fixture.getTodayIsSmallerThanYesterdayAndBiggerThanWeekAgoDataSet().prepareTodayData().getPath());
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(us_57_3_fixture.getTodayIsSmallerThanYesterdayAndBiggerThanWeekAgoDataSet().prepareYesterdayData().getPath());
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(us_57_3_fixture.getTodayIsSmallerThanYesterdayAndBiggerThanWeekAgoDataSet().prepareWeekAgoData().getPath());
    }

    @Given("the user prepares data for red highlighted checks - today data is bigger than yesterday and smaller than weekAgo one for story 57.3")
    public void givenTheUserPreparesDataForRedHighLightedChecksTodayDataIsBiggerThanYesterdayAndSmallerThanWeekAgo() throws ParserConfigurationException, TransformerException, XPathExpressionException, IOException, InterruptedException {
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(us_57_3_fixture.getTodayIsBiggerThanYesterdayAndWeekAgoDataSet().prepareTodayData().getPath());
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(us_57_3_fixture.getTodayIsBiggerThanYesterdayAndWeekAgoDataSet().prepareYesterdayData().getPath());
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(us_57_3_fixture.getTodayIsBiggerThanYesterdayAndWeekAgoDataSet().prepareWeekAgoData().getPath());
    }

    @Given("the user prepares data for red highlighted checks - today data is smaller than yesterday and weekAgo one for story 57.3")
    public void givenTheUserPreparesDataForRedHighLightedChecksTodayDataIsSmallerThanYesterdayAndWeekAgo() throws ParserConfigurationException, TransformerException, XPathExpressionException, IOException, InterruptedException {
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(us_57_3_fixture.getTodayIsSmallerThanYesterdayAndWeekAgoDataSet().prepareTodayData().getPath());
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(us_57_3_fixture.getTodayIsSmallerThanYesterdayAndWeekAgoDataSet().prepareYesterdayData().getPath());
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(us_57_3_fixture.getTodayIsSmallerThanYesterdayAndWeekAgoDataSet().prepareWeekAgoData().getPath());
    }
}
