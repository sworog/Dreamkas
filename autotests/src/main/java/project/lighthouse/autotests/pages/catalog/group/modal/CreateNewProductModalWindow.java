package project.lighthouse.autotests.pages.catalog.group.modal;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.elements.bootstrap.buttons.PrimaryBtnFacade;
import project.lighthouse.autotests.elements.items.Input;
import project.lighthouse.autotests.elements.items.NewAutoComplete;
import project.lighthouse.autotests.elements.items.SelectByVisibleText;
import project.lighthouse.autotests.pages.modal.ModalWindowPage;

/**
 * Create new product modal window
 */
public class CreateNewProductModalWindow extends ModalWindowPage {

    public CreateNewProductModalWindow(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        put("group", new NewAutoComplete(this, By.xpath("???")));
        put("name", new Input(this, "name"));
        put("unit", new Input(this, "unit"));
        put("barcode", new Input(this, "barcode"));
        put("vat", new SelectByVisibleText(this, "vat"));
        put("purchasePrice", new Input(this, "purchasePrice"));
        put("retailPrice", new Input(this, "retailPrice"));
    }

    @Override
    public void confirmationOkClick() {
        new PrimaryBtnFacade(this, "Добавить").click();
    }

    public String getMarkUpValue() {
        return findVisibleElement(By.name("markUp")).getText();
    }

    @Override
    public String getTitleText() {
        return super.getTitleText();
    }
}
