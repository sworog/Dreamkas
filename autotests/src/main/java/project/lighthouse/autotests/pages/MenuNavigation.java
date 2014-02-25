package project.lighthouse.autotests.pages;

import org.openqa.selenium.WebDriver;
import project.lighthouse.autotests.common.CommonPageObject;
import project.lighthouse.autotests.elements.Buttons.navigationBar.NavigationBarLink;

public class MenuNavigation extends CommonPageObject {

    public MenuNavigation(WebDriver driver) {
        super(driver);
    }

    @Override
    public void createElements() {
    }

    public void reportMenuItemClick() {
        new NavigationBarLink(this, "Отчеты").click();
    }

    public void suppliersMenuItemClick() {
        new NavigationBarLink(this, "Поставщики").click();
    }
}
