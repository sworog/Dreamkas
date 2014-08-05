package project.lighthouse.autotests.pages.catalog.group.modal;

import net.thucydides.core.annotations.findby.FindBy;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.elements.bootstrap.buttons.PrimaryBtnFacade;
import project.lighthouse.autotests.elements.items.Input;
import project.lighthouse.autotests.elements.items.SelectByVisibleText;
import project.lighthouse.autotests.elements.items.autocomplete.AutoComplete;
import project.lighthouse.autotests.pages.modal.ModalWindowPage;

/**
 * Create new product modal window
 */
public class CreateNewProductModalWindow extends ModalWindowPage {

    @FindBy(xpath = "//*[@id='modal-productAdd']//*[contains(@class, 'product__markup')]")
    private WebElement markUpValueWebElement;

    public CreateNewProductModalWindow(WebDriver driver) {
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
    }

    @Override
    public void confirmationOkClick() {
        new PrimaryBtnFacade(this, "Добавить").click();
    }

    public WebElement getMarkUpValueWebElement() {
        return markUpValueWebElement;
    }

    @Override
    public String modalWindowXpath() {
        return "//*[@id='modal-productAdd']";
    }
}
