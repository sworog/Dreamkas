package project.lighthouse.autotests.pages.stockMovement.modal.invoice;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.elements.items.Input;
import project.lighthouse.autotests.objects.web.invoiceProduct.InvoiceProductCollection;
import project.lighthouse.autotests.pages.stockMovement.modal.StockMovementModalPage;

public class InvoiceCreateModalWindow extends StockMovementModalPage {

    public InvoiceCreateModalWindow(WebDriver driver) {
        super(driver);
    }

    @Override
    public String modalWindowXpath() {
        return "//*[@id='modal_invoiceAdd']";
    }

    @Override
    public void createElements() {
        super.createElements();
        put("priceEntered", new Input(this, "//*[@name='priceEntered']"));
    }

    @Override
    public void confirmationOkClick() {
        confirmationOkClick("Принять");
    }

    @Override
    public void addProductButtonClick() {
        addProductButtonClick("addInvoiceProduct");
    }

    @Override
    public InvoiceProductCollection getProductCollection() {
        return new InvoiceProductCollection(getDriver());
    }

    @Override
    public String getTotalSum() {
        return getTotalSum("invoice__totalSum");
    }

    @Override
    public Integer getProductRowsCount() {
        return getProductRowsCount("table_invoiceProducts");
    }

    public void createSupplierButtonClick() {
        findVisibleElement(By.xpath(modalWindowXpath() + "//*[contains(@class, 'addSupplierLink')]")).click();
    }

    public void createProductButtonClick() {
        findVisibleElement(By.xpath(modalWindowXpath() + "//*[contains(@class, 'addProductLink')]")).click();
    }

    public void paidCheckBoxClick() {
        findVisibleElement(By.xpath(modalWindowXpath() + "//*[@class='checkbox']")).click();
    }
}
