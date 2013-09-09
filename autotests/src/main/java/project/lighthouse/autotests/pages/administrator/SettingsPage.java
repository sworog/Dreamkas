package project.lighthouse.autotests.pages.administrator;

import net.thucydides.core.annotations.DefaultUrl;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Input;

@DefaultUrl("/settings")
public class SettingsPage extends CommonPageObject {

    public SettingsPage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        items.put("url", new Input(this, "url"));
        items.put("userName", new Input(this, "userName"));
        items.put("password", new Input(this, "password"));
    }

    public WebElement getSaveSettingButtonWebElement() {
        return findVisibleElement(By.xpath(""));
    }
}
