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
    }

    @Step
    public void reportMenuItemIsNotVisible() {
        new BodyPreLoader(getDriver()).await();
        menuNavigationBar.getReportMenuItem().shouldBeNotVisible();
    }

    @Step
    public void supplierMenuItemClick() {
        new BodyPreLoader(getDriver()).await();
        menuNavigationBar.getSuppliersMenuItem().click();
    }

    @Step
    public void supplierMenuItemIsNotVisible() {
        new BodyPreLoader(getDriver()).await();
        menuNavigationBar.getSuppliersMenuItem().shouldBeNotVisible();
    }

    @Step
    public void ordersMenuItemClick() {
        new BodyPreLoader(getDriver()).await();
        menuNavigationBar.getOrdersMenuItem().click();
    }

    @Step
    public void usersMenuItemClick() {
        new BodyPreLoader(getDriver()).await();
        menuNavigationBar.getUsersMenuItem().click();
    }

    @Step
    public void usersMenuItemIsVisible() {
        new BodyPreLoader(getDriver()).await();
        menuNavigationBar.getUsersMenuItem().shouldBeVisible();
    }

    @Step
    public void usersMenuItemIsNotVisible() {
        new BodyPreLoader(getDriver()).await();
        menuNavigationBar.getUsersMenuItem().shouldBeNotVisible();
    }

    @Step
    public void catalogMenuItemClick() {
        new BodyPreLoader(getDriver()).await();
        menuNavigationBar.getCatalogMenuItem().click();
    }

    @Step
    public void catalogMenuItemIsVisible() {
        new BodyPreLoader(getDriver()).await();
        menuNavigationBar.getCatalogMenuItem().shouldBeVisible();
    }

    @Step
    public void catalogMenuItemIsNotVisible() {
        new BodyPreLoader(getDriver()).await();
        menuNavigationBar.getCatalogMenuItem().shouldBeNotVisible();
    }

    @Step
    public void invoicesMenuItemClick() {
        new BodyPreLoader(getDriver()).await();
        menuNavigationBar.getInvoicesMenuItem().click();
    }

    @Step
    public void invoicesMenuItemIsVisible() {
        new BodyPreLoader(getDriver()).await();
        menuNavigationBar.getInvoicesMenuItem().shouldBeVisible();
    }

    @Step
    public void invoicesMenuItemIsNotVisible() {
        new BodyPreLoader(getDriver()).await();
        menuNavigationBar.getInvoicesMenuItem().shouldBeNotVisible();
    }

    @Step
    public void writeOffsMenuItemClick() {
        new BodyPreLoader(getDriver()).await();
        menuNavigationBar.getWriteOffsMenuItem().click();
    }

    @Step
    public void writeOffMenuItemIsVisible() {
        new BodyPreLoader(getDriver()).await();
        menuNavigationBar.getWriteOffsMenuItem().shouldBeVisible();
    }

    @Step
    public void writeOffMenuItemIsNotVisible() {
        new BodyPreLoader(getDriver()).await();
        menuNavigationBar.getWriteOffsMenuItem().shouldBeNotVisible();
    }

    @Step
    public void ordersMenuItemIsNotVisible() {
        new BodyPreLoader(getDriver()).await();
        menuNavigationBar.getOrdersMenuItem().shouldBeNotVisible();
    }

    @Step
    public void userNameLinkClick() {
        new BodyPreLoader(getDriver()).await();
        menuNavigationBar.userNameLinkClick();
    }

    @Step
    public void settingsMenuItemIsVisible() {
        new BodyPreLoader(getDriver()).await();
        menuNavigationBar.getSettingsMenuItem().shouldBeVisible();
    }

    @Step
    public void settingsMenuItemIsNotVisible() {
        new BodyPreLoader(getDriver()).await();
        menuNavigationBar.getSettingsMenuItem().shouldBeNotVisible();
    }
}
