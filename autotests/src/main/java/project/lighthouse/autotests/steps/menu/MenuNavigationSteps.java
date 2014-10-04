package project.lighthouse.autotests.steps.menu;

import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import project.lighthouse.autotests.elements.preLoader.BodyPreLoader;
import project.lighthouse.autotests.pages.MenuNavigationBar;

public class MenuNavigationSteps extends ScenarioSteps {

    MenuNavigationBar menuNavigationBar;

    @Step
    public void reportMenuItemClick() {
        new BodyPreLoader(getDriver()).await();
        menuNavigationBar.getReportMenuItem().click();
        new BodyPreLoader(getDriver()).await();
    }

    @Step
    public void supplierMenuItemClick() {
        new BodyPreLoader(getDriver()).await();
        menuNavigationBar.getSuppliersMenuItem().click();
        new BodyPreLoader(getDriver()).await();
    }

    @Step
    public void catalogMenuItemClick() {
        new BodyPreLoader(getDriver()).await();
        menuNavigationBar.getCatalogMenuItem().click();
        new BodyPreLoader(getDriver()).await();
    }

    @Step
    public void storesMenuItemClick() {
        new BodyPreLoader(getDriver()).await();
        menuNavigationBar.getStoresMenuItem().click();
        new BodyPreLoader(getDriver()).await();
    }

    @Step
    public void stockMovementMenuItemClick() {
        new BodyPreLoader(getDriver()).await();
        menuNavigationBar.getStockMovementMenuItem().click();
        new BodyPreLoader(getDriver()).await();
    }

    @Step
    public void launchPostButtonClick() {
        new BodyPreLoader(getDriver()).await();
        menuNavigationBar.launchPostButtonClick();
        new BodyPreLoader(getDriver()).await();
    }

    @Step
    public void userNameLinkClick() {
        new BodyPreLoader(getDriver()).await();
        menuNavigationBar.clickOnCommonItemWihName("userName");
    }

    @Step
    public void logOutButtonClick() {
        menuNavigationBar.logOutButtonClick();
    }
}
