package project.lighthouse.autotests.steps.menu;

import junit.framework.Assert;
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
        if (!menuNavigationBar.getReportMenuItem().isInvisible()) {
            Assert.fail("The menu navigation reports item link is visible!");
        }
    }

    @Step
    public void supplierMenuItemClick() {
        new BodyPreLoader(getDriver()).await();
        menuNavigationBar.getSuppliersMenuItem().click();
    }

    @Step
    public void supplierMenuItemIsNotVisible() {
        new BodyPreLoader(getDriver()).await();
        if (!menuNavigationBar.getSuppliersMenuItem().isInvisible()) {
            Assert.fail("The menu bar navigation suppliers item link is visible!");
        }
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
    public void catalogMenuItemClick() {
        new BodyPreLoader(getDriver()).await();
        menuNavigationBar.getCatalogMenuItem().click();
    }

    @Step
    public void invoicesMenuItemClick() {
        new BodyPreLoader(getDriver()).await();
        menuNavigationBar.getInvoicesMenuItem().click();
    }

    @Step
    public void writeOffsMenuItemClick() {
        new BodyPreLoader(getDriver()).await();
        menuNavigationBar.getWriteOffsMenuItem().click();
    }

    @Step
    public void ordersMenuItemIsNotVisible() {
        new BodyPreLoader(getDriver()).await();
        if (!menuNavigationBar.getOrdersMenuItem().isInvisible()) {
            Assert.fail("The menu bar navigation orders item link is visible!");
        }
    }

    @Step
    public void userNameLinkClick() {
        new BodyPreLoader(getDriver()).await();
        menuNavigationBar.userNameLinkClick();
    }
}
