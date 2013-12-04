package project.lighthouse.autotests.pages.commercialManager.reports;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.objects.web.reports.storeGrossSaleByHour.StoreGrossSaleByHourElementCollection;

public class GrossSaleByStoresReportsPage extends CommonPageObject {

    public GrossSaleByStoresReportsPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
    }

    public StoreGrossSaleByHourElementCollection getStoreGrossSaleByHourElementCollection() {
        return new StoreGrossSaleByHourElementCollection(getDriver(), By.name(""));
    }
}
