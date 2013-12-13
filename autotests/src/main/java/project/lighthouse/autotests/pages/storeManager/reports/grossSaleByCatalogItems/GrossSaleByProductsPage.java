package project.lighthouse.autotests.pages.storeManager.reports.grossSaleByCatalogItems;

import net.thucydides.core.pages.PageObject;
import org.openqa.selenium.By;
import project.lighthouse.autotests.objects.web.grossSaleByTable.GrossSaleByProductsObjectCollection;

public class GrossSaleByProductsPage extends PageObject {

    public GrossSaleByProductsObjectCollection getGrossSaleByProductsObjectCollection() {
        return new GrossSaleByProductsObjectCollection(getDriver(), By.name(""));
    }
}
