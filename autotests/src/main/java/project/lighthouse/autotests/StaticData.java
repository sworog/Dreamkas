package project.lighthouse.autotests;

import project.lighthouse.autotests.objects.api.*;
import project.lighthouse.autotests.objects.api.invoice.Invoice;
import project.lighthouse.autotests.storage.Storage;

import java.util.ArrayList;
import java.util.HashMap;

public class StaticData {

    public static HashMap<String, Product> products = new HashMap<>();
    public static HashMap<String, Invoice> invoices = new HashMap<>();
    public static HashMap<String, WriteOff> writeOffs = new HashMap<>();
    public static HashMap<String, Group> groups = new HashMap<>();
    public static HashMap<String, Category> categories = new HashMap<>();
    public static HashMap<String, SubCategory> subCategories = new HashMap<>();
    public static HashMap<String, User> users = new HashMap<>();
    public static HashMap<String, Store> stores = new HashMap<>();
    public static HashMap<String, Department> departments = new HashMap<>();
    public static HashMap<String, String> userTokens = new HashMap<>();
    public static HashMap<String, ArrayList<Product>> subCategoryProducts = new HashMap<>();
    public static HashMap<String, Supplier> suppliers = new HashMap<>();

    public static Integer DEFAULT_TIMEOUT = 15;
    public static Integer DEFAULT_PRE_LOADER_TIMEOUT = 5;
    public static Integer DEFAULT_VALIDATION_ERROR_TIMEOUT = 10;

    public static String WEB_DRIVER_BASE_URL;

    public static String retailMarkupMax;
    public static String retailMarkupMin;

    public static final String client_id = "autotests_autotests";
    public static final String client_secret = "secret";

    static public Boolean demoMode = false;
    static public Boolean isPaused = true;

    public static Boolean isGroupCreated(String groupName) {
        return groups.containsKey(groupName);
    }

    public static Boolean hasSubCategory(String subCategoryName) {
        return subCategories.containsKey(subCategoryName);
    }

    public static Boolean hasStore(String storeNumber) {
        return stores.containsKey(storeNumber);
    }

    public static Boolean hasDepartment(String departmentNumber) {
        return stores.containsKey(departmentNumber);
    }

    public static void clear() {
        products.clear();
        invoices.clear();
        writeOffs.clear();
        groups.clear();
        categories.clear();
        subCategories.clear();
        users.clear();
        stores.clear();
        departments.clear();
        userTokens.clear();
        subCategoryProducts.clear();
        suppliers.clear();
        Storage.getOrderVariableStorage().resetNumber();
        Storage.getInvoiceVariableStorage().resetNumber();
    }
}
