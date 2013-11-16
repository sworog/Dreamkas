package project.lighthouse.autotests.jbehave.api.departmentManager;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import org.jbehave.core.model.ExamplesTable;
import org.json.JSONException;
import project.lighthouse.autotests.helper.DateTimeHelper;
import project.lighthouse.autotests.steps.api.departmentManager.InvoiceApiSteps;

import java.io.IOException;

public class EndInvoiceApiSteps {

    public static ExamplesTable examplesTable;

    @Steps
    InvoiceApiSteps invoiceApiSteps;

    @Given("there is the invoice '$invoiceSku' with product '$productName' name, '$productSku' sku, '$productBarCode' barcode, '$productUnits' units")
    public void givenThereIsInvoiceWithProduct(String invoiceSku, String productName, String productSku, String productBarCode, String productUnits) throws JSONException, IOException {
        productApi.сreateProductThroughPost(productName, productSku, productBarCode, productUnits, "123");
        invoiceApiSteps.createInvoiceThroughPostWithProductAndNavigateToIt(invoiceSku, productSku);
    }

    @Given("there is the invoice with '$sku' sku")
    public void givenThereIsTheInvoiceWithSku(String sku) throws JSONException, IOException {
        invoiceApiSteps.createInvoiceThroughPost(sku);
    }

    @Given("there is the invoice with sku '$sku' in the store with number '$number' ruled by department manager with name '$userName'")
    public void givenThereIsTheInvoiceInTheStore(String sku, String number, String userName) throws IOException, JSONException {
        invoiceApiSteps.createInvoiceThroughPost(sku, number, userName);
    }

    @Given("there is the date invoice with sku '$sku' and date '$date' in the store with number '$number' ruled by department manager with name '$userName'")
    public void givenThereIsTheInvoiceInTheStore(String sku, String date, String number, String userName) throws IOException, JSONException {
        invoiceApiSteps.createInvoiceThroughPost(sku, new DateTimeHelper(date).convertDateTime(), number, userName);
    }

    @Given("there is the invoice in the store with number '$number' ruled by department manager with name '$userName' with values $exampleTable")
    public void givenThereIsTheInvoiceInTheStoreWithValues(String number, String userName, ExamplesTable examplesTable) throws IOException, JSONException {
        invoiceApiSteps.createInvoiceThroughPost(number, userName, examplesTable);
        this.examplesTable = examplesTable;
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
