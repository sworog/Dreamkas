package project.lighthouse.autotests.pages.invoice;

import net.thucydides.core.annotations.DefaultUrl;
import net.thucydides.core.pages.PageObject;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import project.lighthouse.autotests.ICommonPageInterface;
import project.lighthouse.autotests.pages.common.ICommonPage;

@DefaultUrl("/?invoice/create")
public class InvoiceCreatePage extends PageObject{

    public ICommonPageInterface ICommonPageInterface = new ICommonPage(getDriver());
    private static final String INVOICE_NAME = "invoice";

    @FindBy(name = "sku")
    public WebElement invoiceSkuField;

    @FindBy(name = "acceptanceDate")
    public WebElement invoiceAcceptanceDateField;

    @FindBy(name = "supplier")
    public WebElement invoiceSupplierField;

    @FindBy(name = "accepter")
    public WebElement invoiceAccepterField;

    @FindBy(name = "recipient")
    public WebElement invoiceRecipientField;

    @FindBy(name = "supplierInvoiceSku")
    public WebElement invoiceSupplierInvoiceSkuField;

    @FindBy(name = "supplierInvoiceDate")
    public WebElement invoiceSupplierInvoiceDateField;

    @FindBy(name = "legalEntity")
    public WebElement legalEntityField;

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
        ICommonPageInterface.CheckCreateAlertSuccess(INVOICE_NAME);
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
                return invoiceSupplierInvoiceDateField;
            case "legalEntity":
                return legalEntityField;
            default:
                String errorMessage = "No such element";
                return (WebElement) new AssertionError(errorMessage);
        }
    }
}
