package project.lighthouse.autotests.storage.variable;

import org.json.JSONException;
import project.lighthouse.autotests.helper.ObjectsFactory;
import project.lighthouse.autotests.objects.api.Product;
import project.lighthouse.autotests.objects.api.Supplier;
import project.lighthouse.autotests.objects.api.invoice.Invoice;

import java.util.ArrayList;
import java.util.List;

public class InvoiceVariableStorage {

    private Product product;
    private Supplier supplier;
    private String quantity;
    private String price;
    private String acceptanceDate;
    private String accepter;
    private String legalEntity;
    private String supplierInvoiceNumber;

    private Invoice invoice;

    private Integer number = 10000;

    private List<Invoice> invoiceList = new ArrayList<>();

    public InvoiceVariableStorage() throws JSONException {
        supplier = ObjectsFactory.getSupplierObject();
        product = ObjectsFactory.getProductObject();
    }

    public String getNumber() {
        return number.toString();
    }

    public String getPreviousNumber() {
        return Integer.toString(number - 1);
    }

    public void resetNumber() {
        number = 10000;
    }

    public void incrementNumber() {
        number++;
    }

    public Product getProduct() {
        return product;
    }

    public Supplier getSupplier() {
        return supplier;
    }

    public String getQuantity() {
        return quantity;
    }

    public String getPrice() {
        return price;
    }

    public String getAcceptanceDate() {
        return acceptanceDate;
    }

    public String getAccepter() {
        return accepter;
    }

    public String getLegalEntity() {
        return legalEntity;
    }

    public String getSupplierInvoiceNumber() {
        return supplierInvoiceNumber;
    }

    public InvoiceVariableStorage setProduct(Product product) {
        this.product = product;
        return this;
    }

    public InvoiceVariableStorage setSupplier(Supplier supplier) {
        this.supplier = supplier;
        return this;
    }

    public InvoiceVariableStorage setQuantity(String quantity) {
        this.quantity = quantity;
        return this;
    }

    public InvoiceVariableStorage setPrice(String price) {
        this.price = price;
        return this;
    }

    public InvoiceVariableStorage setAcceptanceDate(String acceptanceDate) {
        this.acceptanceDate = acceptanceDate;
        return this;
    }

    public InvoiceVariableStorage setAccepter(String accepter) {
        this.accepter = accepter;
        return this;
    }

    public InvoiceVariableStorage setLegalEntity(String legalEntity) {
        this.legalEntity = legalEntity;
        return this;
    }

    public InvoiceVariableStorage setSupplierInvoiceNumber(String supplierInvoiceNumber) {
        this.supplierInvoiceNumber = supplierInvoiceNumber;
        return this;
    }

    public void setInvoiceForInvoiceBuilderSteps(Invoice invoice) {
        this.invoice = invoice;
    }

    public Invoice getInvoiceForInvoiceBuilderSteps() {
        return invoice;
    }

    public List<Invoice> getInvoiceList() {
        return invoiceList;
    }
}
