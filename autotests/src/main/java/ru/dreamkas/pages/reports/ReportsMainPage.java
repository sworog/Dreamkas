package ru.dreamkas.pages.reports;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import ru.dreamkas.common.pageObjects.BootstrapPageObject;
import ru.dreamkas.elements.items.NonType;

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
        put("stockBalanceReport", new NonType(this, By.xpath("//*[contains(text(), 'Остатки товаров')]")));
        put("grossMarginSalesReport", new NonType(this, By.xpath("//*[contains(text(), 'Продажи и прибыль по товарам')]/..")));
    }
}
