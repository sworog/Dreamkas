package project.lighthouse.autotests.jbehave.fixtureSteps.sprint_25.us_57_4;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import project.lighthouse.autotests.fixtures.sprint_25.Us_57_4_Fixture;
import project.lighthouse.autotests.steps.ConsoleCommandSteps;

import javax.xml.parsers.ParserConfigurationException;
import javax.xml.transform.TransformerException;
import javax.xml.xpath.XPathExpressionException;
import java.io.IOException;

public class GivenFixturePrepareSteps {

    @Steps
    ConsoleCommandSteps consoleCommandSteps;

    private Us_57_4_Fixture us_57_4_fixture = new Us_57_4_Fixture();

    @Given("the user prepares data for us 57.4 story")
    public void givenTheUserPreparesDataForUs552Story() throws ParserConfigurationException, IOException, XPathExpressionException, TransformerException, InterruptedException {
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(us_57_4_fixture.prepareTodayDataForShop1().getPath());
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(us_57_4_fixture.prepareYesterdayDataForShop1().getPath());
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(us_57_4_fixture.prepareWeekAgoDataForShop1().getPath());
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(us_57_4_fixture.prepareTodayDataForShop2().getPath());
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(us_57_4_fixture.prepareYesterdayDataForShop2().getPath());
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(us_57_4_fixture.prepareWeekAgoDataForShop2().getPath());
    }

    @Given("the user prepares data for red highlighted checks - today data is bigger than yesterday/weekAgo one")
    public void givenTheUserPreparesDataForRedHighlitedChecksTodayDataIsBiggerThanYesterdayAndWeekAgoOne() throws ParserConfigurationException, TransformerException, XPathExpressionException, IOException, InterruptedException {
        Us_57_4_Fixture.TodayIsBiggerThanYesterdayAndWeekAgoDataSet todayIsBiggerThanYesterdayAndWeekAgoDataSet = us_57_4_fixture.getTodayIsBiggerThanYesterdayAndWeekAgoDataSet();
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(todayIsBiggerThanYesterdayAndWeekAgoDataSet.prepareTodayData().getPath());
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(todayIsBiggerThanYesterdayAndWeekAgoDataSet.prepareYesterdayData().getPath());
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(todayIsBiggerThanYesterdayAndWeekAgoDataSet.prepareWeekAgoData().getPath());
    }

    @Given("the user prepares data for red highlighted checks - today data is equal yesterday/weekAgo one")
    public void givenTheUserPreparesDataForRedHighLightedChecksTodayDataIsEqualYesterdayWeekAgoOne() throws ParserConfigurationException, TransformerException, XPathExpressionException, IOException, InterruptedException {
        Us_57_4_Fixture.TodayYesterdayWeekAgoDataAreEqualToEachOtherDataSet todayYesterdayWeekAgoDataAreEqualToEachOtherDataSet = us_57_4_fixture.getTodayYesterdayWeekAgoDataAreEqualToEachOtherDataSet();
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(todayYesterdayWeekAgoDataAreEqualToEachOtherDataSet.prepareTodayData().getPath());
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(todayYesterdayWeekAgoDataAreEqualToEachOtherDataSet.prepareYesterdayData().getPath());
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(todayYesterdayWeekAgoDataAreEqualToEachOtherDataSet.prepareWeekAgoData().getPath());
    }

    @Given("the user prepares data for red highlighted checks - today data is smaller than yesterday and bigger than weekAgo one")
    public void givenTheUserPreparesDataForRedHighLightedChecksTodayDataIsSmallerThanYesterdayAndBiggerThanWeekAgo() throws ParserConfigurationException, TransformerException, XPathExpressionException, IOException, InterruptedException {
        Us_57_4_Fixture.TodayIsSmallerThanYesterdayAndBiggerThanWeekAgoDataSet todayIsSmallerThanYesterdayAndBiggerThanWeekAgoDataSet = us_57_4_fixture.getTodayIsSmallerThanYesterdayAndBiggerThanWeekAgoDataSet();
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(todayIsSmallerThanYesterdayAndBiggerThanWeekAgoDataSet.prepareTodayData().getPath());
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(todayIsSmallerThanYesterdayAndBiggerThanWeekAgoDataSet.prepareYesterdayData().getPath());
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(todayIsSmallerThanYesterdayAndBiggerThanWeekAgoDataSet.prepareWeekAgoData().getPath());
    }

    @Given("the user prepares data for red highlighted checks - today data is bigger than yesterday and smaller than weekAgo one")
    public void givenTheUserPreparesDataForRedHighLightedChecksTodayDataIsBiggerThanYesterdayAndSmallerThanWeekAgo() throws ParserConfigurationException, TransformerException, XPathExpressionException, IOException, InterruptedException {
        Us_57_4_Fixture.TodayIsBiggerThanYesterdayAndSmallerThanWeekAgoDataSet todayIsBiggerThanYesterdayAndSmallerThanWeekAgoDataSet = us_57_4_fixture.getTodayIsBiggerThanYesterdayAndSmallerThanWeekAgoDataSet();
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(todayIsBiggerThanYesterdayAndSmallerThanWeekAgoDataSet.prepareTodayData().getPath());
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(todayIsBiggerThanYesterdayAndSmallerThanWeekAgoDataSet.prepareYesterdayData().getPath());
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(todayIsBiggerThanYesterdayAndSmallerThanWeekAgoDataSet.prepareWeekAgoData().getPath());
    }

    @Given("the user prepares data for red highlighted checks - today data is smaller than yesterday and weekAgo one")
    public void givenTheUserPreparesDataForRedHighLightedChecksTodayDataIsSmallerThanYesterdayAndWeekAgo() throws ParserConfigurationException, TransformerException, XPathExpressionException, IOException, InterruptedException {
        Us_57_4_Fixture.TodayIsSmallerThanYesterdayAndWeekAgoDataSet todayIsSmallerThanYesterdayAndWeekAgoDataSet = us_57_4_fixture.getTodayIsSmallerThanYesterdayAndWeekAgoDataSet();
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(todayIsSmallerThanYesterdayAndWeekAgoDataSet.prepareTodayData().getPath());
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(todayIsSmallerThanYesterdayAndWeekAgoDataSet.prepareYesterdayData().getPath());
        consoleCommandSteps.runCapAutoTestsSymfonyImportSalesLocalCommand(todayIsSmallerThanYesterdayAndWeekAgoDataSet.prepareWeekAgoData().getPath());
    }
}
