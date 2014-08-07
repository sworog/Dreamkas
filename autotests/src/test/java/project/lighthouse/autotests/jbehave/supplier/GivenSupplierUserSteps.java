package project.lighthouse.autotests.jbehave.supplier;

import net.thucydides.core.annotations.Steps;
import net.thucydides.core.steps.ScenarioSteps;
import org.jbehave.core.annotations.Given;
import project.lighthouse.autotests.steps.supplier.SupplierSteps;

public class GivenSupplierUserSteps extends ScenarioSteps {

    @Steps
    SupplierSteps supplierSteps;

    @Given("the user opens the supplier list page")
    public void givenTheUserOpensTheSupplierListPage() {
        supplierSteps.supplierListPageOpen();
    }
}
