package project.lighthouse.autotests.pages.storeManager.reports;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.objects.web.grossMargin.GrossMarginTableObjectCollection;

public class StoreGrossSaleMarginReportPage extends CommonPageObject {

    public StoreGrossSaleMarginReportPage(WebDriver driver) {
        super(driver);
    }

    public GrossMarginTableObjectCollection getGrossMarginTableObjectCollection() {
        return new GrossMarginTableObjectCollection(getDriver(), By.name(""));
    }

    public String getReportName() {
        return findVisibleElement(By.name("")).getText();
    }

    @Override
    public void createElements() {
    }
}
