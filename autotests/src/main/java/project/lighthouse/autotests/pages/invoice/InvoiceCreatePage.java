package project.lighthouse.autotests.pages.invoice;

import net.thucydides.core.annotations.DefaultUrl;
import net.thucydides.core.pages.PageObject;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import project.lighthouse.autotests.pages.product.ProductCreatePage;

@DefaultUrl("/?invoice/create")
public class InvoiceCreatePage extends ProductCreatePage{

    @FindBy(name = "sku")
    private WebElement invoiceSkuField;

    @FindBy(name = "acceptanceDate")
    private WebElement invoiceAcceptanceDateField;

    @FindBy(name = "supplier")
    private WebElement invoiceSupplierField;

    @FindBy(name = "accepter")
    private WebElement invoiceAccepterField;

    @FindBy(name = "recipient")
    private WebElement invoiceRecipientField;

    @FindBy(name = "supplierInvoiceSku")
    private WebElement invoiceSupplierInvoiceSkuField;

    @FindBy(name = "supplierInvoiceDate")
    private WebElement invoiceSupplierInvoiceDate;

    @FindBy(xpath = "//*[@lh_button='commit']")
    private WebElement invoiceCreateAndSaveButton;

    @FindBy(xpath = "//*[@lh_link='close']")
    private WebElement invoiceCloseButton;

    public InvoiceCreatePage(WebDriver driver) {
        super(driver);
    }

    public void InvoiceCloseButtonClick(){
        $(invoiceCloseButton).click();
    }

    public void InvoiceCreateButtonClick(){
        $(invoiceCreateAndSaveButton).click();
        CreateButtonNotSuccessAlertCheck("invoice");
    }

    public void Input(String elementName, String inputText){
        WebElement invoiceElement = getInvoiceWebElement(elementName);
        $(invoiceElement).type(inputText);
    }

    public WebElement getInvoiceWebElement(String elementName){
        switch (elementName){
            case "sku":
                return invoiceSkuField;
            case "acceptanceDate":
                return invoiceAcceptanceDateField;
            case "supplier":
                return invoiceSupplierField;
            case "accepter":
                return invoiceAccepterField;
            case "recipient":
                return invoiceRecipientField;
            case "supplierInvoiceSku":
                return invoiceSupplierInvoiceSkuField;
            case "supplierInvoiceDate":
                return invoiceSupplierInvoiceDate;
            default:
                String errorMessage = "No such element";
                return (WebElement) new AssertionError(errorMessage);
        }
    }
}
