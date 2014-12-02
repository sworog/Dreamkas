package ru.dreamkas.pages.cashFlow;

import net.thucydides.core.annotations.DefaultUrl;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import ru.dreamkas.apihelper.DateTimeHelper;
import ru.dreamkas.collection.abstractObjects.AbstractObject;
import ru.dreamkas.collection.abstractObjects.AbstractObjectCollection;
import ru.dreamkas.collection.cashFlow.CashFlowObject;
import ru.dreamkas.common.pageObjects.BootstrapPageObject;
import ru.dreamkas.elements.bootstrap.buttons.PrimaryBtnFacade;
import ru.dreamkas.elements.items.JSInput;

@DefaultUrl("/cashFlow")
public class CashFlowListPage extends BootstrapPageObject{

    public CashFlowListPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void addObjectButtonClick() {
        new PrimaryBtnFacade(this, "Добавить операцию").click();
    }

    @Override
    public void createElements() {
        put("Дата по", getCustomJsInput("dateTo"));
        put("Дата с", getCustomJsInput("dateFrom"));
        putDefaultCollection(new AbstractObjectCollection(getDriver(), By.name("cashFlow")) {

            @Override
            public AbstractObject createNode(WebElement element) {
                return new CashFlowObject(element);
            }

            @Override
            public void clickByLocator(String locator) {
                String [] locators = locator.split(":");
                String convertedDate = DateTimeHelper.getDate(locators[0]);
                super.clickByLocator(convertedDate + ":" + locators[1]);
            }
        });
    }

    private JSInput getCustomJsInput(final String name) {
        return new JSInput(this, name) {

            @Override
            public void setValue(String value) {
                getVisibleWebElementFacade();
                String jsScript = String.format(
                        "document.querySelector('.inputDateRange').block.trigger('update', {%s: '%s'})",
                        name,
                        DateTimeHelper.getDate(value));
                getPageObject().evaluateJavascript(jsScript);
            }

            @Override
            public void evaluateUpdatingQueryScript() {
            }
        };
    }
}
