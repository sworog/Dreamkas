package ru.dreamkas.pages.stockMovement.modal.invoice;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import ru.dreamkas.collection.stockMovement.invoiceProduct.InvoiceProductCollection;
import ru.dreamkas.elements.items.Input;
import ru.dreamkas.elements.items.SelectByVisibleText;
import ru.dreamkas.pages.stockMovement.modal.PayableStockMovementModalPage;
import ru.dreamkas.pages.stockMovement.modal.StockMovementModalPage;

public class InvoiceCreateModalWindow extends StockMovementModalPage implements PayableStockMovementModalPage {

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
        put("supplier", new SelectByVisibleText(this, "//*[@name='supplier']"));
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
    public Integer getProductRowsCount() {
        return getProductRowsCount("table_invoiceProducts");
    }

    @Override
    public String getTotalSum() {
        String xpath = String.format("%s//*[@class='invoice__totalSum']", modalWindowXpath());
        return findVisibleElement(By.xpath(xpath)).getText();
    }

    public void createSupplierButtonClick() {
        findVisibleElement(By.xpath(modalWindowXpath() + "//*[contains(@class, 'addSupplierLink')]")).click();
    }

    public void createProductButtonClick() {
        findVisibleElement(By.xpath(modalWindowXpath() + "//*[contains(@class, 'addProductLink')]")).click();
    }

    public void clickPaidCheckBox() {
        findVisibleElement(By.xpath(modalWindowXpath() + "//*[@class='checkbox']")).click();
    }
}
