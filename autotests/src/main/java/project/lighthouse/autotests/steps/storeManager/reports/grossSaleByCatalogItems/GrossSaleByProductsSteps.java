package project.lighthouse.autotests.steps.storeManager.reports.grossSaleByCatalogItems;

import net.thucydides.core.annotations.Step;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.fixtures.Us_57_4_Fixture;
import project.lighthouse.autotests.pages.storeManager.reports.grossSaleByCatalogItems.GrossSaleByProductsPage;

public class GrossSaleByProductsSteps {

    GrossSaleByProductsPage grossSaleByProductsPage;

    @Step
    public void compareWithExampleTable(ExamplesTable examplesTable) {
        grossSaleByProductsPage.getGrossSaleByProductsObjectCollection().compareWithExampleTable(examplesTable);
    }

    @Step
    public void compareIsZeroSales() {
        ExamplesTable examplesTable = new Us_57_4_Fixture().getFixtureExampleTable();
        grossSaleByProductsPage.getGrossSaleByProductsObjectCollection().compareWithExampleTable(examplesTable);
    }
}
