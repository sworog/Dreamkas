package project.lighthouse.autotests.jbehave.commercialManager.reports;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Then;
import project.lighthouse.autotests.steps.commercialManager.reports.GrossSaleByStoreReportsSteps;

public class ThenGrossSaleByStoresReportUserSteps {

    @Steps
    GrossSaleByStoreReportsSteps grossSaleByStoreReportsSteps;

    @Then("the user checks the gross sale by stores report has correct data")
    public void thenTheUserChecksTheGrossSaleByStoresReportHasCorrectData() {
        grossSaleByStoreReportsSteps.compareWithExampleTable();
    }

    @Then("the user checks the gross sale by stores report has zero sales")
    public void thenTheUserChecksTheGrossSaleByStoresReportHasZeroSales() {
        grossSaleByStoreReportsSteps.compareWithExampleToCheckZeroSales();
    }
}
