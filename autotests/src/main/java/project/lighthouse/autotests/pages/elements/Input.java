package project.lighthouse.autotests.pages.elements;

import net.thucydides.core.pages.PageObject;
import org.openqa.selenium.By;
import project.lighthouse.autotests.pages.common.CommonItem;

public class Input extends CommonItem {

    public Input(PageObject pageObject, By findBy) {
        super(pageObject, findBy);
    }

    public Input(PageObject pageObject, String name) {
        super(pageObject, name);
    }

    @Override
    public void setValue(String value) {
        $().type(value);
    }
}
