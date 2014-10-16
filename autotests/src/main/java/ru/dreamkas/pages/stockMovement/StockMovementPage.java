package ru.dreamkas.pages.stockMovement;

import net.thucydides.core.annotations.DefaultUrl;
import org.openqa.selenium.WebDriver;
import ru.dreamkas.collection.stockMovement.StockMovementListObjectCollection;
import ru.dreamkas.common.pageObjects.BootstrapPageObject;
import ru.dreamkas.elements.bootstrap.buttons.DefaultBtnFacade;
import ru.dreamkas.elements.bootstrap.buttons.DropdownBtnFacade;
import ru.dreamkas.elements.bootstrap.buttons.PrimaryBtnFacade;
import ru.dreamkas.elements.items.JSInput;
import ru.dreamkas.elements.items.SelectByVisibleText;

@DefaultUrl("/stockMovements")
public class StockMovementPage extends BootstrapPageObject {

    public StockMovementPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void addObjectButtonClick() {
        new PrimaryBtnFacade(this, "Принять от поставщика").click();
    }

    public void writeOffCreateButtonClick() {
        clickCreateDropdownButton("Списать");
    }

    public void stockInCreateButtonClick() {
        clickCreateDropdownButton("Оприходовать");
    }

    public void supplierReturnButtonClick() {
        clickCreateDropdownButton("Вернуть поставщику");
    }

    public void clickCreateDropdownButton(String title) {
        new DropdownBtnFacade(this, "Еще...").clickDropdownItem(title);
    }

    @Override
    public void createElements() {
        put("types", new SelectByVisibleText(this, "types"));
        put("dateFrom", getCustomJsInput("dateFrom"));
        put("dateTo", getCustomJsInput("dateTo"));
        putDefaultCollection(new StockMovementListObjectCollection(getDriver()));
        put("acceptFiltersButton", new PrimaryBtnFacade(this, "Применить фильтры"));
        put("resetFiltersButton", new DefaultBtnFacade(this, "Сбросить фильтры"));
    }

    private JSInput getCustomJsInput(final String name) {
        return new JSInput(this, name) {
            @Override
            public void evaluateUpdatingQueryScript() {
                //Do nothing
            }
        };
    }
}
