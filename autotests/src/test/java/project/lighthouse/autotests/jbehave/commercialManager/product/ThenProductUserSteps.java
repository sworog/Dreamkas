package project.lighthouse.autotests.jbehave.commercialManager.product;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Then;
import org.jbehave.core.model.ExamplesTable;
import project.lighthouse.autotests.steps.commercialManager.ProductSteps;

public class ThenProductUserSteps {

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

    @Then("the user sees no edit product button")
    public void thenTheUserSeesNoEditProductButton() {
        productSteps.editProductButtonIsNotPresent();
    }

    @Then("the user sees no create new product button")
    public void thenTheUserSeesNoCreateNewProductButton() {
        productSteps.newProductCreateButtonIsNotPresent();
    }

    @Then("the user checks the products list contain product with name '$name'")
    public void thenTheUserChecksTheProductsListContainProductWithName(String name) {
        productSteps.productObjectCollectionContainObjectWithLocator(name);
    }
}
