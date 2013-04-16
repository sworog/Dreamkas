package project.lighthouse.autotests.pages.elements;

import net.thucydides.core.pages.PageObject;
import org.openqa.selenium.By;
import project.lighthouse.autotests.pages.common.CommonItem;

/**
 * Created with IntelliJ IDEA.
 * User: atolpeev
 * Date: 16.04.13
 * Time: 15:47
 * To change this template use File | Settings | File Templates.
 */
public class Select extends CommonItem {

    types type = types.textarea;

    public Select(PageObject pageObject, By findBy) {
        super(pageObject, findBy);
    }

    public Select(PageObject pageObject, String name) {
        super(pageObject, name);
    }

    @Override
    public void setValue(String value) {
        $().selectByValue(value);
    }
}
