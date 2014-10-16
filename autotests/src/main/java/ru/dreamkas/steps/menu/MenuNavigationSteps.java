package ru.dreamkas.steps.menu;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import ru.dreamkas.elements.preLoader.BodyPreLoader;
import ru.dreamkas.pages.MenuNavigationBar;

public class MenuNavigationSteps extends ScenarioSteps {

    MenuNavigationBar menuNavigationBar;

    @Step
    public void reportMenuItemClick() {
        new BodyPreLoader(getDriver()).await();
        menuNavigationBar.clickOnCommonItemWihName("reportMenuItem");
        new BodyPreLoader(getDriver()).await();
    }

    @Step
    public void supplierMenuItemClick() {
        new BodyPreLoader(getDriver()).await();
        menuNavigationBar.clickOnCommonItemWihName("supplierMenuItem");
        new BodyPreLoader(getDriver()).await();
    }

    @Step
    public void catalogMenuItemClick() {
        new BodyPreLoader(getDriver()).await();
        menuNavigationBar.clickOnCommonItemWihName("catalogMenuItem");
        new BodyPreLoader(getDriver()).await();
    }

    @Step
    public void storesMenuItemClick() {
        new BodyPreLoader(getDriver()).await();
        menuNavigationBar.clickOnCommonItemWihName("storeMenuItem");
        new BodyPreLoader(getDriver()).await();
    }

    @Step
    public void stockMovementMenuItemClick() {
        new BodyPreLoader(getDriver()).await();
        menuNavigationBar.clickOnCommonItemWihName("stockMovementMenuItem");
        new BodyPreLoader(getDriver()).await();
    }

    @Step
    public void launchPostButtonClick() {
        new BodyPreLoader(getDriver()).await();
        menuNavigationBar.clickOnCommonItemWihName("launchPosButton");
        new BodyPreLoader(getDriver()).await();
    }

    @Step
    public void userNameLinkClick() {
        new BodyPreLoader(getDriver()).await();
        menuNavigationBar.clickOnCommonItemWihName("userName");
    }

    @Step
    public void logOutButtonClick() {
        menuNavigationBar.clickOnCommonItemWihName("logOutButton");
    }
}
