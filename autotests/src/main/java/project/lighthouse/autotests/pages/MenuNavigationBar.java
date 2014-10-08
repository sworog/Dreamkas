package project.lighthouse.autotests.pages;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.pageObjects.CommonPageObject;
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
        put("userName", new NonType(this, By.xpath("//*[@class='sideBar__userName']")));
        put("reportMenuItem", new NavigationBarLinkFacade(this, "Отчеты"));
        put("supplierMenuItem", new NavigationBarLinkFacade(this, "Поставщики"));
        put("catalogMenuItem", new NavigationBarLinkFacade(this, "Ассортимент"));
        put("storeMenuItem", new NavigationBarLinkFacade(this, "Магазины"));
        put("stockMovementMenuItem", new NavigationBarLinkFacade(this, "Товародвижение"));
        put("launchPosButton", new PrimaryBtnFacade(this, "Запустить кассу"));
        put("logOutButton", new NavigationLinkFacade(this, "Выйти"));
    }
}
