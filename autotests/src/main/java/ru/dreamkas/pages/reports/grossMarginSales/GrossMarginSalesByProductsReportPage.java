package ru.dreamkas.pages.reports.grossMarginSales;

import net.thucydides.core.annotations.findby.By;
import org.openqa.selenium.WebDriver;
import ru.dreamkas.collection.reports.grossMarginSales.GrossMarginSalesByProductsObjectCollection;
import ru.dreamkas.common.pageObjects.BootstrapPageObject;
import ru.dreamkas.elements.items.NonType;
import ru.dreamkas.elements.items.SelectByVisibleText;
import sun.reflect.generics.reflectiveObjects.NotImplementedException;

public class GrossMarginSalesByProductsReportPage extends BootstrapPageObject {
    public GrossMarginSalesByProductsReportPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void addObjectButtonClick() {
        throw new NotImplementedException();
    }

    @Override
    public void createElements() {
        putDefaultCollection(new GrossMarginSalesByProductsObjectCollection(getDriver(), By.name("product")));
        put("колонка 'Товар'", new NonType(this, org.openqa.selenium.By.xpath("//*[@data-sort-by='product.name']")));
        put("колонка 'Продажи'", new NonType(this, org.openqa.selenium.By.xpath("//*[@data-sort-by='grossSales']")));
        put("колонка 'Себестоимость'", new NonType(this, org.openqa.selenium.By.xpath("//*[@data-sort-by='costOfGoods']")));
        put("колонка 'Прибыль'", new NonType(this, org.openqa.selenium.By.xpath("//*[@data-sort-by='grossMargin']")));
        put("колонка 'Количество'", new NonType(this, org.openqa.selenium.By.xpath("//*[@data-sort-by='quantity']")));
        put("фильтр по сети", new SelectByVisibleText(this, "store"));
        put("дата с", new SelectByVisibleText(this, "dateFrom"));
        put("дата по", new SelectByVisibleText(this, "dateTo"));
    }
}
