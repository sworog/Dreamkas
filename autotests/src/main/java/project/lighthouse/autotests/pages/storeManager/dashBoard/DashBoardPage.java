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
        return findVisibleElement(By.xpath("//*[@class='grossSale__subTitle']")).getText();
    }

    public String getGrossSaleTodayValue() {
        return findVisibleElement(By.xpath("//*[@class='grossSale__todayValue']")).getText();
    }

    public String getGrossSaleYesterdayValue() {
        return findVisibleElement(By.xpath("//*[@class='grossSale__yesterdayValue']")).getText();
    }

    public String getGrossSaleLastWeekValue() {
        return findVisibleElement(By.xpath("//*[@class='grossSale__lastWeekValue']")).getText();
    }

    public String getGrossSaleYesterdayDiff() {
        return findVisibleElement(By.xpath("//*[@class='grossSale__yesterdayDiff']")).getText();
    }

    public String getGrossSaleWeekDiff() {
        return findVisibleElement(By.xpath("//*[@class='grossSale__weekDiff']")).getText();
    }

    public String getGrossSaleYesterdayDiffTextColor() {
        return findVisibleElement(By.xpath("//*[@class='grossSale__yesterdayDiff']/*[contains(@class, 'grossSale__diffText')]")).getCssValue("color");
    }

    public String getGrossSaleWeekDiffTextColor() {
        return findVisibleElement(By.xpath("//*[@class='grossSale__weekDiff']/*[contains(@class, 'grossSale__diffText')]")).getCssValue("color");
    }

    public WebElement getGrossSaleDivBlock() {
        return findVisibleElement(By.xpath("//*[@class='grossSale grossSale_store']"));
    }

    @Override
    public void createElements() {
    }
}
