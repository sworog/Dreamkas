package project.lighthouse.autotests.steps.storeManager.reports.grossSaleByCatalogItems;

import net.thucydides.core.annotations.Step;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.pages.storeManager.reports.grossSaleByCatalogItems.CommonGrossSaleByCatalogItemPage;

public class CommonGrossSaleByCatalogItemSteps {

    CommonGrossSaleByCatalogItemPage commonGrossSaleByCatalogItemPage;

    @Step
    public void compareWithExampleTable(ExamplesTable examplesTable) {
        commonGrossSaleByCatalogItemPage.getGrossSaleByTableObjectCollection().compareWithExampleTable(examplesTable);
    }

    @Step
    public void clickByLocator(String locator) {
        commonGrossSaleByCatalogItemPage.getGrossSaleByTableObjectCollection().clickByLocator(locator);
    }
}
