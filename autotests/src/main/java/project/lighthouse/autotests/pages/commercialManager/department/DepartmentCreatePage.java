package project.lighthouse.autotests.pages.commercialManager.department;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.common.CommonView;
import project.lighthouse.autotests.elements.Input;

public class DepartmentCreatePage extends CommonPageObject {
    CommonView commonView = new CommonView(getDriver());

    public DepartmentCreatePage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        items.put("number", new Input(this, "number"));
        items.put("name", new Input(this, "name"));
    }

    public WebElement submitButton() {
        return findElement(By.cssSelector("input[type=submit]"));
    }
}
