package project.lighthouse.autotests.jbehave.commercialManager.supplier;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import project.lighthouse.autotests.steps.commercialManager.supplier.SupplierSteps;

public class GivenSupplierSteps {

    @Steps
    SupplierSteps supplierSteps;

    @Given("the user opens supplier create page")
    public void givenTheUserOpensSupplierCreatePage() {
        supplierSteps.openSupplierCreatePage();
    }
}
