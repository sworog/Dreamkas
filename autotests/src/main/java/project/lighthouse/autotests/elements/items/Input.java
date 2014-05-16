package project.lighthouse.autotests.elements.items;

import junit.framework.Assert;
import org.openqa.selenium.By;
import project.lighthouse.autotests.common.CommonItem;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Buttons.interfaces.Conditional;

public class Input extends CommonItem implements Conditional {

    public Input(CommonPageObject pageObject, By findBy) {
        super(pageObject, findBy);
    }

    public Input(CommonPageObject pageObject, String name) {
        super(pageObject, name);
    }

    public Input(CommonPageObject pageObject, String name, String label) {
        super(pageObject, name, label);
    }

    @Override
    public void setValue(String value) {
        getVisibleWebElementFacade().type(value);
    }

    @Override
    public Boolean isVisible() {
        throw new AssertionError("Not implemented!");
    }

    @Override
    public Boolean isInvisible() {
        return getPageObject().invisibilityOfElementLocated(getFindBy());
    }

    @Override
    public void shouldBeVisible() {
        throw new AssertionError("Not implemented!");
    }

    @Override
    public void shouldBeNotVisible() {
        if (!isInvisible()) {
            String errorMessage = String.format("Element should be not visible!");
            Assert.fail(errorMessage);
        }
    }
}
