package project.lighthouse.autotests.pages.stockMovement.modal.writeOff;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.elements.bootstrap.buttons.PrimaryBtnFacade;
import project.lighthouse.autotests.elements.items.DateInput;
import project.lighthouse.autotests.elements.items.Input;
import project.lighthouse.autotests.elements.items.SelectByVisibleText;
import project.lighthouse.autotests.elements.items.autocomplete.InvoiceProductAutoComplete;
import project.lighthouse.autotests.objects.web.writeOffProduct.WriteOffProductCollection;
import project.lighthouse.autotests.pages.modal.ModalWindowPage;

public class WriteOffCreateModalWindow extends ModalWindowPage {

    public WriteOffCreateModalWindow(WebDriver driver) {
        super(driver);
    }

    @Override
    public String modalWindowXpath() {
        return "//*[@id='modal_writeOffAdd']";
    }

    @Override
    public void createElements() {
        put("date", new DateInput(this, "//*[@name='date']"));
        put("store", new SelectByVisibleText(this, "//*[@name='store']"));
        put("product.name", new InvoiceProductAutoComplete(this, "//*[@name='product.name']"));
        put("price", new Input(this, "//*[@name='price']"));
        put("quantity", new Input(this, "//*[@name='quantity']"));
        put("cause", new Input(this, "//*[@name='cause']"));
    }

    @Override
    public void confirmationOkClick() {
        new PrimaryBtnFacade(this, "Списать").click();
    }

    public void addProductToWriteOffButtonClick() {
        findVisibleElement(By.xpath(modalWindowXpath() + "//*[contains(@class, 'addWriteOffProduct')]")).click();
    }

    public WriteOffProductCollection getWriteOffProductCollection() {
        return new WriteOffProductCollection(getDriver());
    }

    public String getTotalSum() {
        return findVisibleElement(By.xpath(modalWindowXpath() + "//*[@class='writeOff__totalSum']")).getText();
    }
}
