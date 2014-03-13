package project.lighthouse.autotests.pages.commercialManager.department;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Input;

public class DepartmentCreatePage extends CommonPageObject {

    public DepartmentCreatePage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        put("number", new Input(this, "number"));
        put("name", new Input(this, "name"));
    }

    public WebElement submitButton() {
        return findElement(
                By.cssSelector("input[type=submit]")
        );
    }
}
