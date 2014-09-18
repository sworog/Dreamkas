package project.lighthouse.autotests.pages.pos;

import org.openqa.selenium.By;
import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.BootstrapPageObject;
import project.lighthouse.autotests.elements.bootstrap.SideMenuLink;
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

    public SideMenuLink getCashRegistrySideMenuLink() {
        return new SideMenuLink(this, "Касса");
    }

    public void clickOnSaleHistorySideMenuLink() {
        new SideMenuLink(this, "История продаж").click();
    }

    public void clickOnChangeStoreSideMenuLink() {
        new SideMenuLink(this, "Сменить магазин").click();
    }

    public void clickOnSideBarInteraction() {
        click(By.xpath("//*[contains(@class, 'sideBarLink')]"));
    }
}
