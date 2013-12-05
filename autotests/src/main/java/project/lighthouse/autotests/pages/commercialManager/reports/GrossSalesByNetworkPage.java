package project.lighthouse.autotests.pages.commercialManager.reports;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;

public class GrossSalesByNetworkPage extends CommonPageObject {

    public GrossSalesByNetworkPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
    }

    public String getYesterdayValue() {
        return findVisibleElement(By.name("yesterday.runningSum")).getText();
    }

    public String getTwoDaysAgoValue() {
        return findVisibleElement(By.name("twoDaysAgo.runningSum")).getText();
    }

    public String getEightDaysAgoValue() {
        return findVisibleElement(By.name("eightDaysAgo.dayHour")).getText();
    }
}
