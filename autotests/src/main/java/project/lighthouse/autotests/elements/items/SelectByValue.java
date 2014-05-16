package project.lighthouse.autotests.elements.items;

import org.openqa.selenium.By;
import project.lighthouse.autotests.common.CommonItem;
import project.lighthouse.autotests.common.CommonPageObject;

public class SelectByValue extends CommonItem {

    public SelectByValue(CommonPageObject pageObject, String name) {
        super(pageObject, name);
    }

    public SelectByValue(CommonPageObject pageObject, By findBy) {
        super(pageObject, findBy);
    }

    @Override
    public void setValue(String value) {
        selectByValue(value);
    }
}
