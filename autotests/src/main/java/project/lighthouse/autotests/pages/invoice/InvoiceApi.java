package project.lighthouse.autotests.pages.invoice;

import net.thucydides.core.pages.PageObject;
import org.json.JSONException;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.ApiConnect;

import java.io.IOException;

public class InvoiceApi extends PageObject {

    ApiConnect apiConnect = new ApiConnect(getDriver());

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
