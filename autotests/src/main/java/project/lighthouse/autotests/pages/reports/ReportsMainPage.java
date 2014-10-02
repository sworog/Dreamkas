package project.lighthouse.autotests.pages.reports;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.pageObjects.BootstrapPageObject;

public class ReportsMainPage extends BootstrapPageObject {

    public ReportsMainPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void addObjectButtonClick() {
        throw new AssertionError("The method is not implemented!");
    }

    @Override
    public void createElements() {
    }

    public void clickOnStockBalanceReport() {
        click(By.xpath("//*[contains(text(), 'Остатки товаров')]/.."));
    }
}
