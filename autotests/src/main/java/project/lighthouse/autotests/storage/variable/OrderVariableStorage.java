package project.lighthouse.autotests.storage.variable;

import project.lighthouse.autotests.objects.api.Product;
import project.lighthouse.autotests.objects.api.Supplier;

public class OrderVariableStorage {

    private Supplier supplier;
    private Product product;
    private int number = 10000;

    public Supplier getSupplier() {
        return supplier;
    }

    public Product getProduct() {
        return product;
    }

    public void setSupplier(Supplier supplier) {
        this.supplier = supplier;
    }

    public void setProduct(Product product) {
        this.product = product;
    }

    public Integer getNumber() {
        return number;
    }

    public void resetNumber() {
        number = 10000;
    }

    public void incrementNumber() {
        number++;
    }
}
