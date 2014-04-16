package project.lighthouse.autotests.pages.departmentManager.invoice;

import net.thucydides.core.annotations.findby.FindBy;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Buttons.ButtonFacade;
import project.lighthouse.autotests.elements.Buttons.LinkFacade;
import project.lighthouse.autotests.elements.items.DateTime;
import project.lighthouse.autotests.elements.items.Input;
import project.lighthouse.autotests.elements.items.NewAutoComplete;
import project.lighthouse.autotests.elements.items.SelectByVisibleText;
import project.lighthouse.autotests.objects.web.invoice.InvoiceProductsCollection;

/**
 * Page object representing invoice page
 */
public class InvoicePage extends CommonPageObject {

    @FindBy(name = "invoiceNumber")
    @SuppressWarnings("unused")
    private WebElement invoiceNumberWebElement;

    @FindBy(name = "sumTotal")
    @SuppressWarnings("unused")
    private WebElement totalSumWebElement;

    @FindBy(name = "sumTotalAmountVAT")
    @SuppressWarnings("unused")
    private WebElement vatSumWebElement;

    public InvoicePage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        put("acceptanceDate", new DateTime(this, "acceptanceDate", "Дата"));
        put("supplier", new SelectByVisibleText(this, "supplier", "Поставщик"));
        put("accepter", new Input(this, "accepter", "Приемщик"));
        put("supplierInvoiceNumber", new Input(this, "supplierInvoiceNumber", "Счет-фактура"));
        put("legalEntity", new Input(this, "legalEntity", "Организация получатель"));
        put("invoice product autocomplete", new NewAutoComplete(this, By.xpath("//*[@class='inputText autocomplete']")));
    }

    public InvoiceProductsCollection getInvoiceProductsCollection() {
        return new InvoiceProductsCollection(getDriver(), By.name("invoiceProduct"));
    }

    public String getInvoiceNumber() {
        return findVisibleElement(invoiceNumberWebElement).getText();
    }

    public String getTotalSum() {
        return findVisibleElement(totalSumWebElement).getText();
    }

    public String getVatSum() {
        return findVisibleElement(vatSumWebElement).getText();
    }

    public void acceptProductsButtonClick() {
        new ButtonFacade(this, "Принять товары").click();
    }

    public ButtonFacade getDownloadAgreementFileButton() {
        return new ButtonFacade(this, "Скачать договор");
    }

    public void cancelLinkClick() {
        new LinkFacade(this, "Отменить").click();
    }
}
