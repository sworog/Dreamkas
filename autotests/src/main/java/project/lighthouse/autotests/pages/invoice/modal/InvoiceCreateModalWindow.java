package project.lighthouse.autotests.pages.invoice.modal;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.elements.bootstrap.buttons.PrimaryBtnFacade;
import project.lighthouse.autotests.elements.items.Input;
import project.lighthouse.autotests.elements.items.SelectByVisibleText;
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
        put("date", new Input(this, "//*[@name='date']"));
        put("store", new SelectByVisibleText(this, "//*[@name='store']"));
        put("supplier", new SelectByVisibleText(this, "//*[@name='supplier']"));
        put("product.name", new SelectByVisibleText(this, "//*[@name='product.name']"));
        put("priceEntered", new SelectByVisibleText(this, "//*[@name='priceEntered']"));
        put("quantity", new SelectByVisibleText(this, "//*[@name='quantity']"));
    }

    @Override
    public void confirmationOkClick() {
        new PrimaryBtnFacade(this, "Принять").click();
    }

    public void addSupplierButtonClick() {
        findVisibleElement(By.xpath(modalWindowXpath() + "//*[contains(@class, 'addSupplierLink')]")).click();
    }

    public void paidCheckBoxClick() {
        findVisibleElement(By.xpath(modalWindowXpath() + "//*[@class='checkbox']")).click();
    }

    public void addProductToInvoiceButtonClick() {
        findVisibleElement(By.xpath(modalWindowXpath() + "//*[contains(@class, 'addInvoiceProduct')]")).click();
    }
}
