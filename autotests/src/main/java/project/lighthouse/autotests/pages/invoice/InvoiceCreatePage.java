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

    public CommonPageInterface commonPageInterface = new CommonPage(getDriver());
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

    @FindBy(xpath = "//*[@lh_product_autocomplete='name']")
    public WebElement productName;

    @FindBy(name = "productName")
    public WebElement productNameInList;

    @FindBy(xpath = "//*[@lh_product_autocomplete='sku']")
    public WebElement productSku;

    @FindBy(name = "productSku")
    public WebElement productSkuInList;

    @FindBy(xpath = "//*[@lh_product_autocomplete='barcode']")
    public WebElement productBarCode;

    @FindBy(name = "productBarcode")
    public WebElement productBarCodeInList;

    @FindBy(name = "productUnits")
    public WebElement productUnitsInList;

    @FindBy(name = "productAmount")
    public WebElement productAmountInList;

    @FindBy(name = "productPrice")
    public WebElement productPriceInList;

    @FindBy(name = "productSum")
    public WebElement productSumInList;


    @FindBy(name = "quantity")
    public WebElement productAmount;

    @FindBy(name = "price")
    public WebElement invoiceCost;

    @FindBy(name = "totalProducts")
    public WebElement totalProducts;

    @FindBy(name = "totalSum")
    public WebElement totalSum;

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
            put("productName", new CommonItem(productName, CommonItem.types.autocomplete));
            put("productSku", new CommonItem(productSku, CommonItem.types.autocomplete));
            put("productBarCode", new CommonItem(productBarCode, CommonItem.types.autocomplete));
            put("productAmount", new CommonItem(productAmount, CommonItem.types.input));
            put("invoiceCost", new CommonItem(invoiceCost, CommonItem.types.input));
            put("totalProducts", new CommonItem(totalProducts, CommonItem.types.input));
            put("totalSum", new CommonItem(totalSum, CommonItem.types.input));

            put("productNameInList", new CommonItem(productNameInList, CommonItem.types.nonType));
            put("productSkuInList", new CommonItem(productSkuInList, CommonItem.types.nonType));
            put("productBarCodeInList", new CommonItem(productBarCodeInList, CommonItem.types.nonType));
            put("productUnitsInList", new CommonItem(productUnitsInList, CommonItem.types.nonType));
            put("productAmountInList", new CommonItem(productAmountInList, CommonItem.types.nonType));
            put("productPriceInList", new CommonItem(productPriceInList, CommonItem.types.nonType));
            put("productSumInList", new CommonItem(productSumInList, CommonItem.types.nonType));
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
        commonPageInterface.checkCreateAlertSuccess(INVOICE_NAME);
    }

    public void input(String elementName, String inputText){
        CommonItem item = items.get(elementName);
        commonPageInterface.setValue(item, inputText);
    }

    public void checkFieldLength(String elementName, int fieldLength){
        WebElement element = items.get(elementName).getWebElement();
        commonPageInterface.checkFieldLength(elementName, fieldLength, element);
    }

    public void checkFormIsChanged(){
        String alertText = getAlert().getText();
        String expectedMessage = "";
        if(!alertText.contains(expectedMessage)){
            String errorMessage = "";
            throw new AssertionError(errorMessage);
        }
    }
}
