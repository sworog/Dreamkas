package project.lighthouse.autotests.pages.catalog.group.modal;

import net.thucydides.core.annotations.findby.FindBy;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.elements.items.Input;
import project.lighthouse.autotests.elements.items.SelectByVisibleText;
import project.lighthouse.autotests.elements.items.autocomplete.AutoComplete;

/**
 * Edit product modal window
 */
public class EditProductModalWindow extends CreateNewProductModalWindow {

    @FindBy(xpath = "//*[@id='modal-productEdit']//*[contains(@class, 'product__markup')]")
    private WebElement markUpValueWebElement;

    public EditProductModalWindow(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        put("group", new AutoComplete(this, By.xpath("//*[@id='modal-productEdit']//*[@class='select2-choice']")));
        put("name", new Input(this, By.xpath("//*[@id='modal-productEdit']//*[@name='name']")));
        put("unit", new Input(this, By.xpath("//*[@id='modal-productEdit']//*[@name='units']")));
        put("barcode", new Input(this, By.xpath("//*[@id='modal-productEdit']//*[@name='barcode']")));
        put("vat", new SelectByVisibleText(this, By.xpath("//*[@id='modal-productEdit']//*[@name='vat']")));
        put("purchasePrice", new Input(this, By.xpath("//*[@id='modal-productEdit']//*[@name='purchasePrice']")));
        put("sellingPrice", new Input(this, By.xpath("//*[@id='modal-productEdit']//*[@name='sellingPrice']")));
    }

    public void deleteButtonClick() {
        findVisibleElement(By.xpath("//*[@id='modal-productEdit']//*[@class='removeLink product__removeLink']")).click();
    }

    public void confirmDeleteButtonClick() {
        findVisibleElement(By.xpath("//*[@id='modal-productEdit']//*[@class='confirmLink__confirmation']//*[@class='removeLink product__removeLink']")).click();
    }

    @Override
    public String getTitleText() {
        return findVisibleElement(By.xpath("//*[@id='modal-productEdit']//*[@class='modal-title']")).getText();
    }

    @Override
    public void confirmationOkClick() {
        findVisibleElement(By.xpath("//*[@id='modal-productEdit']//*[@class='btn btn-primary pull-right']")).click();
    }

    @Override
    public void closeIconClick() {
        findVisibleElement(By.xpath("//*[@id='modal-productEdit']//*[contains(@class, 'close')]")).click();
    }

    public WebElement getMarkUpValueWebElement() {
        return markUpValueWebElement;
    }
}
