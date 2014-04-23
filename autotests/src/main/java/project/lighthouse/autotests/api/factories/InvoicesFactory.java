package project.lighthouse.autotests.api.factories;

import org.json.JSONException;
import project.lighthouse.autotests.api.abstractFactory.AbstractApiFactory;
import project.lighthouse.autotests.objects.api.Store;
import project.lighthouse.autotests.objects.api.invoice.Invoice;
import project.lighthouse.autotests.objects.api.invoice.InvoiceProduct;

import java.io.IOException;

/**
 * Factory to create invoices
 */
public class InvoicesFactory extends AbstractApiFactory {

    public InvoicesFactory(String userName, String password) {
        super(userName, password);
    }

    public Invoice create(String supplierId,
                          String acceptanceDate,
                          String accepter,
                          String legalEntity,
                          String supplierInvoiceNumber,
                          InvoiceProduct[] invoiceProducts,
                          Store store) throws JSONException, IOException {
        Invoice invoice = new Invoice(supplierId, acceptanceDate, accepter, legalEntity, supplierInvoiceNumber);
        return create(invoice, store, invoiceProducts);
    }

    public Invoice create(Invoice invoice,
                          Store store,
                          InvoiceProduct[] invoiceProducts) throws JSONException, IOException {

        invoice.putProducts(invoiceProducts);
        create(invoice, store);
        return invoice;
    }

    public Invoice create(Invoice invoice, Store store) throws JSONException, IOException {
        invoice.setStore(store);
        createObject(invoice);
        return invoice;
    }
}
