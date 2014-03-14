package project.lighthouse.autotests.jbehave.departmentManager.order;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import project.lighthouse.autotests.steps.departmentManager.order.OrderSteps;

public class GivenOrderUserSteps {

    @Steps
    OrderSteps orderSteps;

    @Given("The user opens order create page")
    public void givenTheUserOpensOrderCreatePage() {
        orderSteps.openPage();
    }
}
