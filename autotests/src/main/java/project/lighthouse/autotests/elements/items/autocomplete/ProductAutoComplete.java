package project.lighthouse.autotests.elements.items.autocomplete;

import net.thucydides.core.annotations.findby.By;
import project.lighthouse.autotests.common.item.CommonItem;
import project.lighthouse.autotests.common.pageObjects.CommonPageObject;
import project.lighthouse.autotests.pages.modal.ModalWindowPage;

public class ProductAutoComplete extends CommonItem {

    public ProductAutoComplete(ModalWindowPage modalWindowPage, String xpath) {
        super(modalWindowPage, xpath);
    }

    public ProductAutoComplete(CommonPageObject pageObject, org.openqa.selenium.By findBy) {
        super(pageObject, findBy);
    }

    @Override
    public void setValue(String value) {
        if (value.startsWith("!")) {
            value = value.substring(1);
            getVisibleWebElementFacade().type(value);
        } else {
            getVisibleWebElementFacade().type(value);
            getPageObject().findVisibleElement(By.xpath("//*[contains(@class, 'tt-dataset')]//*[text()='" + value + "']")).click();
        }
    }

    @Override
    public String getText() {
        return getVisibleWebElementFacade().getValue();
    }
}
