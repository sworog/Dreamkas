package project.lighthouse.autotests.pages.elements;

import net.thucydides.core.pages.PageObject;
import org.openqa.selenium.By;
import project.lighthouse.autotests.pages.common.CommonItem;
import project.lighthouse.autotests.pages.common.CommonPage;

/**
 * Created with IntelliJ IDEA.
 * User: atolpeev
 * Date: 16.04.13
 * Time: 16:02
 * To change this template use File | Settings | File Templates.
 */
public class NonType extends CommonItem {

    types type = types.nonType;

    public NonType(PageObject pageObject, By findBy) {
        super(pageObject, findBy);
    }

    public NonType(PageObject pageObject, String name) {
        super(pageObject, name);
    }

    @Override
    public void setValue(String value) {
        String errorMessage = String.format(CommonPage.ERROR_MESSAGE, type);
        throw new AssertionError(errorMessage);
    }
}
