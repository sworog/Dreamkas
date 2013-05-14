package project.lighthouse.autotests.pages.elements;

import project.lighthouse.autotests.common.CommonPageObject;

public class Textarea extends Input {

    public Textarea(CommonPageObject pageObject, String name) {
        super(pageObject, name);
    }

    @Override
    public int length() {
        return $().getValue().length();
    }
}
