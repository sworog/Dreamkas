package project.lighthouse.autotests.pages.departmentManager.invoice;

import junit.framework.Assert;
import org.jbehave.core.model.ExamplesTable;
import org.json.JSONException;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.ApiConnect;
import project.lighthouse.autotests.StaticData;
import project.lighthouse.autotests.elements.DateTime;
import project.lighthouse.autotests.objects.Invoice;
import project.lighthouse.autotests.objects.Store;
import project.lighthouse.autotests.objects.User;
import project.lighthouse.autotests.pages.administrator.users.UserApi;
import project.lighthouse.autotests.pages.commercialManager.catalog.CatalogApi;
import project.lighthouse.autotests.pages.commercialManager.store.StoreApi;
import project.lighthouse.autotests.pages.departmentManager.api.DepartmentManagerApi;

import java.io.IOException;
import java.util.Map;

public class InvoiceApi extends DepartmentManagerApi {

    StoreApi storeApi = new StoreApi(getDriver());
    UserApi userApi = new UserApi(getDriver());
    CatalogApi catalogApi = new CatalogApi(getDriver());

    public InvoiceApi(WebDriver driver) throws JSONException {
        super(driver);
    }

    public Invoice createInvoiceThroughPost(String invoiceName) throws JSONException, IOException {
        Store store = storeApi.createStoreThroughPost();
        User user = userApi.getUser(DEFAULT_USER_NAME);
        catalogApi.promoteDepartmentManager(store, user.getUserName());
        return createInvoiceThroughPost(invoiceName, store.getNumber(), user.getUserName());
    }

    public Invoice createInvoiceThroughPost(String invoiceName, String storeName, String userName) throws JSONException, IOException {
        Invoice invoice = new Invoice(invoiceName, "supplier", DateTime.getTodayDate(DateTime.DATE_TIME_PATTERN), "accepter", "legalEntity", "", "");
        String storeId = StaticData.stores.get(storeName).getId();
        invoice.setStoreId(storeId);
        return new ApiConnect(userName, "lighthouse").createInvoiceThroughPost(invoice);
    }

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

    public void createInvoiceThroughPostAndNavigateToIt(String invoiceName) throws JSONException, IOException {
        createInvoiceThroughPost(invoiceName);
        navigateToTheInvoicePage(invoiceName);
    }

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

    public void addProductToInvoice(String invoiceName, String productSku, String quantity, String price, String userName) throws JSONException, IOException {
        new ApiConnect(userName, "lighthouse").addProductToInvoice(invoiceName, productSku, quantity, price);
    }

    public void navigateToTheInvoicePage(String invoiceName) throws JSONException {
        String invoicePageUrl = apiConnect.getInvoicePageUrl(invoiceName);
        getDriver().navigate().to(invoicePageUrl);
    }
}
