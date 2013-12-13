package project.lighthouse.autotests.steps.storeManager.reports.grossSaleByCatalogItems;

import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.pages.storeManager.reports.grossSaleByCatalogItems.CommonGrossSaleByCatalogItemPage;

public class CommonGrossSaleByCatalogItemSteps {

    CommonGrossSaleByCatalogItemPage commonGrossSaleByCatalogItemPage;

    public void compareWithExampleTable(ExamplesTable examplesTable) {
        commonGrossSaleByCatalogItemPage.getGrossSaleByTableObjectCollection().compareWithExampleTable(examplesTable);
    }

    public void clickByLocator(String locator) {
        commonGrossSaleByCatalogItemPage.getGrossSaleByTableObjectCollection().clickByLocator(locator);
    }
}
