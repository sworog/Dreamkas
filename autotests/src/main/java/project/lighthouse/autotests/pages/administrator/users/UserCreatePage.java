package project.lighthouse.autotests.pages.administrator.users;

import net.thucydides.core.annotations.DefaultUrl;
import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Buttons.ButtonFacade;
import project.lighthouse.autotests.elements.Input;
import project.lighthouse.autotests.elements.SelectByValue;
import project.lighthouse.autotests.helper.RoleReplacer;

import java.util.Map;

@DefaultUrl("/users/create")
public class UserCreatePage extends CommonPageObject {

    public UserCreatePage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        put("name", new Input(this, By.xpath("//input[@name='name']")));
        put("position", new Input(this, By.xpath("//input[@name='position']")));
        put("username", new Input(this, By.xpath("//input[@name='username']")));
        put("password", new Input(this, By.xpath("//input[@name='password']")));
        put("role", new SelectByValue(this, By.xpath("//select[@name='role']")));
    }

    public void userCreateButtonClick() {
        new ButtonFacade(this).click();
    }

    @Override
    public void fieldInput(ExamplesTable fieldInputTable) {
        for (Map<String, String> row : fieldInputTable.getRows()) {
            String elementName = row.get("elementName");
            String inputText = row.get("value");
            String updatedText = (elementName.equals("role")) ? RoleReplacer.replace(inputText) : inputText;
            input(elementName, updatedText);
        }
    }
}
