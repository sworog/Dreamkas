package project.lighthouse.autotests.helper;

import net.thucydides.core.pages.WebElementFacade;

import static junit.framework.Assert.assertEquals;

/**
 * This class is used to check and assert field length
 */
public class FieldLengthChecker {

    private WebElementFacade fieldWEbWebElementFacade;

    public FieldLengthChecker(WebElementFacade fieldWEbWebElementFacade) {
        this.fieldWEbWebElementFacade = fieldWEbWebElementFacade;
    }

    public void check(String elementName, int fieldLength) {
        int length;
        switch (fieldWEbWebElementFacade.getTagName()) {
            case "input":
                length = fieldWEbWebElementFacade.getTextValue().length();
                break;
            case "textarea":
                length = fieldWEbWebElementFacade.getValue().length();
                break;
            default:
                length = fieldWEbWebElementFacade.getText().length();
                break;
        }
        assertFieldLength(elementName, fieldLength, length);
    }

    private void assertFieldLength(String elementName, int fieldLength, int actualLength) {
        assertEquals(
                String.format("The '%s' field doesn't contains '%s' symbols. It actually contains '%s' symbols.",
                        elementName,
                        actualLength,
                        fieldLength),
                actualLength, fieldLength);
    }
}
