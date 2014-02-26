package project.lighthouse.autotests.jbehave.menuNavigation;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.When;
import project.lighthouse.autotests.steps.MenuNavigationSteps;

public class WhenMenuNavigationUserSteps {

    @Steps
    MenuNavigationSteps menuNavigationSteps;

    @When("the user clicks the menu report item")
    public void whenTheUserClicksTheMenuReportItemClick() {
        menuNavigationSteps.reportMenuItemClick();
    }

    @When("the user clicks the menu suppliers item")
    public void whenTheUserClicksTheMenuSuppliersItemClick() {
        menuNavigationSteps.supplierMenuItemClick();
    }
}
