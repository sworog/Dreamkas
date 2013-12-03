package project.lighthouse.autotests.steps.departmentManager;

import junit.framework.Assert;
import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import org.joda.time.DateTime;
import project.lighthouse.autotests.fixtures.Us_53_2_Fixture;
import project.lighthouse.autotests.pages.departmentManager.reports.StoreGrossSaleByHourReportPage;

public class ReportsSteps extends ScenarioSteps {

    StoreGrossSaleByHourReportPage storeGrossSaleByHourReportPage;

    @Step
    public void compareWithExampleTable() {
        ExamplesTable examplesTable = new Us_53_2_Fixture().getFixtureExampleTable();
        storeGrossSaleByHourReportPage.getStoreGrossSaleByHourElementCollection().compareWithExampleTable(examplesTable);
    }

    @Step
    public void notContainsCurrentHour() {
        try {
            storeGrossSaleByHourReportPage.getStoreGrossSaleByHourElementCollection().contains(String.format("%02d:00", new DateTime().getHourOfDay()));
            Assert.fail("The item is present!");
        } catch (Exception ignored) {
        }
    }
}
