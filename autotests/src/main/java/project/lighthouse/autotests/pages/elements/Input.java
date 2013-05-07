package project.lighthouse.autotests.pages.elements;

import org.openqa.selenium.By;
import project.lighthouse.autotests.pages.common.CommonItem;
import project.lighthouse.autotests.pages.common.CommonPageObject;

public class Input extends CommonItem {

    public Input(CommonPageObject pageObject, By findBy) {
        super(pageObject, findBy);
    }

    public Input(CommonPageObject pageObject, String name) {
        super(pageObject, name);
    }

    @Override
    public void setValue(String value) {
        $().type(value);
    }
}
