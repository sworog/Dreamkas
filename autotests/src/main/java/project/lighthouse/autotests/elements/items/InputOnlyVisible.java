package project.lighthouse.autotests.elements.items;


import project.lighthouse.autotests.common.item.CommonItem;
import project.lighthouse.autotests.common.pageObjects.CommonPageObject;

public class InputOnlyVisible extends CommonItem {

    public InputOnlyVisible(CommonPageObject pageObject, String name) {
        super(pageObject, name);
    }

    @Override
    public void setValue(String value) {
        getOnlyVisibleWebElementFacade().type(value);
    }
}
