package ru.dreamkas.pages.cashFlow;

import org.openqa.selenium.WebDriver;
import ru.dreamkas.common.pageObjects.BootstrapPageObject;
import ru.dreamkas.elements.bootstrap.buttons.PrimaryBtnFacade;
import ru.dreamkas.elements.items.JSInput;

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
    }

    private JSInput getCustomJsInput(String name) {
        return new JSInput(this, name) {

            @Override
            public void evaluateUpdatingQueryScript() {
                getPageObject().evaluateJavascript("document.querySelector('.inputDateRange').block.trigger('update')");
            }
        };
    }
}
