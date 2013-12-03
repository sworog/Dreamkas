package project.lighthouse.autotests.pages.departmentManager.reports;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.objects.web.reports.StoreGrossSaleByHourElementCollection;

public class StoreGrossSaleByHourReportPage extends CommonPageObject {

    public StoreGrossSaleByHourReportPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
    }

    public StoreGrossSaleByHourElementCollection getStoreGrossSaleByHourElementCollection() {
        return new StoreGrossSaleByHourElementCollection(getDriver(), By.xpath(""));
    }
}
