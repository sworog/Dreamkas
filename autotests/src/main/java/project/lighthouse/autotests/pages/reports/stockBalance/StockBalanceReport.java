package project.lighthouse.autotests.pages.reports.stockBalance;

import org.openqa.selenium.By;
import org.openqa.selenium.StaleElementReferenceException;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.collection.abstractObjects.AbstractObject;
import project.lighthouse.autotests.collection.abstractObjects.AbstractObjectCollection;
import project.lighthouse.autotests.collection.reports.stockBalance.StockBalanceObject;
import project.lighthouse.autotests.common.objects.BootstrapPageObject;
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
    }

    @Override
    public AbstractObjectCollection getObjectCollection() {
        try {
            return getAbstractObjectCollection();
        } catch (StaleElementReferenceException e) {
            return getAbstractObjectCollection();
        }
    }

    private AbstractObjectCollection getAbstractObjectCollection() {
        return new AbstractObjectCollection(getDriver(), By.xpath("//*[@name='products']/tr")) {

            @Override
            public AbstractObject createNode(WebElement element) {
                return new StockBalanceObject(element, getDriver());
            }
        };
    }
}
