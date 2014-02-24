package project.lighthouse.autotests.pages.authorization;

import net.thucydides.core.annotations.DefaultUrl;
import org.openqa.selenium.By;
import org.openqa.selenium.Cookie;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Buttons.ButtonFacade;
import project.lighthouse.autotests.elements.Input;

import java.util.HashMap;
import java.util.Map;

import static junit.framework.Assert.*;

@DefaultUrl("/")
public class AuthorizationPage extends CommonPageObject {

    private Map<String, String> users = new HashMap<>();
    private Boolean isAuthorized = false;

    public AuthorizationPage(WebDriver driver) {
        super(driver);
        users();
    }

    private void users() {
        users.put("watchman", "lighthouse");
        users.put("commercialManager", "lighthouse");
        users.put("storeManager", "lighthouse");
        users.put("departmentManager", "lighthouse");
    }

    @Override
    public void createElements() {
        items.put("userName", new Input(this, "username"));
        items.put("password", new Input(this, "password"));
    }

    public void authorization(String userName) {
        String password = users.get(userName);
        authorization(userName, password);
    }

    public void authorization(String userName, String password) {
        authorization(userName, password, false);
    }

    public void authorization(String userName, String password, Boolean isFalse) {
        workAroundTypeForUserName(userName);
        input("password", password);
        new ButtonFacade(getDriver(), "Войти").click();
        if (!isFalse) {
            checkUser(userName);
        }
        isAuthorized = true;
    }

    // TODO fix this in future

    /**
     * This is actually bad type workaround for failing logging in.
     * For some reasons the type method is disturbed and the userName is not typed fully.
     *
     * @param inputText
     */
    private void workAroundTypeForUserName(String inputText) {
        input("userName", inputText);
        if (!$(findVisibleElement(By.name("username"))).getValue().equals(inputText)) {
            workAroundTypeForUserName(inputText);
        }
    }

    public void logOut() {
        logOutButtonClick();
        isAuthorized = false;
    }

    public void logOutButtonClick() {
        findVisibleElement(By.xpath("//*[@class='navigationBar__userName']")).click();
        new ButtonFacade(getDriver(), "Выйти").click();
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
        String userXpath = "//*[@class='navigationBar__userName']";
        String actualUserName = findVisibleElement(By.xpath(userXpath)).getText();
        assertEquals(
                String.format("The user name is '%s'. Should be '%s'.", actualUserName, userName),
                userName, actualUserName
        );
    }

    public boolean loginFormIsVisible() {
        return getWaiter().isElementVisible(By.id("form_login"));
    }

    public void loginFormIsPresent() {
        assertTrue("The log out is not successful!", loginFormIsVisible());
    }

    public void authorizationFalse(String userName, String password) {
        authorization(userName, password, true);
        isAuthorized = false;
    }

    public void error403IsPresent() {
        try {
            String error404Xpath = getError403Xpath();
            findElement(By.xpath(error404Xpath));
        } catch (Exception e) {
            fail("The error 403 is not present on the page!");
        }
    }

    public void error404isPresent() {
        try {
            String error404Xpath = "//*[contains(@class, 'page_error_404')]";
            findElement(By.xpath(error404Xpath));
        } catch (Exception e) {
            fail("The error 403 is not present on the page!");
        }
    }

    public String getError403Xpath() {
        return "//*[contains(@class, 'page_error_403')]";
    }

    public void error403IsNotPresent() {
        try {
            String error404Xpath = getError403Xpath();
            getWaiter().waitUntilIsNotVisible(By.xpath(error404Xpath));
        } catch (Exception e) {
            fail("The error 403 is present on the page!");
        }
    }
}
