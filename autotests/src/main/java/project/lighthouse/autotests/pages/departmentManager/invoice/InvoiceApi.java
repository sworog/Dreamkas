package project.lighthouse.autotests.pages.departmentManager.invoice;

import org.json.JSONException;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.elements.DateTime;
import project.lighthouse.autotests.objects.Invoice;
import project.lighthouse.autotests.pages.departmentManager.api.DepartmentManagerApi;

import java.io.IOException;

public class InvoiceApi extends DepartmentManagerApi {

    public InvoiceApi(WebDriver driver) throws JSONException {
        super(driver);
    }

    public Invoice createInvoiceThroughPost(String invoiceName) throws JSONException, IOException {
        Invoice invoice = new Invoice(invoiceName, "supplier", DateTime.getTodayDate(DateTime.DATE_TIME_PATTERN), "accepter", "legalEntity", "", "");
        return apiConnect.createInvoiceThroughPost(invoice);
    }

    public void createInvoiceThroughPostAndNavigateToIt(String invoiceName) throws JSONException, IOException {
        createInvoiceThroughPost(invoiceName);
        navigateToTheInvoicePage(invoiceName);
    }

    public void createInvoiceThroughPostWithProductAndNavigateToIt(String invoiceName, String productSku) throws IOException, JSONException {
        createInvoiceThroughPost(invoiceName);
        apiConnect.addProductToInvoice(invoiceName, productSku);
        navigateToTheInvoicePage(invoiceName);
    }

    public void navigateToTheInvoicePage(String invoiceName) throws JSONException {
        String invoicePageUrl = apiConnect.getInvoicePageUrl(invoiceName);
        getDriver().navigate().to(invoicePageUrl);
    }
}
