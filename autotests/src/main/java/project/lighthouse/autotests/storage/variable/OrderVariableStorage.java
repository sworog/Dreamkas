package project.lighthouse.autotests.storage.variable;

import org.json.JSONException;
import project.lighthouse.autotests.helper.ObjectsFactory;
import project.lighthouse.autotests.objects.api.Product;
import project.lighthouse.autotests.objects.api.Supplier;

public class OrderVariableStorage {

    private Supplier supplier;
    private Product product;
    private Integer number = 10000;
    private String quantity = "0";

    public OrderVariableStorage() throws JSONException {
        supplier = ObjectsFactory.getSupplierObject();
        product = ObjectsFactory.getProductObject();
    }

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

    public String getQuantity() {
        return quantity;
    }

    public void setQuantity(String quantity) {
        this.quantity = quantity;
    }
}
