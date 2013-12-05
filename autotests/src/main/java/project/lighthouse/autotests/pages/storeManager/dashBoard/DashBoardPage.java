package project.lighthouse.autotests.pages.storeManager.dashBoard;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.common.CommonPageObject;

public class DashBoardPage extends CommonPageObject {

    public DashBoardPage(WebDriver driver) {
        super(driver);
    }

    public String getGrossSaleSubTitle() {
        return findVisibleElement(By.xpath("//*[@class='grossSales__subTitle']")).getText();
    }

    public String getGrossSaleTodayValue() {
        return findVisibleElement(By.xpath("//*[@class='grossSales__todayValue']")).getText();
    }

    public String getGrossSaleYesterdayValue() {
        return findVisibleElement(By.xpath("//*[@class='grossSales__yesterdayValue']")).getText();
    }

    public String getGrossSaleLastWeekValue() {
        return findVisibleElement(By.xpath("//*[@class='grossSales__lastWeekValue']")).getText();
    }

    public String getGrossSaleYesterdayDiff() {
        return findVisibleElement(By.xpath("//*[@class='grossSales__yesterdayDiff']")).getText();
    }

    public String getGrossSaleWeekDiff() {
        return findVisibleElement(By.xpath("//*[@class='grossSales__weekDiff']")).getText();
    }

    public String getGrossSaleYesterdayDiffTextColor() {
        return findVisibleElement(By.xpath("//*[@class='grossSales__yesterdayDiff']/*[contains(@class, 'grossSales__diffText')]")).getCssValue("color");
    }

    public String getGrossSaleWeekDiffTextColor() {
        return findVisibleElement(By.xpath("//*[@class='grossSales__weekDiff']/*[contains(@class, 'grossSales__diffText')]")).getCssValue("color");
    }

    public WebElement getGrossSaleDivBlock() {
        return findVisibleElement(By.xpath("//*[@class='grossSales grossSales_store']"));
    }

    @Override
    public void createElements() {
    }
}
