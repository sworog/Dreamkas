package project.lighthouse.autotests.jbehave.api.builder;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import org.json.JSONException;
import project.lighthouse.autotests.StaticData;
import project.lighthouse.autotests.objects.api.Store;
import project.lighthouse.autotests.objects.api.Supplier;
import project.lighthouse.autotests.steps.api.builder.InvoiceBuilderSteps;

public class InvoiceBuilderUserSteps {

    @Steps
    InvoiceBuilderSteps invoiceBuilderSteps;

    @Given("the user creates invoice api object with date '$date', paid status '$paid', store with name '$storeName', supplier with name '$supplierName'")
    public void givenTheUserWithEmailCreatesInvoiceApiObject(String date, Boolean paid, String storeName, String supplierName) throws JSONException {
        Store store = StaticData.stores.get(storeName);
        Supplier supplier = StaticData.suppliers.get(supplierName);
        invoiceBuilderSteps.build(date, paid, store.getId(), supplier.getId());
    }

    @Given("the user adds the product with name '$name' with price '$price' and quantity '$quantity 'to invoice api object")
    public void givenTheUserAddsTheProductToInvoiceApiObject(String name, String price, String quantity) throws JSONException {
        invoiceBuilderSteps.addProduct(
                StaticData.products.get(name).getId(),
                quantity,
                price);
    }
}
