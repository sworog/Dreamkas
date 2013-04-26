package project.lighthouse.autotests.pages.elements;

import org.openqa.selenium.By;
import project.lighthouse.autotests.pages.common.CommonItem;
import project.lighthouse.autotests.pages.common.CommonPage;
import project.lighthouse.autotests.pages.common.CommonPageObject;

public class NonType extends CommonItem {

    public NonType(CommonPageObject pageObject, By findBy) {
        super(pageObject, findBy);
    }

    public NonType(CommonPageObject pageObject, String name) {
        super(pageObject, name);
    }

    @Override
    public void setValue(String value) {
        String errorMessage = String.format(CommonPage.ERROR_MESSAGE, "NonType");
        throw new AssertionError(errorMessage);
    }
}
