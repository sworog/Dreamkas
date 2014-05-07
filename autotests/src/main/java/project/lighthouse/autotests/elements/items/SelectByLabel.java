package project.lighthouse.autotests.elements.items;

import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.common.CommonItem;
import project.lighthouse.autotests.common.CommonPageObject;

public class SelectByLabel extends CommonItem {

    private By labelParentFindBy;

    public SelectByLabel(CommonPageObject pageObject, By findBy, By labelParentFindBy) {
        super(pageObject, findBy);
        this.labelParentFindBy = labelParentFindBy;
    }

    @Override
    public void setValue(String value) {
        By labelBy = By.xpath("//label[text()=\"" + value + "\"]");
        WebElement labelParent = getPageObject().findVisibleElement(labelParentFindBy);
        labelParent.findElement(labelBy).click();
    }
}
