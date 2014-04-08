package project.lighthouse.autotests.helper;

import org.openqa.selenium.By;
import project.lighthouse.autotests.common.CommonItem;

import static org.hamcrest.Matchers.equalTo;
import static org.junit.Assert.assertThat;

/**
 * This class is used to check and assert field
 */
public class FieldChecker {

    private CommonItem commonItem;

    public FieldChecker(CommonItem commonItem) {
        this.commonItem = commonItem;
    }

    public void assertLabelTitle() {
        assertThat(
                commonItem.getLabel(),
                equalTo(commonItem.getVisibleWebElement().findElement(By.xpath("./../label")).getText()));
    }

    public void assertValueEqual(String expectedValue) {
        String actualValue;
        switch (commonItem.getVisibleWebElement().getTagName()) {
            case "input":
                actualValue = commonItem.getVisibleWebElementFacade().getValue();
                break;
            default:
                actualValue = commonItem.getVisibleWebElementFacade().getText();
                break;
        }
        assertThat(
                actualValue,
                equalTo(expectedValue));
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
        assertThat(
                String.format("The '%s' field doesn't contains '%s' symbols. It actually contains '%s' symbols.",
                        elementName,
                        fieldLength,
                        length),
                length,
                equalTo(fieldLength));
    }
}
