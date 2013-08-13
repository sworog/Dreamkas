package project.lighthouse.autotests.elements;

import org.openqa.selenium.By;
import project.lighthouse.autotests.common.CommonItem;
import project.lighthouse.autotests.common.CommonPageObject;

public class SelectByVisibleText extends CommonItem {

    public SelectByVisibleText(CommonPageObject pageObject, By findBy) {
        super(pageObject, findBy);
    }

    public SelectByVisibleText(CommonPageObject pageObject, String name) {
        super(pageObject, name);
    }

    @Override
    public void setValue(String label) {
        selectByVisibleText(label);
    }
}
