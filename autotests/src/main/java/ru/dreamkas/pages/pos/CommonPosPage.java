package ru.dreamkas.pages.pos;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import ru.dreamkas.common.pageObjects.BootstrapPageObject;
import ru.dreamkas.elements.items.NonType;

public abstract class CommonPosPage extends BootstrapPageObject {

    public CommonPosPage(WebDriver driver) {
        super(driver);
        putSideBarElements();
    }

    private void putSideBarElements() {
        put("боковое меню", new NonType(this, By.className("sideBar")));
        put("боковое меню раскрыто", new NonType(this, By.xpath("//*[contains(@class, 'page page_pos page_sideBarVisible')]")));
        put("боковое меню спрятано", new NonType(this, By.xpath("//*[contains(@class, 'page page_pos')]")));
        put("posSideMenuLink", new NonType(this, By.xpath("//*[contains(@class, 'sideBar__item') and i[contains(@class, 'fa-shopping-cart')]]")));
        put("saleHistorySideMenuLink", new NonType(this, By.xpath("//*[contains(@class, 'sideBar__item') and i[contains(@class, 'fa-exchange')]]")));
        put("changeStoreSideMenuLink", new NonType(this, By.xpath("//*[contains(@class, 'sideBar__item') and contains(text(), 'Сменить магазин')]")));
        put("sideBar", new NonType(this, By.xpath("//*[@class='page__head']//*[contains(@class, 'sideBarLink')]")));
    }
}
