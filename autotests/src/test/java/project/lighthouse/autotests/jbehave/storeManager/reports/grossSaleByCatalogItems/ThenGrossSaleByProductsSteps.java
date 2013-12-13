package project.lighthouse.autotests.jbehave.storeManager.reports.grossSaleByCatalogItems;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.steps.storeManager.reports.grossSaleByCatalogItems.GrossSaleByProductsSteps;

public class ThenGrossSaleByProductsSteps {

    @Steps
    GrossSaleByProductsSteps grossSaleByProductsSteps;

    @Then("the user checks the gross sale by products report contains entry $examplesTable")
    public void thenTheUserChecksTheGrossSaleByProductsReportContainsEntry(ExamplesTable examplesTable) {
        grossSaleByProductsSteps.compareWithExampleTable(examplesTable);
    }

    @Then("the user checks the gross sale by products report contains zero sales")
    public void thenTheUserChecksTheGrossSaleByProductsReportContainsZeroSales() {
        grossSaleByProductsSteps.compareIsZeroSales();
    }
}
