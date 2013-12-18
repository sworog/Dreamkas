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

    @Then("the user checks the gross sale by products report contains correct data if the product barCode is empty")
    public void thenTheUserChecksTheGrossSaleByProductsReportContainsCorrectDataIfTheProductBarCodeIsEmpty() {
        grossSaleByProductsSteps.compareTableIfBarcodeIsEmpty();
    }

    @Then("the user checks the gross sale by products report contains correct data for product with sku 255742")
    public void thenTheUSerChecksTheGrossSaleByProductsReportContainsCorrectDataForProduct1() {
        grossSaleByProductsSteps.compareTableContainsCorrectDataForProduct1();
    }

    @Then("the user checks the gross sale by products report contains correct data for product with sku 255743")
    public void thenTheUSerChecksTheGrossSaleByProductsReportContainsCorrectDataForProduct2() {
        grossSaleByProductsSteps.compareTableContainsCorrectDataForProduct2();
    }

    @Then("the user checks the gross sale by products report contains empty data for product with sku 255742")
    public void thenTheUSerChecksTheGrossSaleByProductsReportContainsEmptyDataForProduct1() {
        grossSaleByProductsSteps.compareTableContainsEmptyDataForProduc1();
    }

    @Then("the user checks the gross sale by products report contains empty data for product with sku 255743")
    public void thenTheUSerChecksTheGrossSaleByProductsReportContainsEmptyDataForProduct2() {
        grossSaleByProductsSteps.compareTableContainsEmptyDataForProduc2();
    }

    @Then("the user checks the table today value by locator '$locator' is not red highlighted")
    public void thenTheUserChecksTheTableTodayValueByLocatorIsNotRedHighLighted(String locator) {
        grossSaleByProductsSteps.checkTheTableValueColorIsNotRed(locator);
    }

    @Then("the user checks the table today value by locator '$locator' is red highlighted")
    public void thenTheUserChecksTheTableTodayValueByLocatorIsRedHighlighted(String locator) {
        grossSaleByProductsSteps.checkTheTableValueIsRed(locator);
    }
}
