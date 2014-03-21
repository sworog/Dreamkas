package project.lighthouse.autotests.pages;

import net.thucydides.core.annotations.findby.FindBy;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Buttons.navigationBar.NavigationBarLinkFacade;

public class MenuNavigationBar extends CommonPageObject {

    @FindBy(xpath = "//*[@class='navigationBar__userName']")
    @SuppressWarnings("unused")
    private WebElement userNameWebElement;

    public MenuNavigationBar(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
    }

    public String getUserNameText() {
        return findVisibleElement(userNameWebElement).getText();
    }

    public void userNameLinkClick() {
        findVisibleElement(userNameWebElement).click();
    }

    public void reportMenuItemClick() {
        new NavigationBarLinkFacade(this, "Отчеты").click();
    }

    public void suppliersMenuItemClick() {
        new NavigationBarLinkFacade(this, "Поставщики").click();
    }

    public void ordersMenuItemClick() {
        new NavigationBarLinkFacade(this, "Заказы").click();
    }
}
