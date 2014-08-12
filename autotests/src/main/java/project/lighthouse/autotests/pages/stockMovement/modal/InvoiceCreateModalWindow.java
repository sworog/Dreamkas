package project.lighthouse.autotests.pages.stockMovement.modal;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.elements.bootstrap.buttons.PrimaryBtnFacade;
import project.lighthouse.autotests.elements.items.DateInput;
import project.lighthouse.autotests.elements.items.Input;
import project.lighthouse.autotests.elements.items.SelectByVisibleText;
import project.lighthouse.autotests.elements.items.autocomplete.InvoiceProductAutoComplete;
import project.lighthouse.autotests.objects.web.invoiceProduct.InvoiceProductCollection;
import project.lighthouse.autotests.pages.modal.ModalWindowPage;

public class InvoiceCreateModalWindow extends ModalWindowPage {

    public InvoiceCreateModalWindow(WebDriver driver) {
        super(driver);
    }

    @Override
    public String modalWindowXpath() {
        return "//*[@id='modal_invoiceAdd']";
    }

    @Override
    public void createElements() {
        put("date", new DateInput(this, "//*[@name='date']"));
        put("store", new SelectByVisibleText(this, "//*[@name='store']"));
        put("supplier", new SelectByVisibleText(this, "//*[@name='supplier']"));
        put("product.name", new InvoiceProductAutoComplete(this, "//*[@name='product.name']"));
        put("priceEntered", new Input(this, "//*[@name='priceEntered']"));
        put("quantity", new Input(this, "//*[@name='quantity']"));
    }

    @Override
    public void confirmationOkClick() {
        new PrimaryBtnFacade(this, "Принять").click();
    }

    public void addSupplierButtonClick() {
        findVisibleElement(By.xpath(modalWindowXpath() + "//*[contains(@class, 'addSupplierLink')]")).click();
    }

    public void addProductButtonClick() {

    }

    public void paidCheckBoxClick() {
        findVisibleElement(By.xpath(modalWindowXpath() + "//*[@class='checkbox']")).click();
    }

    public void addProductToInvoiceButtonClick() {
        findVisibleElement(By.xpath(modalWindowXpath() + "//*[contains(@class, 'addInvoiceProduct')]")).click();
    }

    public InvoiceProductCollection getInvoiceProductCollection() {
        return new InvoiceProductCollection(getDriver());
    }

    public String getTotalSum() {
        return findVisibleElement(By.xpath(modalWindowXpath() + "//*[@class='invoice__totalSum']")).getText();
    }
}
