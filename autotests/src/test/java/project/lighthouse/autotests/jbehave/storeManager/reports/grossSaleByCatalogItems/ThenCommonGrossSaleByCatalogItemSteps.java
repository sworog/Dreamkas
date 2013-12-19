package project.lighthouse.autotests.jbehave.storeManager.reports.grossSaleByCatalogItems;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.steps.storeManager.reports.grossSaleByCatalogItems.CommonGrossSaleByCatalogItemSteps;

public class ThenCommonGrossSaleByCatalogItemSteps {

    @Steps
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

    @Then("the user checks the gross sale by subCategory report contains entry with zero sales value")
    public void thenTheUserChecksTheGrossSaleBySubCategoryReportContainsEntryWithZeroSalesValue() {
        commonGrossSaleByCatalogItemSteps.compareWithExampleTableIncludingZeroSales();
    }

    @Then("the user checks the subCategory today entry value by locator '$locator' is not red highlighted")
    public void thenTheUserChecksTheTableTodayValueByLocatorIsNotRedHighLighted(String locator) {
        commonGrossSaleByCatalogItemSteps.checkTheTableValueColorIsNotRed(locator);
    }

    @Then("the user checks the subCategory today entry value by locator '$locator' is red highlighted")
    public void thenTheUserChecksTheTableTodayValueByLocatorIsRedHighlighted(String locator) {
        commonGrossSaleByCatalogItemSteps.checkTheTableValueIsRed(locator);
    }

    @Then("the user checks the gross sale by subCategory report contains correct data for defaultCategory-s25u573 of shop 25573")
    public void thenTheUserChecksTheGrossSaleBySubCategoryReportForSubCategory1Shop1() {
        commonGrossSaleByCatalogItemSteps.compareWithExampleTableForSubCategory1Shop1();
    }

    @Then("the user checks the gross sale by subCategory report contains correct data for defaultCategory-s25u5731 of shop 25573")
    public void thenTheUserChecksTheGrossSaleBySubCategoryReportForSubCategory2Shop1() {
        commonGrossSaleByCatalogItemSteps.compareWithExampleTableForSubCategory2Shop1();
    }

    @Then("the user checks the gross sale by subCategory report contains correct data for defaultCategory-s25u573 of shop 255731")
    public void thenTheUserChecksTheGrossSaleBySubCategoryReportForSubCategory1Shop2() {
        commonGrossSaleByCatalogItemSteps.compareWithExampleTableForSubCategory1Shop2();
    }

    @Then("the user checks the gross sale by subCategory report contains correct data for defaultCategory-s25u5731 of shop 255731")
    public void thenTheUserChecksTheGrossSaleBySubCategoryReportForSubCategory2Shop2() {
        commonGrossSaleByCatalogItemSteps.compareWithExampleTableForSubCategory2Shop2();
    }
}
