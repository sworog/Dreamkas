package project.lighthouse.autotests.steps.storeManager.reports;

import junit.framework.Assert;
import junit.framework.AssertionFailedError;
import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.model.ExamplesTable;
import org.joda.time.DateTime;
import project.lighthouse.autotests.fixtures.sprint_24.Us_53_2_Fixture;
import project.lighthouse.autotests.pages.storeManager.reports.StoreGrossSaleByHourReportPage;

public class StoreGrossSaleByHourReportSteps extends ScenarioSteps {

    StoreGrossSaleByHourReportPage storeGrossSaleByHourReportPage;

    @Step
    public void compareWithExampleTable() {
        ExamplesTable examplesTable = new Us_53_2_Fixture().getFixtureExampleTable();
        storeGrossSaleByHourReportPage.getStoreGrossSaleByHourElementCollection().compareWithExampleTable(examplesTable);
    }

    @Step
    public void notContainsCurrentHour() {
        try {
            String dayHours = String.format("%02d:00 — %02d:00", new DateTime().getHourOfDay(), new DateTime().getHourOfDay() + 1);
            storeGrossSaleByHourReportPage.getStoreGrossSaleByHourElementCollection().contains(dayHours);
            String message = String.format("The item '%s' is present!", dayHours);
            Assert.fail(message);
        } catch (AssertionFailedError ignored) {
        }
    }
}
