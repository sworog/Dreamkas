package project.lighthouse.autotests.jbehave.departmentManager.order;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.When;
import project.lighthouse.autotests.steps.departmentManager.order.OrderMenuNavigationSteps;

public class WhenOrderMenuNavigationSteps {

    @Steps
    OrderMenuNavigationSteps orderMenuNavigationSteps;

    @When("the user clicks the create order link on order page menu navigation")
    public void whenTheUserClicksTheCreateOrderLinkOnOrderPageMenuNavigation() {
        orderMenuNavigationSteps.createOrderLinkClick();
    }
}
