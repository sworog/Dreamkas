package project.lighthouse.autotests.handler.field;

import org.openqa.selenium.By;
import project.lighthouse.autotests.common.item.CommonItem;
import project.lighthouse.autotests.elements.items.SelectByVisibleText;

import static org.hamcrest.Matchers.is;
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
        String actualValue;

        if (commonItem instanceof SelectByVisibleText) {
            actualValue = commonItem.getVisibleWebElement().findElement(By.xpath("./../../label")).getText();
        } else {
            actualValue = commonItem.getVisibleWebElement().findElement(By.xpath("./../label")).getText();
        }

        assertThat(actualValue, is(commonItem.getLabel()));
    }

    public void assertValueEqual(String expectedValue) {
        assertThat(commonItem.getText(), is(expectedValue));
    }

    public void assertValueEqual(String message, String expectedValue) {
        assertThat(message, commonItem.getText(), is(expectedValue));
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
                is(fieldLength)
        );
    }
}
