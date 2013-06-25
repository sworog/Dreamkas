package project.lighthouse.autotests.pages.authorization;

import net.thucydides.core.annotations.DefaultUrl;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;

import java.util.HashMap;
import java.util.Map;

@DefaultUrl("/")
public class AuthorizationPage extends CommonPageObject {

    public Map<String, String> users = new HashMap();
    Boolean isAuthorized = false;

    public AuthorizationPage(WebDriver driver) {
        super(driver);
        users();
    }

    public void users() {
        users.put("watchman", "lighthouse");
        users.put("commercialManager", "lighthouse");
        users.put("storeManager", "lighthouse");
        users.put("departmentManager", "lighthouse");
    }

    @Override
    public void createElements() {
        //To change body of implemented methods use File | Settings | File Templates.
    }

    public void authorization(String userName) {
        String password = users.get(userName);
        authorization(userName, password);
    }

    public void authorization(String userName, String password) {
        find(By.name("username")).type(userName);
        find(By.name("password")).type(password);
        String loginButtonXpath = "//*[@class='button button_color_blue']/input";
        findBy(loginButtonXpath).click();
        isAuthorized = true;
    }

    public void logOut() {
        String logOutButtonXpath = "//*[@class='topBar']/*[@class='topBar__logoutLink button']";
        findBy(logOutButtonXpath).click();
        isAuthorized = false;
    }

    public void afterScenarioFailure() {
        if (isAuthorized) {
            logOut();
        }
    }

    public void checkUser(String userName) {
        String userXpath = "";
        findBy(userXpath).shouldBeVisible();
    }
}
