package ru.dreamkas.pages.reports.goodsGrossMarginSales;

import net.thucydides.core.annotations.findby.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import ru.dreamkas.collection.abstractObjects.AbstractObject;
import ru.dreamkas.collection.abstractObjects.AbstractObjectCollection;
import ru.dreamkas.collection.reports.goodsGrossMarginSales.GoodsGrossMarginSalesByProductsObject;
import ru.dreamkas.common.pageObjects.BootstrapPageObject;
import ru.dreamkas.elements.items.JSInput;
import ru.dreamkas.elements.items.NonType;
import ru.dreamkas.elements.items.SelectByVisibleText;
import sun.reflect.generics.reflectiveObjects.NotImplementedException;

public class GoodsGrossMarginSalesByProductsReportPage extends BootstrapPageObject {

    public GoodsGrossMarginSalesByProductsReportPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void addObjectButtonClick() {
        throw new NotImplementedException();
    }

    @Override
    public void createElements() {
        put("колонка 'Товар'", new NonType(this, org.openqa.selenium.By.xpath("//*[@data-sort-by='product.name']")));
        put("колонка 'Продажи'", new NonType(this, org.openqa.selenium.By.xpath("//*[@data-sort-by='grossSales']")));
        put("колонка 'Себестоимость'", new NonType(this, org.openqa.selenium.By.xpath("//*[@data-sort-by='costOfGoods']")));
        put("колонка 'Прибыль'", new NonType(this, org.openqa.selenium.By.xpath("//*[@data-sort-by='grossMargin']")));
        put("колонка 'Количество'", new NonType(this, org.openqa.selenium.By.xpath("//*[@data-sort-by='quantity']")));
        put("фильтр по сети", new SelectByVisibleText(this, "store"));
        put("дата с", getCustomJsInput("dateFrom"));
        put("дата по", getCustomJsInput("dateTo"));
        putDefaultCollection(new AbstractObjectCollection(getDriver(), By.name("product")) {

            @Override
            public AbstractObject createNode(WebElement element) {
                return new GoodsGrossMarginSalesByProductsObject(element);
            }
        });
    }

    private JSInput getCustomJsInput(final String name) {
        return new JSInput(this, name) {
            @Override
            public void evaluateUpdatingQueryScript() {
                String commitChangesScript = "$('.inputDateRange').trigger('update')";
                getPageObject().evaluateJavascript(commitChangesScript);
            }
        };
    }
}
