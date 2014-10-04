package project.lighthouse.autotests.pages.pos;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.collection.posAutoComplete.PosAutoCompleteCollection;
import project.lighthouse.autotests.collection.receipt.ReceiptCollection;
import project.lighthouse.autotests.elements.items.autocomplete.PosAutoComplete;

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
        put("defaultCollection", new PosAutoCompleteCollection(getDriver()));
    }

    @Override
    public String getTitle() {
        return findVisibleElement(By.className("page__title")).getText();
    }

    public ReceiptCollection getReceiptCollection() {
        return new ReceiptCollection(getDriver());
    }

    public void clearReceipt() {
        click(By.className("confirmLink__trigger"));
    }

    public void confirmClearReceipt() {
        click(By.className("confirmLink__confirmation"));
    }

    public void clickOnRegisterSaleButton() {
        click(By.xpath("//*[contains(@class, 'btn-primary') and contains(text(), 'Продать на сумму')]"));
    }
}
