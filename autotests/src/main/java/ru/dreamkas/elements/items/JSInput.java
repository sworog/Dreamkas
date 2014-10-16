package ru.dreamkas.elements.items;

import ru.dreamkas.common.item.CommonItem;
import ru.dreamkas.common.pageObjects.CommonPageObject;
import ru.dreamkas.handler.field.FieldChecker;
import ru.dreamkas.helper.DateTimeHelper;

public class JSInput extends CommonItem {

    private String name;

    public JSInput(CommonPageObject pageObject, String name) {
        super(pageObject, name);
        this.name = name;
    }

    @Override
    public void setValue(String value) {
        getVisibleWebElementFacade();
        String jsScript = String.format(
                "document.getElementsByName('%s')[0].value='%s'",
                name,
                DateTimeHelper.getDate(value));
        getPageObject().evaluateJavascript(jsScript);
        evaluateUpdatingQueryScript();
    }

    public void evaluateUpdatingQueryScript() {
        String commitChangesScript = String.format(
                "document.querySelector('.receiptFinder').block.findReceipts('[name=\"%s\"]')",
                name);
        getPageObject().evaluateJavascript(commitChangesScript);
    }

    @Override
    public String getText() {
        return getVisibleWebElementFacade().getValue();
    }

    @Override
    public FieldChecker getFieldChecker() {
        return new FieldChecker(this) {
            @Override
            public void assertValueEqual(String expectedValue) {
                String convertedValue = DateTimeHelper.getDate(expectedValue);
                super.assertValueEqual(convertedValue);
            }
        };
    }
}
