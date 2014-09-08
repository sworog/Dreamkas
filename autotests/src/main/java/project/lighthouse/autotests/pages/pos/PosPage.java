package project.lighthouse.autotests.pages.pos;

import net.thucydides.core.annotations.findby.FindBy;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.common.BootstrapPageObject;
import project.lighthouse.autotests.elements.items.autocomplete.PosAutoComplete;
import project.lighthouse.autotests.objects.web.posAutoComplete.PosAutoCompleteCollection;
import project.lighthouse.autotests.objects.web.receipt.ReceiptCollection;

public class PosPage extends BootstrapPageObject {

    @FindBy(name = "totalPrice")
    private WebElement totalPriceWebElement;

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
    }

    @Override
    public String getTitle() {
        return findVisibleElement(By.className("page__title")).getText();
    }

    public PosAutoCompleteCollection getObjectCollection() {
        return new PosAutoCompleteCollection(getDriver());
    }

    public ReceiptCollection getReceiptCollection() {
        return new ReceiptCollection(getDriver());
    }

    public String getReceiptTotalSum() {
        return findVisibleElement(totalPriceWebElement).getText();
    }

    public void clearReceipt() {
        click(By.className("confirmLink__trigger"));
    }

    public void confirmClearReceipt() {
        click(By.className("confirmLink__confirmation"));
    }
}
