package project.lighthouse.autotests.jbehave.storeManager.reports.grossSaleByCatalogItems;

import org.jbehave.core.annotations.Then;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.steps.storeManager.reports.grossSaleByCatalogItems.CommonGrossSaleByCatalogItemSteps;

public class ThenCommonGrossSaleByCatalogItemSteps {

    CommonGrossSaleByCatalogItemSteps commonGrossSaleByCatalogItemSteps;

    @Then("the user checks the gross sale by group report contains entry $examplesTable")
    public void thenTheUserChecksTheGrossSaleByGroupReportContainsEntry(ExamplesTable examplesTable) {
        commonGrossSaleByCatalogItemSteps.compareWithExampleTable(examplesTable);
    }

    @Then("the user checks the gross sale by category report contains entry $examplesTable")
    public void thenTheUserChecksTheGrossSaleByCategoryReportContainsEntry(ExamplesTable examplesTable) {
        commonGrossSaleByCatalogItemSteps.compareWithExampleTable(examplesTable);
    }

    @Then("the user checks the gross sale by subCategory report contains entry $examplesTable")
    public void thenTheUserChecksTheGrossSaleBySubCategoryReportContainsEntry(ExamplesTable examplesTable) {
        commonGrossSaleByCatalogItemSteps.compareWithExampleTable(examplesTable);
    }
}
