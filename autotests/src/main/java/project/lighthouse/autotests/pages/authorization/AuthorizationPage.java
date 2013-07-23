package project.lighthouse.autotests.pages.authorization;

import net.thucydides.core.annotations.DefaultUrl;
import org.openqa.selenium.By;
import org.openqa.selenium.Cookie;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.pages.administrator.users.UserCreatePage;

import java.util.HashMap;
import java.util.Map;

@DefaultUrl("/")
public class AuthorizationPage extends UserCreatePage {

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
    }

    public void authorization(String userName) {
        String password = users.get(userName);
        authorization(userName, password);
    }

    public void authorization(String userName, String password) {
        type(By.name("username"), userName);
        type(By.name("password"), password);
        String loginButtonXpath = "//*[@class='button button_color_blue']";
        click(By.xpath(loginButtonXpath));
        checkUser(userName);
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

    public void error403IsPresent() {
        try {
            String error404Xpath = getError403Xpath();
            findElement(By.xpath(error404Xpath));
        } catch (Exception e) {
            String errorMessage = "The error 403 is not present on the page!";
            throw new AssertionError(errorMessage);
        }
    }

    public String getError403Xpath() {
        return "//body[@class='page page_common_403']";
    }

    public void error403IsNotPresent() {
        try {
            String error404Xpath = getError403Xpath();
            waiter.waitUntilIsNotVisible(By.xpath(error404Xpath));
        } catch (Exception e) {
            String errorMessage = "The error 403 is present on the page!";
            throw new AssertionError(errorMessage);
        }
    }
}
