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
        menuNavigationBar.reportMenuItemClick();
    }

    @Step
    public void reportMenuItemIsNotVisible() {
        try {
            menuNavigationBar.reportMenuItemClick();
            Assert.fail("The menu navigation reports item link is visible!");
        } catch (Exception ignored) {
        }
    }

    @Step
    public void supplierMenuItemClick() {
        new BodyPreLoader(getDriver()).await();
        menuNavigationBar.suppliersMenuItemClick();
    }

    @Step
    public void supplierMenuItemIsNotVisible() {
        try {
            new BodyPreLoader(getDriver()).await();
            menuNavigationBar.suppliersMenuItemClick();
            Assert.fail("The menu bar navigation suppliers item link is visible!");
        } catch (Exception ignored) {
        }
    }

    @Step
    public void ordersMenuItemClick() {
        new BodyPreLoader(getDriver()).await();
        menuNavigationBar.ordersMenuItemClick();
    }

    @Step
    public void ordersMenuItemIsNotVisible() {
        try {
            new BodyPreLoader(getDriver()).await();
            menuNavigationBar.ordersMenuItemClick();
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
