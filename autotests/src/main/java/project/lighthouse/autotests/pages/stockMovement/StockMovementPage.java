package project.lighthouse.autotests.pages.stockMovement;

import net.thucydides.core.annotations.DefaultUrl;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.BootstrapPageObject;
import project.lighthouse.autotests.elements.bootstrap.buttons.DefaultBtnFacade;
import project.lighthouse.autotests.elements.bootstrap.buttons.DropdownBtnFacade;
import project.lighthouse.autotests.elements.bootstrap.buttons.PrimaryBtnFacade;
import project.lighthouse.autotests.elements.items.Input;
import project.lighthouse.autotests.elements.items.SelectByVisibleText;
import project.lighthouse.autotests.objects.web.stockMovement.StockMovementListObjectCollection;

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
        put("dateFrom", new Input(this, "dateFrom"));
        put("dateTo", new Input(this, "dateTo"));
    }

    public StockMovementListObjectCollection getStockMovementObjectCollection() {
        return new StockMovementListObjectCollection(getDriver());
    }

    public void acceptFiltersButtonClick() {
        new PrimaryBtnFacade(this, "Применить фильтры").click();
    }

    public void resetFiltersButtonClick() {
        new DefaultBtnFacade(this, "Сбросить фильтры").click();
    }
}
