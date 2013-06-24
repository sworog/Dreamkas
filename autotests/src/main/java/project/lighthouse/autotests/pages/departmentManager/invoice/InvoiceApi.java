package project.lighthouse.autotests.pages.departmentManager.invoice;

import org.json.JSONException;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.pages.departmentManager.api.DepartmentManagerApi;

import java.io.IOException;

public class InvoiceApi extends DepartmentManagerApi {

    public InvoiceApi(WebDriver driver) throws JSONException {
        super(driver);
    }

    public void createInvoiceThroughPost(String invoiceName) throws JSONException, IOException {
        apiConnect.createInvoiceThroughPost(invoiceName);
        navigateToTheInvoicePage(invoiceName);
    }

    public void createInvoiceThroughPost(String invoiceName, String productSku) throws IOException, JSONException {
        apiConnect.createInvoiceThroughPost(invoiceName, productSku);
        navigateToTheInvoicePage(invoiceName);
    }

    public void navigateToTheInvoicePage(String invoiceName) throws JSONException {
        String invoicePageUrl = apiConnect.getInvoicePageUrl(invoiceName);
        getDriver().navigate().to(invoicePageUrl);
    }
}
