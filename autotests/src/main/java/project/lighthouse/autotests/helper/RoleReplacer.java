package project.lighthouse.autotests.helper;

import java.util.HashMap;
import java.util.Map;

/**
 * class to handle role replacement values
 */
public class RoleReplacer {

    private static final HashMap<String, String> roles = new HashMap<String, String>() {
        {
            put("commercialManager", "ROLE_COMMERCIAL_MANAGER");
            put("storeManager", "ROLE_STORE_MANAGER");
            put("departmentManager", "ROLE_DEPARTMENT_MANAGER");
            put("administrator", "ROLE_ADMINISTRATOR");
        }
    };

    public static String replace(String value) {
        for (Map.Entry<String, String> role : roles.entrySet()) {
            value = value.replaceAll(role.getKey(), role.getValue());
        }
        return value;
    }
}
