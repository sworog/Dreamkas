package project.lighthouse.autotests.steps.api.objectBuilder;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.json.JSONException;
import project.lighthouse.autotests.objects.api.invoice.Invoice;
import project.lighthouse.autotests.objects.api.invoice.InvoiceProduct;
import project.lighthouse.autotests.storage.Storage;

public class InvoiceBuilderSteps extends ScenarioSteps {

    @Step
    public void build(String supplierId,
                         String acceptanceDate,
                         String accepter,
                         String legalEntity,
                         String supplierInvoiceNumber) throws JSONException {
        Invoice invoice =  new Invoice(supplierId, acceptanceDate, accepter, legalEntity, supplierInvoiceNumber);
        Storage.getInvoiceVariableStorage().setInvoiceForInvoiceBuilderSteps(invoice);
    }

    @Step
    public void addProduct(String productId, String quantity, String price) throws JSONException {
        InvoiceProduct invoiceProduct = new InvoiceProduct(productId, quantity, price);
        Storage.getInvoiceVariableStorage()
                .getInvoiceForInvoiceBuilderSteps()
                .putProducts(new InvoiceProduct[]{invoiceProduct});
    }
}
