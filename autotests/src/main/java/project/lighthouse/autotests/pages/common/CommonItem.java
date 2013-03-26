package project.lighthouse.autotests.pages.common;

import org.openqa.selenium.WebElement;

public class CommonItem {

    types type;
    WebElement element;

    public CommonItem(WebElement element, types type){
        this.type = type;
        this.element = element;
    }

    public static enum types {input, textarea, checkbox, date}

    public types GetType(){
        return type;
    }

    public WebElement GetWebElement(){
        return element;
    }
}
