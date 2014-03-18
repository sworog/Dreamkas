package project.lighthouse.autotests.steps.menu;

import junit.framework.Assert;
import net.thucydides.core.annotations.Step;
import net.thucydides.core.steps.ScenarioSteps;
import org.openqa.selenium.TimeoutException;
import project.lighthouse.autotests.elements.preLoader.BodyPreLoader;
import project.lighthouse.autotests.pages.MenuNavigation;

public class MenuNavigationSteps extends ScenarioSteps {

    MenuNavigation menuNavigation;

    @Step
    public void reportMenuItemClick() {
        new BodyPreLoader(getDriver()).await();
        menuNavigation.reportMenuItemClick();
    }

    @Step
    public void reportMenuItemIsNotVisible() {
        try {
            menuNavigation.reportMenuItemClick();
            Assert.fail("The menu navigation reports item link is visible!");
        } catch (Exception ignored) {
        }
    }

    @Step
    public void supplierMenuItemClick() {
        new BodyPreLoader(getDriver()).await();
        menuNavigation.suppliersMenuItemClick();
    }

    @Step
    public void supplierMenuItemIsNotVisible() {
        try {
            new BodyPreLoader(getDriver()).await();
            menuNavigation.suppliersMenuItemClick();
            Assert.fail("The menu bar navigation suppliers item link is visible!");
        } catch (Exception ignored) {
        }
    }

    @Step
    public void ordersMenuItemClick() {
        new BodyPreLoader(getDriver()).await();
        menuNavigation.ordersMenuItemClick();
    }

    @Step
    public void ordersMenuItemIsNotVisible() {
        try {
            new BodyPreLoader(getDriver()).await();
            menuNavigation.ordersMenuItemClick();
            Assert.fail("The menu bar navigation orders item link is visible!");
        } catch (TimeoutException ignored) {
        }
    }
}
