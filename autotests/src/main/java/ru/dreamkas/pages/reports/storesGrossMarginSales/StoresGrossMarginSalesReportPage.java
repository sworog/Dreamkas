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
        put("продажи по сети", new SelectByVisibleText(this, "storesGrossSales"));
        put("себестоимость по сети", new SelectByVisibleText(this, "storesCostOfGoods"));
        put("прибыль по сети", new SelectByVisibleText(this, "storesGrossMargin"));

        putDefaultCollection(new AbstractObjectCollection(getDriver(), By.name("store")) {

            @Override
            public AbstractObject createNode(WebElement element) {
                return new StoresGrossMarginSalesObject(element);
            }
        });
    }
}
