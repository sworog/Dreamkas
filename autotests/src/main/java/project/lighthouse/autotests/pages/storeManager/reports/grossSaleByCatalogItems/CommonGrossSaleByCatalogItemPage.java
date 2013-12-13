package project.lighthouse.autotests.pages.storeManager.reports.grossSaleByCatalogItems;

import net.thucydides.core.pages.PageObject;
import org.openqa.selenium.By;
import project.lighthouse.autotests.objects.web.grossSaleByTable.GrossSaleByTableObjectCollection;

public class CommonGrossSaleByCatalogItemPage extends PageObject {

    public GrossSaleByTableObjectCollection getGrossSaleByTableObjectCollection() {
        return new GrossSaleByTableObjectCollection(getDriver(), By.name(""));
    }
}
