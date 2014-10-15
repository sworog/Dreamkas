package ru.dreamkas.pages.reports.grossMarginSales;

import net.thucydides.core.annotations.findby.By;
import org.openqa.selenium.WebDriver;
import ru.dreamkas.collection.reports.grossMarginSales.GrossMarginSalesByProductsObjectCollection;
import ru.dreamkas.common.pageObjects.BootstrapPageObject;
import ru.dreamkas.elements.items.NonType;
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
    }
}
