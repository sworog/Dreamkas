package project.lighthouse.autotests.jbehave.deprecated.commercialManager;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.annotations.When;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.steps.deprecated.commercialManager.ProductSteps;

public class ProductDataDrivenSteps {

    @Steps
    ProductSteps productSteps;

    ExamplesTable examplesTable;

    @When("the user fills form with following data $fieldInputTable")
    public void whenTheUserInputsInElementFields(ExamplesTable fieldInputTable) {
        productSteps.fieldType(fieldInputTable);
        examplesTable = fieldInputTable;
    }

    @Then("the user checks the elements values matches input data")
    public void thenTheUserChecksTheElementValues() {
        productSteps.checkCardValue(examplesTable);
    }

}
