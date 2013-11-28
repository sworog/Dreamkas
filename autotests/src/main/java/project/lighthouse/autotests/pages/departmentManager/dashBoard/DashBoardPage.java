package project.lighthouse.autotests.pages.departmentManager.dashBoard;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
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

    @Override
    public void createElements() {
    }
}
