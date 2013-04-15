package project.lighthouse.autotests.pages.common;

import org.openqa.selenium.WebElement;

public class CommonItem {

    types type;
    WebElement element;

    public CommonItem(WebElement element, types type){
        this.type = type;
        this.element = element;
    }

    public static enum types {input, textarea, select, dateTime, autocomplete, date, nonType }

    public types getType(){
        return type;
    }

    public WebElement getWebElement(){
        return element;
    }
}
