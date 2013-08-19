package project.lighthouse.autotests.elements;


import org.openqa.selenium.By;
import project.lighthouse.autotests.common.CommonItem;
import project.lighthouse.autotests.common.CommonPage;
import project.lighthouse.autotests.common.CommonPageObject;

import static junit.framework.Assert.fail;

public class NonType extends CommonItem {

    public NonType(CommonPageObject pageObject, By findBy) {
        super(pageObject, findBy);
    }

    public NonType(CommonPageObject pageObject, String name) {
        super(pageObject, name);
    }

    @Override
    public void setValue(String value) {
        fail(
                String.format(CommonPage.ERROR_MESSAGE, "NonType")
        );
    }
}
