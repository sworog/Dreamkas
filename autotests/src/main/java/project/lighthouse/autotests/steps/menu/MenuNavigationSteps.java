package project.lighthouse.autotests.steps.menu;

import junit.framework.Assert;
import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.openqa.selenium.TimeoutException;
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
        try {
            menuNavigationBar.getReportMenuItem().click();
            Assert.fail("The menu navigation reports item link is visible!");
        } catch (TimeoutException ignored) {
        }
    }

    @Step
    public void supplierMenuItemClick() {
        new BodyPreLoader(getDriver()).await();
        menuNavigationBar.getSuppliersMenuItem().click();
    }

    @Step
    public void supplierMenuItemIsNotVisible() {
        try {
            new BodyPreLoader(getDriver()).await();
            menuNavigationBar.getSuppliersMenuItem().click();
            Assert.fail("The menu bar navigation suppliers item link is visible!");
        } catch (TimeoutException ignored) {
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
        try {
            new BodyPreLoader(getDriver()).await();
            menuNavigationBar.getOrdersMenuItem().click();
            Assert.fail("The menu bar navigation orders item link is visible!");
        } catch (TimeoutException ignored) {
        }
    }

    @Step
    public void userNameLinkClick() {
        new BodyPreLoader(getDriver()).await();
        menuNavigationBar.userNameLinkClick();
    }
}
