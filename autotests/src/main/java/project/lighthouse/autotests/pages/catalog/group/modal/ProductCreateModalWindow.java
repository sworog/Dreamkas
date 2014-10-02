package project.lighthouse.autotests.pages.catalog.group.modal;

import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.elements.bootstrap.buttons.PrimaryBtnFacade;
import project.lighthouse.autotests.elements.items.Input;
import project.lighthouse.autotests.elements.items.NonType;
import project.lighthouse.autotests.elements.items.SelectByVisibleText;
import project.lighthouse.autotests.elements.items.autocomplete.AutoComplete;
import project.lighthouse.autotests.pages.modal.ModalWindowPage;

/**
 * Create new product modal window
 */
public class ProductCreateModalWindow extends ModalWindowPage {

    public ProductCreateModalWindow(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        put("group", new AutoComplete(this, "//*[@class='select2-choice']"));
        put("name", new Input(this, "//*[@name='name']"));
        put("unit", new Input(this, "//*[@name='units']"));
        put("barcode", new Input(this, "//*[@name='barcode']"));
        put("vat", new SelectByVisibleText(this, "//*[@name='vat']"));
        put("purchasePrice", new Input(this, "//*[@name='purchasePrice']"));
        put("sellingPrice", new Input(this, "//*[@name='sellingPrice']"));
        put("markUpValue", new NonType(this, "//*[contains(@class, 'product__markup')]"));
    }

    @Override
    public void confirmationOkClick() {
        new PrimaryBtnFacade(this, "Добавить").click();
    }

    @Override
    public String modalWindowXpath() {
        return "//*[@id='modal_product']";
    }
}
