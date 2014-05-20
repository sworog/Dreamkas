package project.lighthouse.autotests.jbehave.commercialManager.product;

import net.thucydides.core.annotations.Steps;
import org.jbehave.core.annotations.Alias;
import org.jbehave.core.annotations.When;
import project.lighthouse.autotests.steps.commercialManager.ProductSteps;

public class WhenProductUserSteps {

    @Steps
    ProductSteps productSteps;

    @When("the user clicks the product local navigation writeoffs link")
    public void whenTheUSerClicksTheProductLocalNavigationWriteOffsLink() {
        productSteps.productWriteOffsLinkClick();
    }

    @When("the user clicks the product local navigation returns link")
    public void whenTheUSerClicksTheProductLocalNavigationReturnsLink() {
        productSteps.productReturnsLinkClick();
    }

    @When("the user clicks on the product writeOff with '$number' number")
    public void whenTheUserClicksOnTheProductWriteOff(String number) {
        productSteps.productWriteOffListObjectClick(number);
    }

    @When("the user clicks on product with name '$name'")
    @Alias("the user clicks on product with <productName>")
    public void whenTheUserClicksOnProductWithName(String productName) {
        productSteps.productObjectClickByLocator(productName);
    }
}
