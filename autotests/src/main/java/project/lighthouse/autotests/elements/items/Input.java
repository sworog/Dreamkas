package project.lighthouse.autotests.elements.items;

import org.openqa.selenium.By;
import project.lighthouse.autotests.common.item.CommonItem;
import project.lighthouse.autotests.common.objects.CommonPageObject;
import project.lighthouse.autotests.pages.modal.ModalWindowPage;

public class Input extends CommonItem {

    public Input(CommonPageObject pageObject, By findBy) {
        super(pageObject, findBy);
    }

    public Input(ModalWindowPage modalWindowPage, String xpath) {
        super(modalWindowPage, xpath);
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
    public String getText() {
        return getVisibleWebElementFacade().getValue();
    }
}
