package project.lighthouse.autotests.jbehave.deprecated.departmentManager.order;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import project.lighthouse.autotests.steps.deprecated.departmentManager.order.OrderSteps;

public class GivenOrderUserSteps {

    @Steps
    OrderSteps orderSteps;

    @Given("the user opens order create page")
    public void givenTheUserOpensOrderCreatePage() {
        orderSteps.openOrderCreatePage();
    }

    @Given("the user opens orders list page")
    public void givenTheUserOpensOrdersListPage() {
        orderSteps.openOrdersListPage();
    }
}
