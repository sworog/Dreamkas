package project.lighthouse.autotests.pages.elements;

import org.openqa.selenium.By;
import project.lighthouse.autotests.common.CommonItem;
import project.lighthouse.autotests.common.CommonPageObject;

public class Select extends CommonItem {

    public Select(CommonPageObject pageObject, String name) {
        super(pageObject, name);
    }

    public Select(CommonPageObject pageObject, By findBy) {
        super(pageObject, findBy);
    }

    @Override
    public void setValue(String value) {
        $().selectByValue(value);
    }
}
