package project.lighthouse.autotests.pages.commercialManager.reports;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.objects.web.reports.grossSaleByStores.GrossSalesByStoresCollection;

public class GrossSaleByStoresReportsPage extends CommonPageObject {

    public GrossSaleByStoresReportsPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
    }

    public GrossSalesByStoresCollection getStoreGrossSaleByHourElementCollection() {
        return new GrossSalesByStoresCollection(getDriver(), By.name("grossSalesByStoresRow"));
    }
}
