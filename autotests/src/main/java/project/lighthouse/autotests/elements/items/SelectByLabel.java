package project.lighthouse.autotests.elements.items;

import org.openqa.selenium.By;
import project.lighthouse.autotests.common.CommonItem;
import project.lighthouse.autotests.common.CommonPageObject;


public class SelectByLabel extends CommonItem {
    public SelectByLabel(CommonPageObject pageObject, By findBy) {
        super(pageObject, findBy);
    }

    public SelectByLabel(CommonPageObject pageObject, String name) {
        super(pageObject, name);
    }

    public SelectByLabel(CommonPageObject pageObject, String name, String label) {
        super(pageObject, name, label);
    }

    @Override
    public void setValue(String value) {
        By labelBy = By.xpath("//label[text()=\"" + value + "\"]");
        getVisibleWebElement().findElement(labelBy).click();
    }
}
