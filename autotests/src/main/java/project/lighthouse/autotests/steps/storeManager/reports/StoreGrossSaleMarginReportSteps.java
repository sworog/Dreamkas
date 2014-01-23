package project.lighthouse.autotests.steps.storeManager.reports;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.junit.Assert;
import project.lighthouse.autotests.fixtures.sprint_27.Us_54_1_Fixture;
import project.lighthouse.autotests.pages.storeManager.reports.StoreGrossSaleMarginReportPage;

public class StoreGrossSaleMarginReportSteps extends ScenarioSteps {

    StoreGrossSaleMarginReportPage storeGrossSaleMarginReportPage;

    @Step
    public void compareWithExampleFixture() {
        storeGrossSaleMarginReportPage.getGrossMarginTableObjectCollection().exactCompareExampleTable(
                new Us_54_1_Fixture().prepareFixtureExampleTable()
        );
    }

    @Step
    public void compareWithExampleForFiveDaysAgo() {
        storeGrossSaleMarginReportPage.getGrossMarginTableObjectCollection().exactCompareExampleTable(
                new Us_54_1_Fixture().prepareFixtureExampleTableForFiveDaysAgo()
        );
    }

    @Step
    public void assertReportName(String reportName) {
        Assert.assertEquals(reportName, storeGrossSaleMarginReportPage.getReportName());
    }

    @Step
    public void notContainsValue() {
        storeGrossSaleMarginReportPage.getGrossMarginTableObjectCollection().notContains(
                new Us_54_1_Fixture().getTodayDate()
        );
    }

    @Step
    public void exactCompareWithExampleTableForDelayedPurchases() {
        storeGrossSaleMarginReportPage.getGrossMarginTableObjectCollection().exactCompareExampleTable(
                new Us_54_1_Fixture().prepareFixtureExampleTableWithDelayedPurchase()
        );
    }
}
