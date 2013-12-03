package project.lighthouse.autotests.jbehave.departmentManager.reports;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Then;
import project.lighthouse.autotests.steps.departmentManager.ReportsSteps;

public class StoreGrossSaleByHourReportUserSteps {

    @Steps
    ReportsSteps reportsSteps;

    @Then("the user checks the store gross sale by hour report contains correct data")
    public void thenTheUserChecksTheStoreGrossSaleByHourReportContainsCorrectData() {
        reportsSteps.compareWithExampleTable();
    }

    @Then("the user checks the store gross sale by hour report dont contain data on current hour")
    public void thenTheUserChecksTheStoreGrossSaleByHourReportDontContainsDataOnCurrentHour() {
        reportsSteps.notContainsCurrentHour();
    }
}
