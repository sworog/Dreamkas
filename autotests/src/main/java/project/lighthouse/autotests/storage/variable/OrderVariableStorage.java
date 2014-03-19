package project.lighthouse.autotests.storage.variable;

import project.lighthouse.autotests.objects.api.Product;
import project.lighthouse.autotests.objects.api.Supplier;

public class OrderVariableStorage {

    public Supplier supplier;
    public Product product;
    private int number = 10000;

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
