package project.lighthouse.autotests.helper;

import org.junit.Assert;
import org.openqa.selenium.By;
import project.lighthouse.autotests.common.CommonItem;

import static junit.framework.Assert.assertEquals;

/**
 * This class is used to check and assert field length
 */
public class FieldChecker {

    private CommonItem commonItem;

    public FieldChecker(CommonItem commonItem) {
        this.commonItem = commonItem;
    }

    public void assertLabelTitle() {
        Assert.assertEquals(
                commonItem.getLabel(),
                commonItem.getVisibleWebElement().findElement(By.xpath("./../label")).getText());
    }

    public void assertFieldLength(String elementName, int fieldLength) {
        int length;
        switch (commonItem.getOnlyVisibleWebElementFacade().getTagName()) {
            case "input":
                length = commonItem.getOnlyVisibleWebElementFacade().getValue().length();
                break;
            case "textarea":
                length = commonItem.getOnlyVisibleWebElementFacade().getValue().length();
                break;
            default:
                length = commonItem.getOnlyVisibleWebElementFacade().getText().length();
                break;
        }
        assertFieldLength(elementName, fieldLength, length);
    }

    private void assertFieldLength(String elementName, int fieldLength, int actualLength) {
        assertEquals(
                String.format("The '%s' field doesn't contains '%s' symbols. It actually contains '%s' symbols.",
                        elementName,
                        fieldLength,
                        actualLength),
                actualLength, fieldLength);
    }
}
