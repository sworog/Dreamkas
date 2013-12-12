package project.lighthouse.autotests.pages.departmentManager.reports;

import net.thucydides.core.annotations.DefaultUrl;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;

@DefaultUrl("/reports")
public class ReportsMainPage extends CommonPageObject {

    public ReportsMainPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
    }

    public void grossSalePerHourLinkClick() {
        findVisibleElement(By.xpath("//*[contains(text(), 'Продажи по часам.')]")).click();
    }

    public void grossSaleByProductsLinkClick() {
        findVisibleElement(By.xpath("//*[contains(text(), 'Продажи по группам товаров.')]")).click();
    }
}
