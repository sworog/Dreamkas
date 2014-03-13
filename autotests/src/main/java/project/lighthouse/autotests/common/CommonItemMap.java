package project.lighthouse.autotests.common;

import java.util.HashMap;

/**
 * Map to store CommonItem type in page object class
 */
public class CommonItemMap extends HashMap<String, CommonItem> {

    @Override
    public CommonItem get(Object key) {
        try {
            return super.get(key);
        } catch (NullPointerException e) {
            String errorMessage = String.format("There is no element with name '%s'", key);
            throw new AssertionError(errorMessage);
        }
    }
}
