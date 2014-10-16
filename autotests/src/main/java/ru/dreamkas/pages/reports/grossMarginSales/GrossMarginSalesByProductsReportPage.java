package ru.dreamkas.pages.reports.grossMarginSales;

import net.thucydides.core.annotations.DefaultUrl;
import net.thucydides.core.annotations.findby.By;
import org.openqa.selenium.WebDriver;
import ru.dreamkas.collection.reports.grossMarginSales.GrossMarginSalesByProductsObjectCollection;
import ru.dreamkas.common.pageObjects.BootstrapPageObject;

public class GrossMarginSalesByProductsReportPage extends BootstrapPageObject {
    public GrossMarginSalesByProductsReportPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void addObjectButtonClick() {

    }

    @Override
    public void createElements() {
        putDefaultCollection(new GrossMarginSalesByProductsObjectCollection(getDriver(), By.name("product")));
    }
}
