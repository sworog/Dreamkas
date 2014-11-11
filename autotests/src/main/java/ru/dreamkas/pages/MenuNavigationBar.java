package ru.dreamkas.pages;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import ru.dreamkas.common.pageObjects.CommonPageObject;
import ru.dreamkas.elements.Buttons.navigationBar.NavigationBarLinkFacade;
import ru.dreamkas.elements.bootstrap.buttons.PrimaryBtnFacade;
import ru.dreamkas.elements.items.NonType;

public class MenuNavigationBar extends CommonPageObject {

    public MenuNavigationBar(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
        put("userName", new NonType(this, By.xpath("//*[@class='sideBar__userName']")));
        put("reportMenuItem", new NonType(this, By.xpath("//*[@class='sideBar__item ' and i[contains(@class, 'chart')]]")));
        put("supplierMenuItem", new NavigationBarLinkFacade(this, "Поставщики"));
        put("catalogMenuItem", new NavigationBarLinkFacade(this, "Ассортимент"));
        put("storeMenuItem", new NavigationBarLinkFacade(this, "Магазины"));
        put("stockMovementMenuItem", new NonType(this, By.xpath("//*[@class='sideBar__item ' and i[contains(@class, 'exchange')]]")));
        put("launchPosButton", new PrimaryBtnFacade(this, "Запустить кассу"));
        put("logOutButton", new NonType(this, By.xpath("//*[@class='sideBar__actionLink']/i[@class='fa fa-sign-out']/..")));
        put("настройки пользователя", new NonType(this, By.xpath("//*[@class='sideBar__actionLink']/i[@class='fa fa-cog']/..")));
    }
}
