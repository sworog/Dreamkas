package project.lighthouse.autotests.storage.variable;

import project.lighthouse.autotests.api.objects.Product;
import project.lighthouse.autotests.api.objects.Store;
import project.lighthouse.autotests.api.objects.SubCategory;
import project.lighthouse.autotests.api.objects.Supplier;
import project.lighthouse.autotests.storage.Storage;
import project.lighthouse.autotests.storage.StorageClearable;

import java.util.ArrayList;
import java.util.HashMap;
import java.util.Map;

public class CustomVariableStorage implements StorageClearable {

    private String email;

    private String name;

    private String mainWindowHandle;

    public String getEmail() {
        return email;
    }

    public void setEmail(String email) {
        this.email = email;
    }

    public String getName() {
        return name;
    }

    public void setName(String name) {
        this.name = name;
    }

    public String getMainWindowHandle() {
        return mainWindowHandle;
    }

    public void setMainWindowHandle(String mainWindowHandle) {
        this.mainWindowHandle = mainWindowHandle;
    }

    private Map<String, Supplier> suppliers = new HashMap<>();

    public Map<String, Supplier> getSuppliers() {
        return suppliers;
    }

    private HashMap<String, Store> stores = new HashMap<>();

    public HashMap<String, Store> getStores() {
        return stores;
    }

    private HashMap<String, Product> products = new HashMap<>();

    public HashMap<String, Product> getProducts() {
        return products;
    }

    private HashMap<String, SubCategory> subCategories = new HashMap<>();
    private HashMap<String, ArrayList<Product>> subCategoryProducts = new HashMap<>();

    public HashMap<String, SubCategory> getSubCategories() {
        return subCategories;
    }

    public HashMap<String, ArrayList<Product>> getSubCategoryProducts() {
        return subCategoryProducts;
    }

    public Map<String, String> salesMap = new HashMap<>();

    public Map<String, String> getSalesMap() {
        return salesMap;
    }

    public void clear() {
        Storage.getUserVariableStorage().getUserContainers().clear();
        Storage.getUserVariableStorage().getUserTokens().clear();
        suppliers.clear();
        stores.clear();
        products.clear();
        subCategories.clear();
        subCategoryProducts.clear();
        salesMap.clear();
    }
}
