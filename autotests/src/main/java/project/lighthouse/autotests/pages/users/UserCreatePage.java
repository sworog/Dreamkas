package project.lighthouse.autotests.pages.users;

import net.thucydides.core.annotations.DefaultUrl;
import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.pages.elements.Input;
import project.lighthouse.autotests.pages.elements.Select;
import project.lighthouse.autotests.pages.product.ProductCreatePage;

@DefaultUrl("/user/create")
public class UserCreatePage extends ProductCreatePage {

    public UserCreatePage(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        items.put("name", new Input(this, By.xpath("//input[@name='name']")));
        items.put("position", new Input(this, By.xpath("//input[@name='position']")));
        items.put("username", new Input(this, By.xpath("//input[@name='username']")));
        items.put("password", new Input(this, By.xpath("//input[@name='password']")));
        items.put("role", new Select(this, By.xpath("//select[@name='role']")));
    }

    public void userCreateButtonClick() {
        String userCreateButtonXpath = "//*[@class='button button_color_blue']/input";
        findBy(userCreateButtonXpath).click();
        preloaderWait();
    }

    public void preloaderWait() {
        String preloaderXpath = "//*[contains(@class, 'preloader')]";
        waiter.waitUntilIsNotVisible(By.xpath(preloaderXpath));
    }

    public void backToTheUsersListPageLink() {
        String link = "//*[@class='page__backLink']";
        findElement(By.xpath(link)).click();
    }
}
