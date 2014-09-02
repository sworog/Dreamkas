package project.lighthouse.autotests;

import project.lighthouse.autotests.objects.api.*;
import project.lighthouse.autotests.storage.Storage;

import java.util.ArrayList;
import java.util.HashMap;

public class StaticData {

    public static HashMap<String, Product> products = new HashMap<>();
    public static HashMap<String, Group> groups = new HashMap<>();
    public static HashMap<String, Category> categories = new HashMap<>();
    public static HashMap<String, SubCategory> subCategories = new HashMap<>();
    public static HashMap<String, Store> stores = new HashMap<>();
    public static HashMap<String, ArrayList<Product>> subCategoryProducts = new HashMap<>();

    public static Boolean isGroupCreated(String groupName) {
        return groups.containsKey(groupName);
    }

    public static Boolean hasSubCategory(String subCategoryName) {
        return subCategories.containsKey(subCategoryName);
    }

    public static void clear() {
        products.clear();
        groups.clear();
        categories.clear();
        subCategories.clear();
        stores.clear();
        subCategoryProducts.clear();
        Storage.getOrderVariableStorage().resetNumber();
        Storage.getInvoiceVariableStorage().resetNumber();
        Storage.getUserVariableStorage().getUserContainers().clear();
    }
}
