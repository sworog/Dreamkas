package ru.dreamkas.pages.pos;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import ru.dreamkas.collection.posAutoComplete.PosAutoCompleteCollection;
import ru.dreamkas.collection.receipt.ReceiptCollection;
import ru.dreamkas.elements.items.NonType;
import ru.dreamkas.elements.items.autocomplete.PosAutoComplete;

public class PosPage extends CommonPosPage {

    public PosPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void addObjectButtonClick() {
        throw new AssertionError("Not implemented!");
    }

    @Override
    public void createElements() {
        put("autocomplete", new PosAutoComplete(this, By.xpath("//input[@name='product']")));
        put("totalPrice");
        putDefaultCollection(new PosAutoCompleteCollection(getDriver()));
        put("receiptCollection", new ReceiptCollection(getDriver()));
        put("registerSaleButton", new NonType(this, By.xpath("//*[contains(@class, 'btn-primary') and contains(text(), 'Продать на сумму')]")));
        put("clearReceipt", new NonType(this, By.className("confirmLink__trigger")));
        put("confirmClearReceipt", new NonType(this, By.className("confirmLink__confirmation")));
    }

    @Override
    public String getTitle() {
        return findVisibleElement(By.className("page__title")).getText();
    }
}
