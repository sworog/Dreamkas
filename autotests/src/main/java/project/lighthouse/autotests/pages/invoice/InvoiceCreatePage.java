package project.lighthouse.autotests.pages.invoice;

import net.thucydides.core.annotations.DefaultUrl;
import net.thucydides.core.pages.PageObject;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import project.lighthouse.autotests.CommonPageInterface;
import project.lighthouse.autotests.pages.common.CommonItem;
import project.lighthouse.autotests.pages.common.CommonPage;

import java.util.HashMap;
import java.util.Map;

@DefaultUrl("/invoice/create")
public class InvoiceCreatePage extends PageObject{

    public CommonPageInterface CommonPageInterface = new CommonPage(getDriver());
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

    @FindBy(xpath = "//*[@lh_link='close']")
    public WebElement invoiceCloseButton;

    public Map<String, CommonItem> items = new HashMap<String, CommonItem>(){
        {
            put("sku", new CommonItem(invoiceSkuField, CommonItem.types.input));
            put("acceptanceDate", new CommonItem(invoiceAcceptanceDateField, CommonItem.types.dateTime));
            put("supplier", new CommonItem(invoiceSupplierField, CommonItem.types.input));
            put("accepter", new CommonItem(invoiceAccepterField, CommonItem.types.input));
            put("recipient", new CommonItem(invoiceRecipientField, CommonItem.types.input));
            put("supplierInvoiceSku", new CommonItem(invoiceSupplierInvoiceSkuField, CommonItem.types.input));
            put("supplierInvoiceDate", new CommonItem(invoiceSupplierInvoiceDateField, CommonItem.types.date));
            put("legalEntity", new CommonItem(legalEntityField, CommonItem.types.input));

            /*
            us 8.3 code
             */
            put("productName", new CommonItem(productName, CommonItem.types.autocomplete));
            put("productSku", new CommonItem(productSku, CommonItem.types.autocomplete));
            put("productBarCode", new CommonItem(productBarCode, CommonItem.types.autocomplete));
            put("productAmount", new CommonItem(productAmount, CommonItem.types.input));
            put("invoiceCost", new CommonItem(invoiceCost, CommonItem.types.input));
        }
    };

    public InvoiceCreatePage(WebDriver driver) {
        super(driver);
    }

    public void invoiceCloseButtonClick(){
        $(invoiceCloseButton).click();
    }

    public void invoiceCreateButtonClick(){
        findBy("//*[@lh_button='commit']").click();
        CommonPageInterface.checkCreateAlertSuccess(INVOICE_NAME);
    }

    public void input(String elementName, String inputText){
        CommonItem item = items.get(elementName);
        CommonPageInterface.setValue(item, inputText);
    }

    public void checkFieldLength(String elementName, int fieldLength){
        WebElement element = items.get(elementName).getWebElement();
        CommonPageInterface.checkFieldLength(elementName, fieldLength, element);
    }

     /*
    8.3 story code
     */

    @FindBy(name = "name")
    private WebElement productName;

    @FindBy(name = "sku")
    private WebElement productSku;

    @FindBy(name = "barcode")
    private WebElement productBarCode;

    @FindBy(name = "amount")
    private WebElement productAmount;

    @FindBy(name = "cost")
    private WebElement invoiceCost;
}
