package project.lighthouse.autotests.jbehave.menuNavigation;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Then;
import project.lighthouse.autotests.steps.MenuNavigationSteps;

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
}
