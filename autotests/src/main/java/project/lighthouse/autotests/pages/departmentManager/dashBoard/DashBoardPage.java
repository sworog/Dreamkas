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

    @Override
    public void createElements() {
    }
}
