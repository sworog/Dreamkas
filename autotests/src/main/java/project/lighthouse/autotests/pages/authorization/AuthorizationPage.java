package project.lighthouse.autotests.pages.authorization;

import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;

import java.util.HashMap;
import java.util.Map;

public class AuthorizationPage extends CommonPageObject {

    public Map<String, String> users = new HashMap();
    Boolean isAuthorized = false;

    public AuthorizationPage(WebDriver driver) {
        super(driver);
        users();
    }

    public void users() {
        users.put("watchman", "lighthouse");
        users.put("commercialManager", "123456");
        users.put("storeManager", "123456");
        users.put("departmentManager", "123456");
    }

    @Override
    public void createElements() {
        //To change body of implemented methods use File | Settings | File Templates.
    }

    public void authorization(String userName) {
        getDriver().manage().deleteCookieNamed("cookie");
        String password = users.get(userName);
        authorization(userName, password);

        isAuthorized = true;
    }

    public void authorization(String userName, String password) {
        String userNameXpath = "";
        findBy(userNameXpath).type(userName);
        String passwordXpath = "";
        findBy(passwordXpath).type(password);
        String loginButtonXpath = "";
        findBy(loginButtonXpath).click();
    }

    public void logOut() {
        if (isAuthorized) {
            String logOutButtonXpath = "";
            findBy(logOutButtonXpath).click();
        }
    }

    public void checkUser(String userName) {
        String userXpath = "";
        findBy(userXpath).shouldBeVisible();
    }
}
