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

    public NavigationBarLinkFacade getReportMenuItem() {
        return new NavigationBarLinkFacade(this, "Отчеты");
    }

    public NavigationBarLinkFacade getSuppliersMenuItem() {
        return new NavigationBarLinkFacade(this, "Поставщики");
    }

    public NavigationBarLinkFacade getOrdersMenuItem() {
        return new NavigationBarLinkFacade(this, "Заказы");
    }

    public NavigationBarLinkFacade getUsersMenuItem() {
        return new NavigationBarLinkFacade(this, "Пользователи");
    }

    public NavigationBarLinkFacade getCatalogMenuItem() {
        return new NavigationBarLinkFacade(this, "Товары");
    }

    public NavigationBarLinkFacade getInvoicesMenuItem() {
        return new NavigationBarLinkFacade(this, "Накладные");
    }

    public NavigationBarLinkFacade getWriteOffsMenuItem() {
        return new NavigationBarLinkFacade(this, "Списания");
    }
}
