package project.lighthouse.autotests.pages;

import net.thucydides.core.annotations.findby.FindBy;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Buttons.navigationBar.NavigationBarLinkFacade;
import project.lighthouse.autotests.elements.bootstrap.buttons.PrimaryBtnFacade;

public class MenuNavigationBar extends CommonPageObject {

    @FindBy(xpath = "//*[@id='side-menu']/*[@class='user-panel']/*[@class='info']/p")
    @SuppressWarnings("unused")
    private WebElement userNameWebElement;

    public MenuNavigationBar(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
    }

    public String getUserEmailText() {
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
        return new NavigationBarLinkFacade(this, "Ассортимент");
    }

    public NavigationBarLinkFacade getStoresMenuItem() {
        return new NavigationBarLinkFacade(this, "Магазины");
    }

    public NavigationBarLinkFacade getInvoicesMenuItem() {
        return new NavigationBarLinkFacade(this, "Накладные");
    }

    public NavigationBarLinkFacade getWriteOffsMenuItem() {
        return new NavigationBarLinkFacade(this, "Списания");
    }

    public NavigationBarLinkFacade getSettingsMenuItem() {
        return new NavigationBarLinkFacade(this, "Настройки");
    }

    public NavigationBarLinkFacade getStockMovementMenuItem() {
        return new NavigationBarLinkFacade(this, "Товародвижение");
    }

    public void launchPostButtonClick() {
        new PrimaryBtnFacade(this, "Запустить кассу").click();
    }
}
