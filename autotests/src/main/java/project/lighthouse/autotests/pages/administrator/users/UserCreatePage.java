package project.lighthouse.autotests.pages.administrator.users;

import net.thucydides.core.annotations.DefaultUrl;
import org.jbehave.core.model.ExamplesTable;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.elements.Buttons.ButtonFacade;
import project.lighthouse.autotests.elements.Input;
import project.lighthouse.autotests.elements.SelectByValue;
import project.lighthouse.autotests.elements.preLoader.PreLoader;
import project.lighthouse.autotests.pages.commercialManager.product.ProductCreatePage;

import java.util.HashMap;
import java.util.Map;

@DefaultUrl("/users/create")
public class UserCreatePage extends ProductCreatePage {

    public UserCreatePage(WebDriver driver) {
        super(driver);
    }

    private static final HashMap<String, String> roles = new HashMap<String, String>() {
        {
            put("commercialManager", "ROLE_COMMERCIAL_MANAGER");
            put("storeManager", "ROLE_STORE_MANAGER");
            put("departmentManager", "ROLE_DEPARTMENT_MANAGER");
            put("administrator", "ROLE_ADMINISTRATOR");
        }
    };

    @Override
    public void createElements() {
        items.put("name", new Input(this, By.xpath("//input[@name='name']")));
        items.put("position", new Input(this, By.xpath("//input[@name='position']")));
        items.put("username", new Input(this, By.xpath("//input[@name='username']")));
        items.put("password", new Input(this, By.xpath("//input[@name='password']")));
        items.put("role", new SelectByValue(this, By.xpath("//select[@name='role']")));
    }

    public void userCreateButtonClick() {
        new ButtonFacade(getDriver()).click();
        new PreLoader(getDriver()).await();
    }

    public void backToTheUsersListPageLink() {
        String link = "//*[@class='page__backLink']";
        findElement(By.xpath(link)).click();
    }

    public String replaceSelectedValue(String value) {
        for (Map.Entry<String, String> role : roles.entrySet()) {
            value = value.replaceAll(role.getKey(), role.getValue());
        }
        return value;
    }

    @Override
    public void fieldInput(ExamplesTable fieldInputTable) {
        for (Map<String, String> row : fieldInputTable.getRows()) {
            String elementName = row.get("elementName");
            String inputText = row.get("value");
            String updatedText = (elementName.equals("role")) ? replaceSelectedValue(inputText) : inputText;
            input(elementName, updatedText);
        }
    }
}
