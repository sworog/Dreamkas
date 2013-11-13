package project.lighthouse.autotests.objects.web.abstractObjects;

import org.openqa.selenium.By;
import org.openqa.selenium.WebElement;

public class ObjectProperty {

    private By findBy;
    private String text;
    private WebElement element;

    public ObjectProperty(WebElement element, By FindBy) {
        this.findBy = FindBy;
        this.element = element.findElement(findBy);
        text = element.getText();
    }

    public By getFindBy() {
        return findBy;
    }

    public String getText() {
        return text;
    }

    public void click() {
        element.click();
    }
}
