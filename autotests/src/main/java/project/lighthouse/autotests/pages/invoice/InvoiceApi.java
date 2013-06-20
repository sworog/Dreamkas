package project.lighthouse.autotests.pages.invoice;

import org.json.JSONException;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonApi;

import java.io.IOException;

public class InvoiceApi extends CommonApi {

    public InvoiceApi(WebDriver driver) {
        super(driver);
    }

    public void createInvoiceThroughPost(String invoiceName) throws JSONException, IOException {
        apiConnect.createInvoiceThroughPost(invoiceName);
    }

    public void createInvoiceThroughPost(String invoiceName, String productSku) throws IOException, JSONException {
        apiConnect.createInvoiceThroughPost(invoiceName, productSku);
    }
}
