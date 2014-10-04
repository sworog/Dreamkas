package project.lighthouse.autotests.pages.reports.stockBalance;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.collection.abstractObjects.AbstractObject;
import project.lighthouse.autotests.collection.abstractObjects.AbstractObjectCollection;
import project.lighthouse.autotests.collection.reports.stockBalance.StockBalanceObject;
import project.lighthouse.autotests.common.pageObjects.BootstrapPageObject;
import project.lighthouse.autotests.elements.items.Input;
import project.lighthouse.autotests.elements.items.NonType;
import project.lighthouse.autotests.elements.items.SelectByVisibleText;
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
        put("defaultCollection", new AbstractObjectCollection(getDriver(), By.xpath("//*[@name='products']/tr")) {

            @Override
            public AbstractObject createNode(WebElement element) {
                return new StockBalanceObject(element, getDriver());
            }
        });
    }
}
