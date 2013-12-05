package project.lighthouse.autotests.jbehave.commercialManager.reports.grossSaleByStores;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Then;
import project.lighthouse.autotests.steps.commercialManager.reports.GrossSaleByStoresReportsSteps;

public class ThenGrossSaleByStoresReportUserSteps {

    @Steps
    GrossSaleByStoresReportsSteps grossSaleByStoresReportsSteps;

    @Then("the user checks the gross sale by stores report has correct data")
    public void thenTheUserChecksTheGrossSaleByStoresReportHasCorrectData() {
        grossSaleByStoresReportsSteps.compareWithExampleTable();
    }

    @Then("the user checks the gross sale by stores report has zero sales")
    public void thenTheUserChecksTheGrossSaleByStoresReportHasZeroSales() {
        grossSaleByStoresReportsSteps.compareWithExampleToCheckZeroSales();
    }

    @Then("the user checks the gross sale by stores report table yesterday row name")
    public void thenTheUserChecksTheGrossSaleByStoresReportTableYesterdayRowName() {
        grossSaleByStoresReportsSteps.assertYesterdayRowName();
    }

    @Then("the user checks the gross sale by stores report table two days ago row name")
    public void thenTheUserChecksTheGrossSaleByStoresReportTableTwoDaysAgoRowName() {
        grossSaleByStoresReportsSteps.assertTwoDaysAgoRowName();
    }

    @Then("the user checks the gross sale by stores report table eight days ago row name")
    public void thenTheUserChecksTheGrossSaleByStoresReportTableEightDaysAgoRowName() {
        grossSaleByStoresReportsSteps.assertEightDaysAgoRowName();
    }

    @Then("the user checks the gross sale by stores report table yesterday row sort")
    public void thenTheUserChecksTheGrossSaleByStoresReportTableYesterdayRowsSort() {
        grossSaleByStoresReportsSteps.assertYesterdayRowSort();
    }

    @Then("the user checks the gross sale by stores report table two days ago row sort")
    public void thenTheUserChecksTheGrossSaleByStoresReportTableTwoDaysAgoRowsSort() {
        grossSaleByStoresReportsSteps.assertTwoDaysAgoRowSort();
    }

    @Then("the user checks the gross sale by stores report table eight days ago row sort")
    public void thenTheUserChecksTheGrossSaleByStoresReportTableEightDaysAgoRowsSort() {
        grossSaleByStoresReportsSteps.assertEightDaysAgoRowSort();
    }
}
