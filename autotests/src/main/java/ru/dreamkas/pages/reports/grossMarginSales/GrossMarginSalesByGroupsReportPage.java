package ru.dreamkas.pages.reports.grossMarginSales;

import net.thucydides.core.annotations.DefaultUrl;
import net.thucydides.core.annotations.findby.By;
import org.openqa.selenium.WebDriver;
import ru.dreamkas.collection.reports.grossMarginSales.GrossMarginSalesByGroupsObjectCollection;
import ru.dreamkas.common.pageObjects.BootstrapPageObject;

@DefaultUrl("/reports/stockSell")
public class GrossMarginSalesByGroupsReportPage extends BootstrapPageObject {
    public GrossMarginSalesByGroupsReportPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        putDefaultCollection(new GrossMarginSalesByGroupsObjectCollection(getDriver(), By.name("group")));
    }


    @Override
    public void addObjectButtonClick() {

    }
}
