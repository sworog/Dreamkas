package project.lighthouse.autotests.pages.catalog.group.modal;

import net.thucydides.core.annotations.findby.FindBy;
import org.openqa.selenium.By;
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
        put("group", new AutoComplete(this, By.xpath("//*[@id='modal-productAdd']//*[@class='select2-choice']")));
        put("name", new Input(this, By.xpath("//*[@id='modal-productAdd']//*[@name='name']")));
        put("unit", new Input(this, By.xpath("//*[@id='modal-productAdd']//*[@name='units']")));
        put("barcode", new Input(this, By.xpath("//*[@id='modal-productAdd']//*[@name='barcode']")));
        put("vat", new SelectByVisibleText(this, By.xpath("//*[@id='modal-productAdd']//*[@name='vat']")));
        put("purchasePrice", new Input(this, By.xpath("//*[@id='modal-productAdd']//*[@name='purchasePrice']")));
        put("sellingPrice", new Input(this, By.xpath("//*[@id='modal-productAdd']//*[@name='sellingPrice']")));
    }

    @Override
    public void confirmationOkClick() {
        new PrimaryBtnFacade(this, "Добавить").click();
    }

    public WebElement getMarkUpValueWebElement() {
        return markUpValueWebElement;
    }

    @Override
    public String getTitleText() {
        return findVisibleElement(By.xpath("//*[@id='modal-productAdd']//*[@class='modal-title']")).getText();
    }

    @Override
    public void closeIconClick() {
        findVisibleElement(By.xpath("//*[@id='modal-productAdd']//*[contains(@class, 'close')]")).click();
    }
}
