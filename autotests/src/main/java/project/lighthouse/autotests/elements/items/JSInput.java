package project.lighthouse.autotests.elements.items;

import project.lighthouse.autotests.common.CommonItem;
import project.lighthouse.autotests.common.CommonPageObject;

public class JSInput extends CommonItem {

    private String name;

    public JSInput(CommonPageObject pageObject, String name) {
        super(pageObject, name);
        this.name = name;
    }

    @Override
    public void setValue(String value) {
        String jsScript = String.format("document.getElementsByName('%s')[0].setAttribute('value', '%s')", name, value);
        getPageObject().evaluateJavascript(jsScript);
    }
}
