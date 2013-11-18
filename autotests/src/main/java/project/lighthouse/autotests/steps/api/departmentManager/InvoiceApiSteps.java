package project.lighthouse.autotests.steps.api.departmentManager;

import net.thucydides.core.annotations.Step;
import org.json.JSONException;
import project.lighthouse.autotests.ApiConnect;
import project.lighthouse.autotests.StaticData;
import project.lighthouse.autotests.objects.api.Invoice;

import java.io.IOException;

public class InvoiceApiSteps extends DepartmentManagerApi {

    public InvoiceApiSteps() throws JSONException {
    }

    @Step
    public Invoice createInvoiceThroughPost(String invoiceName, String date, String supplier, String accepter, String legalEntity, String supplierInvoiceSku, String supplierInvoiceDate, String storeName, String userName) throws JSONException, IOException {
        Invoice invoice = new Invoice(invoiceName, supplier, date, accepter, legalEntity, supplierInvoiceSku, supplierInvoiceDate);
        String storeId = StaticData.stores.get(storeName).getId();
        invoice.setStoreId(storeId);
        return new ApiConnect(userName, "lighthouse").createInvoiceThroughPost(invoice);
    }

    @Step
    public void addProductToInvoice(String invoiceName, String productSku, String quantity, String price, String userName) throws JSONException, IOException {
        new ApiConnect(userName, "lighthouse").addProductToInvoice(invoiceName, productSku, quantity, price);
    }

    @Step
    public void navigateToTheInvoicePage(String invoiceName) throws JSONException {
        String invoicePageUrl = apiConnect.getInvoicePageUrl(invoiceName);
        getDriver().navigate().to(invoicePageUrl);
    }
}
