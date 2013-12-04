package project.lighthouse.autotests.jbehave.departmentManager.reports;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import project.lighthouse.autotests.steps.departmentManager.reports.StoreGrossSaleByHourReportSteps;

public class GivenStoreGrossSaleByHourReportUserSteps {

    @Steps
    StoreGrossSaleByHourReportSteps storeGrossSaleByHourReportSteps;

    @Given("the user opens gross sale by hour report page")
    public void givenTheUserOpensGrossSaleByHourReportPage() {
        storeGrossSaleByHourReportSteps.openStoreGrossSaleByHourReportPage();
    }
}
