package project.lighthouse.autotests.jbehave.departmentManager.returnUserSteps;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.steps.commercialManager.ProductSteps;

public class ThenSteps {

    @Steps
    ProductSteps productSteps;

    @Then("the user checks the product return list contains entry $examplesTable")
    public void thenTheUserChecksTheProductBalanceList(ExamplesTable examplesTable) {
        productSteps.checkProductReturnListObject(examplesTable);
    }
}
