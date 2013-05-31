package project.lighthouse.autotests.pages.elements;


import project.lighthouse.autotests.common.CommonItem;
import project.lighthouse.autotests.common.CommonPageObject;

public class InputOnlyVisible extends CommonItem {

    public InputOnlyVisible(CommonPageObject pageObject, String name) {
        super(pageObject, name);
    }

    @Override
    public void setValue(String value) {
        getOnlyVisibleWebElementFacade().type(value);
    }
}
