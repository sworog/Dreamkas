package ru.dreamkas.pages.reports.stockBalance;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import ru.dreamkas.collection.abstractObjects.AbstractObject;
import ru.dreamkas.collection.abstractObjects.AbstractObjectCollection;
import ru.dreamkas.collection.reports.stockBalance.StockBalanceObject;
import ru.dreamkas.common.pageObjects.BootstrapPageObject;
import ru.dreamkas.elements.items.Input;
import ru.dreamkas.elements.items.NonType;
import ru.dreamkas.elements.items.SelectByVisibleText;
import sun.reflect.generics.reflectiveObjects.NotImplementedException;

public class StockBalanceReport extends BootstrapPageObject {

    public StockBalanceReport(WebDriver driver) {
        super(driver);
    }

    @Override
    public void addObjectButtonClick() {
        throw new NotImplementedException();
    }

    @Override
    public void createElements() {
        put("фильтр магазинов", new SelectByVisibleText(this, "store"));
        put("фильтр по группе", new SelectByVisibleText(this, "group"));
        put("фильтр по товару", new Input(this, "productFilter"));
        put("колонка 'Наименование'", new NonType(this, By.xpath("//*[@data-sort-by='product.name']")));
        put("колонка 'Запас'", new NonType(this, By.xpath("//*[@data-sort-by='inventoryDays']")));
        put("колонка 'Расход'", new NonType(this, By.xpath("//*[@data-sort-by='averageDailySales']")));
        put("колонка 'Остаток'", new NonType(this, By.xpath("//*[@data-sort-by='inventory']")));
        put("кнопка очистки данных автокомплита", new NonType(this, By.xpath("//*[@class='productFinder__resetLink input-group-addon']")));
        putDefaultCollection(new AbstractObjectCollection(getDriver(), By.xpath("//*[@name='products']/tr")) {

            @Override
            public AbstractObject createNode(WebElement element) {
                return new StockBalanceObject(element, getDriver());
            }
        });
    }
}
