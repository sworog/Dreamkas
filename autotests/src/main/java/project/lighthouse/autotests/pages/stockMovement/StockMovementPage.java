package project.lighthouse.autotests.pages.stockMovement;

import net.thucydides.core.annotations.DefaultUrl;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.collection.stockMovement.StockMovementListObjectCollection;
import project.lighthouse.autotests.common.pageObjects.BootstrapPageObject;
import project.lighthouse.autotests.elements.bootstrap.buttons.DefaultBtnFacade;
import project.lighthouse.autotests.elements.bootstrap.buttons.DropdownBtnFacade;
import project.lighthouse.autotests.elements.bootstrap.buttons.PrimaryBtnFacade;
import project.lighthouse.autotests.elements.items.JSInput;
import project.lighthouse.autotests.elements.items.SelectByVisibleText;

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
        put("defaultCollection", new StockMovementListObjectCollection(getDriver()));
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
