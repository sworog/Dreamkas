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
        storeGrossSaleMarginReportPage.getGrossMarginTableObjectCollection().compareWithExampleTable(
                new Us_54_1_Fixture().prepareFixtureExampleTable()
        );
    }

    @Step
    public void assertReportName(String reportName) {
        Assert.assertEquals(reportName, storeGrossSaleMarginReportPage.getReportName());
    }
}
