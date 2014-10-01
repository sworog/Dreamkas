package project.lighthouse.autotests.pages;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.objects.CommonPageObject;
import project.lighthouse.autotests.elements.Buttons.localNavigation.NavigationLinkFacade;
import project.lighthouse.autotests.elements.Buttons.navigationBar.NavigationBarLinkFacade;
import project.lighthouse.autotests.elements.bootstrap.buttons.PrimaryBtnFacade;
import project.lighthouse.autotests.elements.items.NonType;

public class MenuNavigationBar extends CommonPageObject {

    public MenuNavigationBar(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        put("userName", new NonType(this, By.xpath("//*[@id='side-menu']/*[@class='user-panel']/*[@class='info']/p")));
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

    public void logOutButtonClick() {
        new NavigationLinkFacade(this, "Выйти").click();
    }
}
