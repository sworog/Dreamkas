package project.lighthouse.autotests.elements.items;

import org.openqa.selenium.By;
import project.lighthouse.autotests.common.CommonItem;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.pages.modal.ModalWindowPage;

public class SelectByVisibleText extends CommonItem {

    public SelectByVisibleText(CommonPageObject pageObject, By findBy) {
        super(pageObject, findBy);
    }

    public SelectByVisibleText(CommonPageObject pageObject, String name) {
        super(pageObject, name);
    }

    public SelectByVisibleText(ModalWindowPage modalWindowPage, String xpath) {
        super(modalWindowPage, xpath);
    }

    public SelectByVisibleText(CommonPageObject pageObject, String name, String label) {
        super(pageObject, name, label);
    }

    @Override
    public void setValue(String label) {
        selectByVisibleText(label);
    }

    @Override
    public String getText() {
        return getVisibleWebElementFacade().getSelectedVisibleTextValue().trim();
    }
}
