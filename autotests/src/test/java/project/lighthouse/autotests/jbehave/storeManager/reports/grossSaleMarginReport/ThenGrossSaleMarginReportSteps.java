package project.lighthouse.autotests.jbehave.storeManager.reports.grossSaleMarginReport;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Then;
import project.lighthouse.autotests.steps.storeManager.reports.StoreGrossSaleMarginReportSteps;

public class ThenGrossSaleMarginReportSteps {

    @Steps
    StoreGrossSaleMarginReportSteps storeGrossSaleMarginReportSteps;

    @Then("the user checks the gross sale margin table contains expected value entries")
    public void thenTheUserChecksTheGrossSaleMarginTableContainsExpectedValueEntries() {
        storeGrossSaleMarginReportSteps.compareWithExampleFixture();
    }

    @Then("the user checks the report name is '$reportName'")
    public void thenTheUserChecksTheReportName(String reportName) {
        storeGrossSaleMarginReportSteps.assertReportName(reportName);
    }
}
