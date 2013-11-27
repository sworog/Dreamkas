package project.lighthouse.autotests.jbehave.departmentManager;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Given;
import org.jbehave.core.annotations.Then;
import project.lighthouse.autotests.steps.departmentManager.AmountSteps;

public class AmountUserSteps {

    @Steps
    AmountSteps amountSteps;

    @Given("the user opens amount list page")
    public void givenTheUserOpensAmountListPage() {
        amountSteps.AmountListPageOpen();
    }

    @Deprecated
    @Then("the user checks the product with '$skuValue' sku has '$name' equal to '$expectedValue' on amounts page")
    public void checkProductWithSkuHasExpectedValue(String skuValue, String name, String expectedValue) {
        amountSteps.checkProductWithSkuHasExpectedValue(skuValue, name, expectedValue);
    }

    @Then("the user checks the product with '$skuValue' sku has '$name' element equal to '$expectedValue' on amounts page")
    public void thenTheUserChecksTheProductWithValueHasElement(String value, String elementName, String expectedValue) {
        amountSteps.checkListItemHasExpectedValueByFindByLocator(value, elementName, expectedValue);
    }
}
