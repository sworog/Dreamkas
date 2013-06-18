package project.lighthouse.autotests.pages.users;

import net.thucydides.core.annotations.DefaultUrl;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.pages.elements.Input;
import project.lighthouse.autotests.pages.elements.Select;
import project.lighthouse.autotests.pages.product.ProductCreatePage;

@DefaultUrl("/url")
public class UserCreatePage extends ProductCreatePage {

    public UserCreatePage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        items.put("name", new Input(this, "name"));
        items.put("position", new Input(this, "position"));
        items.put("login", new Input(this, "login"));
        items.put("password", new Input(this, "password"));
        items.put("role", new Select(this, "role"));
    }

    public void userCreateButtonClick() {
        String userCreateButtonXpath = "";
        findElement(By.xpath(userCreateButtonXpath)).click();
        preloaderWait();
    }

    public void preloaderWait() {
        String preloaderXpath = "//*[contains(@class, 'preloader')]";
        waiter.waitUntilIsNotVisible(By.xpath(preloaderXpath));
    }

    public void backToTheUsersListPageLink() {
        String link = "";
        findElement(By.xpath(link)).click();
    }
}
