package project.lighthouse.autotests;

import org.json.JSONException;
import project.lighthouse.autotests.objects.*;

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
    public static HashMap<String, OauthAuthorizeData> userTokens = new HashMap<>();
    public static Integer TIMEOUT = 5000;
    public static String WEB_DRIVER_BASE_URL;

    public static final String client_id = "autotests_autotests";
    public static final String client_secret = "secret";

    public static Boolean isGroupCreated(String groupName) {
        return groups.containsKey(groupName);
    }

    public static Boolean hasGroup(String categoryName, String groupName) throws JSONException {
        try {
            return categories.get(categoryName).hasGroup(groupName);
        } catch (NullPointerException e) {
            return false;
        }
    }

    public static Boolean hasCategory(String categoryName, String subCategoryName) throws JSONException {
        try {
            return subCategories.get(subCategoryName).hasCategory(categoryName);
        } catch (NullPointerException e) {
            return false;
        }
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
}
