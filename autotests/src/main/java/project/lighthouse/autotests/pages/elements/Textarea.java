package project.lighthouse.autotests.pages.elements;

import net.thucydides.core.pages.PageObject;
import org.openqa.selenium.By;

public class Textarea extends Input {

    types type = types.textarea;

    public Textarea(PageObject pageObject, By findBy) {
        super(pageObject, findBy);
    }

    public Textarea(PageObject pageObject, String name) {
        super(pageObject, name);
    }

    @Override
    public int length() {
        return $().getValue().length();
    }
}
