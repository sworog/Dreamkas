package ru.dreamkas.pages.reports.storesGrossMarginSales;

import net.thucydides.core.annotations.DefaultUrl;
import net.thucydides.core.annotations.findby.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import ru.dreamkas.collection.abstractObjects.AbstractObject;
import ru.dreamkas.collection.abstractObjects.AbstractObjectCollection;
import ru.dreamkas.collection.reports.storesGrossMarginSales.StoresGrossMarginSalesObject;
import ru.dreamkas.elements.items.NonType;
import ru.dreamkas.elements.items.SelectByVisibleText;
import ru.dreamkas.pages.reports.goodsGrossMarginSales.GoodsGrossMarginSalesByProductsReportPage;

@DefaultUrl("/reports/profit/stores")
public class StoresGrossMarginSalesReportPage extends GoodsGrossMarginSalesByProductsReportPage {

    public StoresGrossMarginSalesReportPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        super.createElements();
        put("колонка 'Магазин'", new NonType(this, org.openqa.selenium.By.xpath("//*[@data-sort-by='name']")));

        put("продажи по сети", new NonType(this, org.openqa.selenium.By.name("storesGrossSales")));
        put("себестоимость по сети", new NonType(this, org.openqa.selenium.By.name("storesCostOfGoods")));
        put("прибыль по сети", new NonType(this, org.openqa.selenium.By.name("storesGrossMargin")));

        putDefaultCollection(new AbstractObjectCollection(getDriver(), By.name("store")) {

            @Override
            public AbstractObject createNode(WebElement element) {
                return new StoresGrossMarginSalesObject(element);
            }
        });
    }
}
