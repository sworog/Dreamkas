package project.lighthouse.autotests.jbehave.menuNavigation;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Then;
import project.lighthouse.autotests.steps.menu.MenuNavigationSteps;

public class ThenMenuNavigationUserSteps {

    @Steps
    MenuNavigationSteps menuNavigationSteps;

    @Then("the user checks the reports navigation menu item is not visible")
    public void thenTheUserChecksTheReportsNavigationMenuItemIsNotVisible() {
        menuNavigationSteps.reportMenuItemIsNotVisible();
    }

    @Then("the user checks the suppliers navigation menu item is not visible")
    public void thenTheUserChecksTheSuppliersNavigationMenuItemIsNotVisible() {
        menuNavigationSteps.supplierMenuItemIsNotVisible();
    }

    @Then("the user checks the orders navigation menu item is not visible")
    public void thenTheUserChecksTheOrdersNavigationMenuItemIsNotVisible() {
        menuNavigationSteps.ordersMenuItemIsNotVisible();
    }

    @Then("the user checks the users navigation menu item is visible")
    public void thenTheUserChecksTheUsersNavigationMenuItemIsVisible() {
        menuNavigationSteps.usersMenuItemIsVisible();
    }

    @Then("the user checks the catalog navigation menu item is visible")
    public void thenTheUserChecksTheCatalogNavigationMenuItemIsVisible() {
        menuNavigationSteps.catalogMenuItemIsVisible();
    }

    @Then("the user checks the invoices navigation menu item is visible")
    public void thenTheUserChecksTheInvoicesNavigationMenuItemIsVisible() {
        menuNavigationSteps.invoicesMenuItemIsVisible();
    }

    @Then("the user checks the writeOffs navigation menu item is visible")
    public void thenTheUserChecksTheWriteOffssNavigationMenuItemIsVisible() {
        menuNavigationSteps.writeOffMenuItemIsVisible();
    }

    @Then("the user checks the catalog navigation menu item is not visible")
    public void thenTheUserChecksTheCatalogNavigationMenuItemIsNotVisible() {
        menuNavigationSteps.catalogMenuItemIsNotVisible();
    }

    @Then("the user checks the invoices navigation menu item is not visible")
    public void thenTheUserChecksTheInvoicesNavigationMenuItemIsNotVisible() {
        menuNavigationSteps.invoicesMenuItemIsNotVisible();
    }

    @Then("the user checks the writeOffs navigation menu item is not visible")
    public void thenTheUserChecksTheWriteOffssNavigationMenuItemIsNotVisible() {
        menuNavigationSteps.writeOffMenuItemIsNotVisible();
    }

    @Then("the user checks the users navigation menu item is not visible")
    public void thenTheUserChecksTheUsersNavigationMenuItemIsNotVisible() {
        menuNavigationSteps.usersMenuItemIsNotVisible();
    }

    @Then("the user checks the settings navigation menu item is visible")
    public void thenTheUserChecksTheSettingsNavigationMenuItemIsVisible() {
        menuNavigationSteps.settingsMenuItemIsVisible();
    }

    @Then("the user checks the settings navigation menu item is not visible")
    public void thenTheUserChecksTheSettingsNavigationMenuItemIsNotVisible() {
        menuNavigationSteps.settingsMenuItemIsNotVisible();
    }
}
