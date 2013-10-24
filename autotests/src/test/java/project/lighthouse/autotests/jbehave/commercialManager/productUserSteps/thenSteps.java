package project.lighthouse.autotests.jbehave.commercialManager.productUserSteps;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.steps.commercialManager.ProductSteps;

public class ThenSteps {

    @Steps
    ProductSteps productSteps;

    @Then("the user checks the product writeOff list contains entry $examplesTable")
    public void thenTheUserChecksTheProductWriteOffListContainsEntry(ExamplesTable examplesTable) {
        productSteps.checkProductWriteOffListObject(examplesTable);
    }

    @Then("the user checks the product local navigation writeoffs link is not present")
    public void thenTheUserChecksTheProductLocalNaviWriteOffsLinkIsNotPresent() {
        productSteps.productWriteOffLinkIsNotPresent();
    }

}
