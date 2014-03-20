package project.lighthouse.autotests.storage.variable;

import project.lighthouse.autotests.objects.api.Product;
import project.lighthouse.autotests.objects.api.Supplier;

public class OrderVariableStorage {

    private Supplier supplier;
    private Product product;
    private Integer number = 10000;

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
}
