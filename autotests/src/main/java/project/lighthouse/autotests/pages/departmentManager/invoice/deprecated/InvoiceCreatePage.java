package project.lighthouse.autotests.pages.departmentManager.invoice.deprecated;

import net.thucydides.core.annotations.DefaultUrl;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.items.Autocomplete;
import project.lighthouse.autotests.elements.items.DateTime;
import project.lighthouse.autotests.elements.items.Input;
import project.lighthouse.autotests.elements.items.NonType;

@DefaultUrl("/invoices/create")
public class InvoiceCreatePage extends CommonPageObject {

    private static final String XPATH_PATTERN = "//*[@class='invoice__dataInput']/*[@name='%s']";
    private static final String XPATH_AC_PATTERN = "//*[@class='invoice__dataInput']/*[@lh_product_autocomplete='%s']";

    public InvoiceCreatePage(WebDriver driver) {
        super(driver);
    }

    public void createElements() {
        put("head", new NonType(this, By.className("invoice__head")));

        /*Inputs*/
        put("sku", new Input(this, "sku"));
        put("acceptanceDate", new DateTime(this, "acceptanceDate"));
        put("supplier", new Input(this, "supplier"));
        put("accepter", new Input(this, "accepter"));
        put("recipient", new Input(this, "recipient"));
        put("supplierInvoiceSku", new Input(this, "supplierInvoiceSku"));
        put("supplierInvoiceDate", new DateTime(this, "supplierInvoiceDate"));
        put("legalEntity", new Input(this, "legalEntity"));

        put("productName", new Autocomplete(this, By.xpath("//*[@lh_product_autocomplete='name']")));
        put("productSku", new Autocomplete(this, By.xpath("//*[@lh_product_autocomplete='sku']")));
        put("productBarCode", new Autocomplete(this, By.xpath("//*[@lh_product_autocomplete='barcode']")));
        put("productAmount", new Input(this, "quantity"));
        put("invoiceCost", new Input(this, "priceEntered"));
        put("totalProducts", new Input(this, "totalProducts"));
        put("totalSum", new Input(this, "totalSum"));

        put("totalVat", new Input(this, By.xpath("//*[@model-attribute='totalAmountVATFormatted']")));

        /*View*/
        put("productNameView", new NonType(this, "productName"));
        put("productSkuView", new NonType(this, "productSku"));
        put("productBarcodeView", new NonType(this, "productBarcode"));
        put("productAmountView", new NonType(this, "productAmount"));
        put("productPriceView", new NonType(this, "productPrice"));

        /*Edit mode*/
        put("inline sku", new Input(this, By.xpath(String.format(XPATH_PATTERN, "sku"))));
        put("inline acceptanceDate", new DateTime(this, By.xpath(String.format(XPATH_PATTERN, "acceptanceDate")), "acceptanceDate"));
        put("inline supplier", new Input(this, By.xpath(String.format(XPATH_PATTERN, "supplier"))));
        put("inline accepter", new Input(this, By.xpath(String.format(XPATH_PATTERN, "accepter"))));
        put("inline recipient", new Input(this, By.xpath(String.format(XPATH_PATTERN, "recipient"))));
        put("inline supplierInvoiceSku", new Input(this, By.xpath(String.format(XPATH_PATTERN, "supplierInvoiceSku"))));
        put("inline supplierInvoiceDate", new DateTime(this, By.xpath(String.format(XPATH_PATTERN, "supplierInvoiceDate")), "supplierInvoiceDate"));
        put("inline legalEntity", new Input(this, By.xpath(String.format(XPATH_PATTERN, "legalEntity"))));

        put("inline productName", new Autocomplete(this, By.xpath(String.format(XPATH_AC_PATTERN, "name"))));
        put("inline productSku", new Autocomplete(this, By.xpath(String.format(XPATH_AC_PATTERN, "sku"))));
        put("inline productBarCode", new Autocomplete(this, By.xpath(String.format(XPATH_AC_PATTERN, "barcode"))));
        put("inline quantity", new Input(this, By.xpath(String.format(XPATH_PATTERN, "quantity"))));
        put("inline price", new Input(this, By.xpath(String.format(XPATH_PATTERN, "priceEntered"))));
        put("inline totalProducts", new Input(this, By.xpath(String.format(XPATH_PATTERN, "totalProducts"))));
        put("inline totalSum", new Input(this, By.xpath(String.format(XPATH_PATTERN, "totalSum"))));

        put("includesVAT", new NonType(this, By.name("includesVAT")));
    }
}
