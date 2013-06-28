package project.lighthouse.autotests.pages.authorization;

import net.thucydides.core.annotations.DefaultUrl;
import org.openqa.selenium.By;
import org.openqa.selenium.Cookie;
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
        logOutButtonClick();
        isAuthorized = false;
    }

    public void logOutButtonClick() {
        String logOutButtonXpath = "//*[@class='topBar__logoutLink']";
        findVisibleElement(By.xpath(logOutButtonXpath)).click();
    }

    public void beforeScenario() {
        if (isAuthorized) {
            Cookie token = getDriver().manage().getCookieNamed("token");
            if (token != null) {
                getDriver().manage().deleteCookie(token);
            }
        }
    }

    public void checkUser(String userName) {
        String userXpath = "//*[@class='topBar__userName']";
        String actualUserName = find(By.xpath(userXpath)).getText();
        if (!userName.equals(actualUserName)) {
            String errorMessage = String.format("The user name is '%s'. Should be '%s'.", actualUserName, userName);
            throw new AssertionError(errorMessage);
        }
    }

    public boolean loginFormIsVisible() {
        return commonPage.isElementVisible(By.id("form_login"));
    }

    public void loginFormIsPresent() {
        if (!loginFormIsVisible()) {
            throw new AssertionError("The log out is not successful!");
        }
    }

    public void authorizationFalse(String userName, String password) {
        authorization(userName, password);
        isAuthorized = false;
    }
}
