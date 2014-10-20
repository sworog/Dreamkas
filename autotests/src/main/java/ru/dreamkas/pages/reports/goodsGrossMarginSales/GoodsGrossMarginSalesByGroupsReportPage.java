package ru.dreamkas.pages.reports.goodsGrossMarginSales;

import net.thucydides.core.annotations.DefaultUrl;
import net.thucydides.core.annotations.findby.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import ru.dreamkas.collection.abstractObjects.AbstractObject;
import ru.dreamkas.collection.abstractObjects.AbstractObjectCollection;
import ru.dreamkas.collection.reports.goodsGrossMarginSales.GoodsGrossMarginSalesByGroupsObject;
import ru.dreamkas.elements.items.NonType;

@DefaultUrl("/reports/profit/groups")
public class GoodsGrossMarginSalesByGroupsReportPage extends GoodsGrossMarginSalesByProductsReportPage {

    public GoodsGrossMarginSalesByGroupsReportPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        super.createElements();
        put("колонка 'Группа'", new NonType(this, org.openqa.selenium.By.xpath("//*[@data-sort-by='subCategory.name']")));
        putDefaultCollection(new AbstractObjectCollection(getDriver(), By.name("group")) {

            @Override
            public AbstractObject createNode(WebElement element) {
                return new GoodsGrossMarginSalesByGroupsObject(element);
            }
        });
    }
}
