package project.lighthouse.autotests.pages.commercialManager.reports;

import net.thucydides.core.annotations.DefaultUrl;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.objects.web.reports.grossSaleByStores.GrossSalesByStoresCollection;

@DefaultUrl("/reports")
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

    public WebElement getYesterdayRowWebElement() {
        return findVisibleElement(By.xpath("//*[@data-sort-by='yesterday.runningSum']"));
    }

    public WebElement getTwoDaysAgoRowWebElement() {
        return findVisibleElement(By.xpath("//*[@data-sort-by='twoDaysAgo.runningSum']"));
    }

    public WebElement getEightDaysAgoRowWebElement() {
        return findVisibleElement(By.xpath("//*[@data-sort-by='eightDaysAgo.runningSum']"));
    }
}
