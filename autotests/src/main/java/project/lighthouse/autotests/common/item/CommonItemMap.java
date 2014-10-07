package project.lighthouse.autotests.common.item;

import org.hamcrest.Matchers;
import org.junit.Assert;
import project.lighthouse.autotests.common.item.interfaces.CommonItemType;

import java.util.HashMap;

/**
 * Map to store CommonItem type in page object class
 */
public class CommonItemMap extends HashMap<String, CommonItemType> {

    @Override
    public CommonItemType get(Object key) {
        Assert.assertThat(
                String.format("There is no element with name '%s'. Available elements: %s", key, this.keySet()),
                super.get(key),
                Matchers.notNullValue());

        return super.get(key);
    }
}
