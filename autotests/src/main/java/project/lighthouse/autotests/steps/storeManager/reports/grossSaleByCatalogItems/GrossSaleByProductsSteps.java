package project.lighthouse.autotests.steps.storeManager.reports.grossSaleByCatalogItems;

import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.pages.storeManager.reports.grossSaleByCatalogItems.GrossSaleByProductsPage;

public class GrossSaleByProductsSteps {

    GrossSaleByProductsPage grossSaleByProductsPage;

    public void compareWithExampleTable(ExamplesTable examplesTable) {
        grossSaleByProductsPage.getGrossSaleByProductsObjectCollection().compareWithExampleTable(examplesTable);
    }
}
