package project.lighthouse.autotests;

import project.lighthouse.autotests.objects.api.Product;
import project.lighthouse.autotests.objects.api.SubCategory;
import project.lighthouse.autotests.storage.Storage;

import java.util.ArrayList;
import java.util.HashMap;

public class StaticData {

    public static HashMap<String, SubCategory> subCategories = new HashMap<>();
    public static HashMap<String, ArrayList<Product>> subCategoryProducts = new HashMap<>();

    public static Boolean hasSubCategory(String subCategoryName) {
        return subCategories.containsKey(subCategoryName);
    }

    public static void clear() {
        subCategories.clear();
        subCategoryProducts.clear();
        Storage.getOrderVariableStorage().resetNumber();
        Storage.getInvoiceVariableStorage().resetNumber();
        Storage.getUserVariableStorage().getUserContainers().clear();
        Storage.getCustomVariableStorage().getSuppliers().clear();
        Storage.getUserVariableStorage().getUserTokens().clear();
        Storage.getCustomVariableStorage().getStores().clear();
        Storage.getCustomVariableStorage().getProducts().clear();
    }
}
