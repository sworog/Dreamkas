package project.lighthouse.autotests.jbehave.api;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import org.jbehave.core.model.ExamplesTable;
import org.json.JSONException;
import org.junit.Assert;
import project.lighthouse.autotests.elements.DateTime;
import project.lighthouse.autotests.helper.DateTimeHelper;
import project.lighthouse.autotests.objects.api.Store;
import project.lighthouse.autotests.objects.api.SubCategory;
import project.lighthouse.autotests.objects.api.User;
import project.lighthouse.autotests.steps.api.administrator.UserApiSteps;
import project.lighthouse.autotests.steps.api.commercialManager.CatalogApiSteps;
import project.lighthouse.autotests.steps.api.commercialManager.ProductApiSteps;
import project.lighthouse.autotests.steps.api.commercialManager.StoreApiSteps;
import project.lighthouse.autotests.steps.api.departmentManager.InvoiceApiSteps;

import java.io.IOException;
import java.util.Map;

/**
 * TODO Remove navigateToTheInvoicePage() methods from each create invoice method.
 * TODO Add the navigateToTheInvoicePage() step method to each scenario, where create invoice step is used
 */

public class EndInvoiceApiSteps {

    public static ExamplesTable examplesTable;

    @Steps
    InvoiceApiSteps invoiceApiSteps;

    @Steps
    ProductApiSteps productApiSteps;

    @Steps
    StoreApiSteps storeApiSteps;

    @Steps
    UserApiSteps userApiSteps;

    @Steps
    CatalogApiSteps catalogApiSteps;

    @Given("there is the invoice '$invoiceSku' with product '$productName' name, '$productSku' sku, '$productBarCode' barcode, '$productUnits' units")
    public void givenThereIsInvoiceWithProduct(String invoiceSku, String productName, String productSku, String productBarCode, String productUnits) throws JSONException, IOException {
        catalogApiSteps.createDefaultSubCategoryThroughPost();
        productApiSteps.createProductThroughPost(productName, productSku, productBarCode, productUnits, "123", SubCategory.DEFAULT_NAME);
        Store store = storeApiSteps.createStoreThroughPost();
        User user = userApiSteps.getUser("departmentManager");
        catalogApiSteps.promoteDepartmentManager(store, user.getUserName());
        invoiceApiSteps.createInvoiceThroughPost(invoiceSku, DateTime.getTodayDate(DateTime.DATE_TIME_PATTERN), "supplier", "accepter", "legalEntity", "", "", store.getNumber(), user.getUserName());
        invoiceApiSteps.addProductToInvoice(invoiceSku, productSku, "1", "1", "departmentManager");
        invoiceApiSteps.navigateToTheInvoicePage(invoiceSku);
    }

    @Given("there is the invoice with '$sku' sku")
    public void givenThereIsTheInvoiceWithSku(String sku) throws JSONException, IOException {
        Store store = storeApiSteps.createStoreThroughPost();
        User user = userApiSteps.getUser("departmentManager");
        catalogApiSteps.promoteDepartmentManager(store, user.getUserName());
        givenThereIsTheInvoiceInTheStore(sku, store.getNumber(), user.getUserName());
    }

    @Given("there is the invoice with sku '$sku' in the store with number '$number' ruled by department manager with name '$userName'")
    public void givenThereIsTheInvoiceInTheStore(String sku, String number, String userName) throws IOException, JSONException {
        invoiceApiSteps.createInvoiceThroughPost(sku, DateTime.getTodayDate(DateTime.DATE_TIME_PATTERN), "supplier", "accepter", "legalEntity", "", "", number, userName);
        givenTheUserNavigatesToTheInvoicePage(sku);
    }

    @Given("there is the date invoice with sku '$sku' and date '$date' in the store with number '$number' ruled by department manager with name '$userName'")
    public void givenThereIsTheInvoiceInTheStore(String sku, String date, String number, String userName) throws IOException, JSONException {
        invoiceApiSteps.createInvoiceThroughPost(sku, new DateTimeHelper(date).convertDateTime(), "supplier", "accepter", "legalEntity", "", "", number, userName);
        givenTheUserNavigatesToTheInvoicePage(sku);
    }

    @Given("there is the invoice in the store with number '$number' ruled by department manager with name '$userName' with values $exampleTable")
    public void givenThereIsTheInvoiceInTheStoreWithValues(String number, String userName, ExamplesTable examplesTable) throws IOException, JSONException {
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
        invoiceApiSteps.createInvoiceThroughPost(sku, acceptanceDate, supplier, accepter, legalEntity, supplierInvoiceSku, supplierInvoiceDate, number, userName);
        EndInvoiceApiSteps.examplesTable = examplesTable;
        givenTheUserNavigatesToTheInvoicePage(sku);
    }

    @Given("the user navigates to the invoice page with name '$invoiceName'")
    public void givenTheUserNavigatesToTheInvoicePage(String invoiceName) throws JSONException {
        invoiceApiSteps.navigateToTheInvoicePage(invoiceName);
    }

    @Given("the user adds the product to the invoice with name '$invoiceName' with sku '$productSku', quantity '$quantity', price '$price' in the store ruled by '$userName'")
    public void addProductToInvoice(String invoiceName, String productSku, String quantity, String price, String userName) throws IOException, JSONException {
        invoiceApiSteps.addProductToInvoice(invoiceName, productSku, quantity, price, userName);
    }
}
