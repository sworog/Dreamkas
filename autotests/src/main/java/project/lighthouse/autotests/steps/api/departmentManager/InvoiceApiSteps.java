package project.lighthouse.autotests.steps.api.departmentManager;

import junit.framework.Assert;
import net.thucydides.core.annotations.Step;
import org.jbehave.core.model.ExamplesTable;
import org.json.JSONException;
import project.lighthouse.autotests.ApiConnect;
import project.lighthouse.autotests.StaticData;
import project.lighthouse.autotests.elements.DateTime;
import project.lighthouse.autotests.objects.api.Invoice;
import project.lighthouse.autotests.objects.api.Store;
import project.lighthouse.autotests.objects.api.User;
import project.lighthouse.autotests.pages.commercialManager.catalog.CatalogApi;
import project.lighthouse.autotests.pages.commercialManager.store.StoreApi;
import project.lighthouse.autotests.steps.api.administrator.UserApiSteps;

import java.io.IOException;
import java.util.Map;

public class InvoiceApiSteps extends DepartmentManagerApi {

    StoreApi storeApi = new StoreApi();
    UserApiSteps userApiSteps = new UserApiSteps();
    CatalogApi catalogApi = new CatalogApi();

    public InvoiceApiSteps() throws JSONException {
    }

    @Step
    public Invoice createInvoiceThroughPost(String invoiceName) throws JSONException, IOException {
        Store store = storeApi.createStoreThroughPost();
        User user = userApiSteps.getUser(DEFAULT_USER_NAME);
        catalogApi.promoteDepartmentManager(store, user.getUserName());
        return createInvoiceThroughPost(invoiceName, store.getNumber(), user.getUserName());
    }

    @Step
    public Invoice createInvoiceThroughPost(String invoiceName, String storeName, String userName) throws JSONException, IOException {
        return createInvoiceThroughPost(invoiceName, DateTime.getTodayDate(DateTime.DATE_TIME_PATTERN), storeName, userName);
    }

    @Step
    public Invoice createInvoiceThroughPost(String invoiceName, String date, String storeName, String userName) throws JSONException, IOException {
        Invoice invoice = new Invoice(invoiceName, "supplier", date, "accepter", "legalEntity", "", "");
        String storeId = StaticData.stores.get(storeName).getId();
        invoice.setStoreId(storeId);
        return new ApiConnect(userName, "lighthouse").createInvoiceThroughPost(invoice);
    }

    @Step
    public Invoice createInvoiceThroughPost(String storeName, String userName, ExamplesTable examplesTable) throws JSONException, IOException {
        String sku = "", acceptanceDate = "", supplier = "", accepter = "", legalEntity = "", supplierInvoiceSku = "", supplierInvoiceDate = "";
        for (Map<String, String> row : examplesTable.getRows()) {
            String elementName = row.get("elementName");
            String elementValue = row.get("elementValue");
            switch (elementName) {
                case "sku":
                    sku = elementValue;
                    break;
                case "acceptanceDate":
                    acceptanceDate = elementValue;
                    break;
                case "supplier":
                    supplier = elementValue;
                    break;
                case "accepter":
                    accepter = elementValue;
                    break;
                case "legalEntity":
                    legalEntity = elementValue;
                    break;
                case "supplierInvoiceSku":
                    supplierInvoiceSku = elementValue;
                    break;
                case "supplierInvoiceDate":
                    supplierInvoiceDate = elementValue;
                    break;
                default:
                    Assert.fail(String.format("No such elementName '%s'", elementName));
                    break;
            }
        }
        Invoice invoice = new Invoice(sku, supplier, acceptanceDate, accepter, legalEntity, supplierInvoiceSku, supplierInvoiceDate);
        String storeId = StaticData.stores.get(storeName).getId();
        invoice.setStoreId(storeId);
        return new ApiConnect(userName, "lighthouse").createInvoiceThroughPost(invoice);
    }

    @Step
    public void createInvoiceThroughPostAndNavigateToIt(String invoiceName) throws JSONException, IOException {
        createInvoiceThroughPost(invoiceName);
        navigateToTheInvoicePage(invoiceName);
    }

    @Step
    public void createInvoiceThroughPostWithProductAndNavigateToIt(String invoiceName, String productSku) throws IOException, JSONException {
        createInvoiceThroughPost(invoiceName);
        addProductToInvoice(invoiceName, productSku, "1", "1", DEFAULT_USER_NAME);
        navigateToTheInvoicePage(invoiceName);
    }

    //TODO make given method->>>
    public void createInvoiceThroughPostWithProductAndNavigateToIt(String invoiceName, String productSku, String storeName, String userName) throws IOException, JSONException {
        createInvoiceThroughPost(invoiceName, storeName, userName);
        addProductToInvoice(invoiceName, productSku, "1", "1", userName);
        navigateToTheInvoicePage(invoiceName);
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
