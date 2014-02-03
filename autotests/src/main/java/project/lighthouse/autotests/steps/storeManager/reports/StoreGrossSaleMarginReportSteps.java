package project.lighthouse.autotests.steps.storeManager.reports;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.junit.Assert;
import project.lighthouse.autotests.fixtures.sprint_27.Us_54_1_Fixture;
import project.lighthouse.autotests.fixtures.sprint_28.Us_54_2_Fixture;
import project.lighthouse.autotests.fixtures.sprint_28.Us_54_4_Fixture;
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

    @Step
    public void exactCompareWithExampleTableForStory544() {
        storeGrossSaleMarginReportPage.getGrossMarginTableObjectCollection().exactCompareExampleTable(
                new Us_54_4_Fixture().prepareFixtureExampleTable()
        );
    }

    @Step
    public void exactCompareWithExampleTableSaleDuplicationWithUpdatedProductForStory544() {
        storeGrossSaleMarginReportPage.getGrossMarginTableObjectCollection().exactCompareExampleTable(
                new Us_54_4_Fixture().prepareFixtureExampleTableForSaleDuplicationWithUpdatedProductChecking()
        );
    }

    @Step
    public void exactCompareWithExampleTableSaleDuplicationWithUpdatedPriceForStory544() {
        storeGrossSaleMarginReportPage.getGrossMarginTableObjectCollection().exactCompareExampleTable(
                new Us_54_4_Fixture().prepareFixtureExampleTableForSaleDuplicationWithUpdatedPriceChecking()
        );
    }

    @Step
    public void exactCompareWithExampleTableForStory542IfInvoiceQuantityIsUpdated() {
        storeGrossSaleMarginReportPage.getGrossMarginTableObjectCollection().exactCompareExampleTable(
                new Us_54_2_Fixture().prepareFixtureExampleTable()
        );
    }

    @Step
    public void exactCompareWithExampleTableForStory542IfInvoicePriceIsUpdated() {
        storeGrossSaleMarginReportPage.getGrossMarginTableObjectCollection().exactCompareExampleTable(
                new Us_54_2_Fixture().getExampleTableFixtureIfInvoiceProductPriceIsUpdated()
        );
    }

    @Step
    public void exactCompareWithExampleTableForStory542IfInvoiceDateIsUpdated() {
        storeGrossSaleMarginReportPage.getGrossMarginTableObjectCollection().exactCompareExampleTable(
                new Us_54_2_Fixture().getExampleTableFixtureIfInvoiceDateIsUpdated()
        );
    }
}
