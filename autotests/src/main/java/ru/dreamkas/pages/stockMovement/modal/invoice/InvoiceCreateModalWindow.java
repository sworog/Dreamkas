package ru.dreamkas.pages.stockMovement.modal.invoice;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import ru.dreamkas.collection.stockMovement.invoiceProduct.InvoiceProductCollection;
import ru.dreamkas.elements.bootstrap.buttons.TransparentBtnFacade;
import ru.dreamkas.elements.items.Input;
import ru.dreamkas.elements.items.NonType;
import ru.dreamkas.elements.items.SelectByVisibleText;
import ru.dreamkas.pages.stockMovement.modal.PayableStockMovementModalPage;
import ru.dreamkas.pages.stockMovement.modal.StockMovementModalPage;

public class InvoiceCreateModalWindow extends StockMovementModalPage implements PayableStockMovementModalPage {

    public InvoiceCreateModalWindow(WebDriver driver) {
        super(driver);
    }

    @Override
    public String modalWindowXpath() {
        return "//*[@id='modal_invoice']";
    }

    @Override
    public void createElements() {
        super.createElements();
        put("supplier", new SelectByVisibleText(this, "//*[@name='supplier']"));
        put("priceEntered", new Input(this, "//*[@name='priceEntered']"));
        put("кнопка 'Создать магазин'", new TransparentBtnFacade(this, "Создать магазин"));
        put("кнопка 'Создать товар'", new TransparentBtnFacade(this, "Создать товар"));
        put("плюсик, чтобы создать новый магазин", new NonType(this, "//*[contains(@data-modal, 'modal_store') and not(contains(@class, 'btn'))]"));
        put("плюсик, чтобы создать новый товар", new NonType(this, "//*[contains(@data-modal, 'modal_productForAutocomplete') and not(contains(@class, 'btn'))]"));

        putDefaultConfirmationOkButton(
                confirmationOkClick("Принять"));
    }

    @Override
    public InvoiceProductCollection getProductCollection() {
        return new InvoiceProductCollection(getDriver());
    }

    @Override
    public Integer getProductRowsCount() {
        return getProductRowsCount("table_invoiceProducts");
    }

    public void createSupplierButtonClick() {
        findVisibleElement(By.xpath(modalWindowXpath() + "//*[contains(@data-modal, 'modal_supplier') and not(contains(@class, 'btn'))]/i[@class='fa fa-plus']/..")).click();
    }

    public void createProductButtonClick() {
        findVisibleElement(By.xpath(modalWindowXpath() + "//*[@class='form_stockMovementProducts']//*[contains(@class, 'fa fa-plus')]")).click();
    }

    public void clickPaidCheckBox() {
        findVisibleElement(By.xpath(modalWindowXpath() + "//*[@class='checkbox']")).click();
    }
}
