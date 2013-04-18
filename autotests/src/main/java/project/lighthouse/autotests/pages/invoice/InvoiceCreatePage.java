package project.lighthouse.autotests.pages.invoice;

import net.thucydides.core.annotations.DefaultUrl;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import org.openqa.selenium.support.FindBy;
import project.lighthouse.autotests.pages.common.CommonItem;
import project.lighthouse.autotests.pages.common.CommonPageObject;
import project.lighthouse.autotests.pages.elements.Autocomplete;
import project.lighthouse.autotests.pages.elements.DateTime;
import project.lighthouse.autotests.pages.elements.Input;
import project.lighthouse.autotests.pages.elements.NonType;

@DefaultUrl("/invoice/create")
public class InvoiceCreatePage extends CommonPageObject {

    private static final String INVOICE_NAME = "invoice";
    private static final String XPATH_PATTERN = "//*[@class='invoice__dataInput']/*[@name='%s']";
    private static final String XPATH_AC_PATTERN = "//*[@class='invoice__dataInput']/*[@lh_product_autocomplete='%s']";

    @FindBy(xpath = "//*[@lh_link='close']")
    public WebElement invoiceCloseButton;

    public InvoiceCreatePage(WebDriver driver) {
        super(driver);
    }

    public void createElements() {
        items.put("head", new NonType(this, By.className("invoice__head")));

        items.put("sku", new Input(this, "sku"));
        items.put("acceptanceDate", new DateTime(this, "acceptanceDate"));
        items.put("supplier", new Input(this, "supplier"));
        items.put("accepter", new Input(this, "accepter"));
        items.put("recipient", new Input(this, "recipient"));
        items.put("supplierInvoiceSku", new Input(this, "supplierInvoiceSku"));
        items.put("supplierInvoiceDate", new DateTime(this, "supplierInvoiceDate"));
        items.put("legalEntity", new Input(this, "legalEntity"));

        items.put("productName", new Autocomplete(this, By.xpath("//*[@lh_product_autocomplete='name']")));
        items.put("productSku", new Autocomplete(this, By.xpath("//*[@lh_product_autocomplete='sku']")));
        items.put("productBarCode", new Autocomplete(this, By.xpath("//*[@lh_product_autocomplete='barcode']")));
        items.put("productAmount", new Input(this, "quantity"));
        items.put("invoiceCost", new Input(this, "price"));
        items.put("totalProducts", new Input(this, "totalProducts"));
        items.put("totalSum", new Input(this, "totalSum"));

        /*Edit mode*/
        items.put("inline sku", new Input(this, By.xpath(String.format(XPATH_PATTERN, "sku"))));
        items.put("inline acceptanceDate", new DateTime(this, By.xpath(String.format(XPATH_PATTERN, "acceptanceDate"))));
        items.put("inline supplier", new Input(this, By.xpath(String.format(XPATH_PATTERN, "supplier"))));
        items.put("inline accepter", new Input(this, By.xpath(String.format(XPATH_PATTERN, "accepter"))));
        items.put("inline recipient", new Input(this, By.xpath(String.format(XPATH_PATTERN, "recipient"))));
        items.put("inline supplierInvoiceSku", new Input(this, By.xpath(String.format(XPATH_PATTERN, "supplierInvoiceSku"))));
        items.put("inline supplierInvoiceDate", new DateTime(this, By.xpath(String.format(XPATH_PATTERN, "supplierInvoiceDate"))));
        items.put("inline legalEntity", new Input(this, By.xpath(String.format(XPATH_PATTERN, "legalEntity"))));

        items.put("inline productName", new Autocomplete(this, By.xpath(String.format(XPATH_AC_PATTERN, "name"))));
        items.put("inline productSku", new Autocomplete(this, By.xpath(String.format(XPATH_AC_PATTERN, "sku"))));
        items.put("inline productBarCode", new Autocomplete(this, By.xpath(String.format(XPATH_AC_PATTERN, "barcode"))));
        items.put("inline quantity", new Input(this, By.xpath(String.format(XPATH_PATTERN, "quantity"))));
        items.put("inline price", new Input(this, By.xpath(String.format(XPATH_PATTERN, "price"))));
        items.put("inline totalProducts", new Input(this, By.xpath(String.format(XPATH_PATTERN, "totalProducts"))));
        items.put("inline totalSum", new Input(this, By.xpath(String.format(XPATH_PATTERN, "totalSum"))));

    }

    public void invoiceCloseButtonClick() {
        $(invoiceCloseButton).click();
    }

    public void invoiceCreateButtonClick() {
        findBy("//*[@lh_button='commit']").click();
        commonPage.checkCreateAlertSuccess(INVOICE_NAME);
    }

    public void checkFieldLength(String elementName, int fieldLength) {
        CommonItem item = items.get(elementName);
        commonPage.checkFieldLength(elementName, fieldLength, item);
    }

    public void checkFormIsChanged() {
        String alertText = getAlert().getText();
        String expectedMessage = "";
        if (!alertText.contains(expectedMessage)) {
            String errorMessage = "";
            throw new AssertionError(errorMessage);
        }
    }
}
