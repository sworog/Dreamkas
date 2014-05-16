package project.lighthouse.autotests.common;

import org.hamcrest.Matchers;
import org.junit.Assert;

import java.util.HashMap;

/**
 * Map to store CommonItem type in page object class
 */
public class CommonItemMap extends HashMap<String, CommonItem> {

    @Override
    public CommonItem get(Object key) {
        Assert.assertThat(
                String.format("There is no element with name '%s'. Available elements: %s", key, this.keySet()),
                super.get(key),
                Matchers.notNullValue());

        return super.get(key);
    }
}
