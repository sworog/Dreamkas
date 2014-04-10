package project.lighthouse.autotests.api.factories;

import org.json.JSONException;
import project.lighthouse.autotests.api.abstractFactory.AbstractFactory;
import project.lighthouse.autotests.objects.api.invoice.Invoice;
import project.lighthouse.autotests.objects.api.invoice.InvoiceProduct;

import java.io.IOException;

/**
 * Factory to create invoices
 */
public class InvoicesFactory extends AbstractFactory {

    public InvoicesFactory(String userName, String password) {
        super(userName, password);
    }

    public Invoice create(String supplierId,
                          String acceptanceDate,
                          String accepter,
                          String legalEntity,
                          String supplierInvoiceNumber,
                          InvoiceProduct[] invoiceProducts,
                          String storeId) throws JSONException, IOException {
        Invoice invoice = new Invoice(supplierId, acceptanceDate, accepter, legalEntity, supplierInvoiceNumber);
        invoice.setStoreId(storeId);
        invoice.putProducts(invoiceProducts);
        getHttpExecutor().executePostRequest(invoice);
        return invoice;
    }
}
