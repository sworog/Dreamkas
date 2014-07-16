package project.lighthouse.autotests.jbehave.deprecated.commercialManager.reports.grossSaleByStores;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import project.lighthouse.autotests.steps.deprecated.commercialManager.reports.GrossSaleByStoresReportsSteps;

public class GivenGrossSaleByStoresReportUserSteps {

    @Steps
    GrossSaleByStoresReportsSteps grossSaleByStoresReportsSteps;

    @Given("the user opens gross sale by stores report page")
    public void givenTheUserOpensGrossSaleByStoresReportPage() {
        grossSaleByStoresReportsSteps.openGrossSaleByStoresReportsPage();
    }
}
