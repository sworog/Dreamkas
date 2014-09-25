package project.lighthouse.autotests.pages.pos;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import org.openqa.selenium.WebElement;
import project.lighthouse.autotests.common.objects.BootstrapPageObject;
import project.lighthouse.autotests.elements.items.NonType;

public abstract class CommonPosPage extends BootstrapPageObject {

    public CommonPosPage(WebDriver driver) {
        super(driver);
        putSideBarElements();
    }

    private void putSideBarElements() {
        put("боковое меню", new NonType(this, By.className("sideBar")));
        put("боковое меню раскрыто", new NonType(this, By.xpath("//*[contains(@class, 'page page_pos page_sideBarVisible')]")));
        put("боковое меню спрятано", new NonType(this, By.xpath("//*[contains(@class, 'page page_pos')]")));
    }

    public WebElement getCashRegistrySideMenuLink() {
        return findVisibleElement(By.xpath("//*[contains(@class, 'sideBar__item') and i[contains(@class, 'fa-shopping-cart')]]"));
    }

    public void clickOnSaleHistorySideMenuLink() {
        click(By.xpath("//*[contains(@class, 'sideBar__item') and i[contains(@class, 'fa-exchange')]]"));
    }

    public void clickOnChangeStoreSideMenuLink() {
        click(By.xpath("//*[contains(@class, 'sideBar__item') and contains(text(), 'Сменить магазин')]"));
    }

    public void clickOnSideBarInteraction() {
        click(By.xpath("//*[@class='page__head']//*[contains(@class, 'sideBarLink')]"));
    }
}
