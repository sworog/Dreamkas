package project.lighthouse.autotests.pages.elements;

import org.openqa.selenium.By;
import project.lighthouse.autotests.pages.common.CommonPageObject;

public class Textarea extends Input {

    public Textarea(CommonPageObject pageObject, By findBy) {
        super(pageObject, findBy);
    }

    public Textarea(CommonPageObject pageObject, String name) {
        super(pageObject, name);
    }

    @Override
    public int length() {
        return $().getValue().length();
    }
}
